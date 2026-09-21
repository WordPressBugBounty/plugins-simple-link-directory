<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'QCOPD_SLD_Usage_Feedback') ) {
	
	class QCOPD_SLD_Usage_Feedback {
		
		private $wpbot_version = '1.0.0';
		private $home_url = '';
		private $plugin_file = '';
		private $plugin_name = '';
		private $options = array();
		private $require_optin = true;
		private $include_goodbye_form = true;

		
		/**
		 * Class constructor
		 *
		 * @param $_home_url				The URL to the site we're sending data to
		 * @param $_plugin_file				The file path for this plugin
		 * @param $_options					Plugin options to track
		 * @param $_require_optin			Whether user opt-in is required (always required on WordPress.org)
		 * @param $_include_goodbye_form	Whether to include a form when the user deactivates
		 * @param $_marketing				Marketing method:
		 *									0: Don't collect email addresses
		 *									1: Request permission same time as tracking opt-in
		 *									2: Request permission after opt-in
		 */
		public function __construct( 
			$_plugin_file,
			$_home_url,
			
			$_require_optin=true,
			$_include_goodbye_form=true) {

			$this->plugin_file = $_plugin_file;
			$this->home_url = $_home_url;
			$this->plugin_name = basename( $this->plugin_file, '.php' );

			$this->require_optin = $_require_optin;
			$this->include_goodbye_form = $_include_goodbye_form;


			// Deactivation hook
			register_deactivation_hook( $this->plugin_file, array( $this, 'deactivate_this_plugin' ) );
			
			// Get it going
			$this->init();
			
		}
		
		public function init() {
			
			// Deactivation
			add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'filter_action_links' ) );
			add_action( 'admin_footer', array( $this, 'goodbye_ajax' ) );
			add_action( 'wp_ajax_goodbye_form', array( $this, 'goodbye_form_callback' ) );
			
		}

		// In theme's functions.php or plug-in code:

		function set_content_type(){
			return "text/html";
		}
		
		
		/**
		 * Send the data to the home site
		 *
		 * @since 1.0.0
		 */
		public function send_data( $body ) {
			$message = '';
			foreach($body as $key=>$value){
				
				if($key=='active_plugins'){
					$message .='<p> <b>'.$key.'</b>: '.(implode(', ',$value)).' </p>';
				}
				elseif($key=='inactive_plugins'){
					$message .='<p> <b>'.$key.'</b>: '.(implode(', ',$value)).' </p>';
				}else{
					$message .='<p> <b>'.$key.'</b>: '.$value.' </p>';
				}
				
			}
			
			    $title   = 'Plugin Deactivation Notice';
				$headers = array('From: Anonymous <mailer@just-a-fake-from-address.com>');
				
				add_filter( 'wp_mail_content_type', array($this, 'set_content_type') );
				$email = wp_mail($this->home_url, $title, $message, $headers);
				remove_filter('wp_mail_content_type', array($this, 'set_content_type'));

				return $email;

		}
		
		/**
		 * Here we collect most of the data
		 * 
		 * @since 1.0.0
		 */
		public function get_data() {
	
			// Use this to pass error messages back if necessary
			$body['message'] = '';
	
			// Use this array to send data back
			$body = array();


	
			/**
			 * Get our plugin data
			 * Currently we grab plugin name and version
			 * Or, return a message if the plugin data is not available
			 * @since 1.0.0
			 */
			$plugin = $this->plugin_data();
			if( empty( $plugin ) ) {
				// We can't find the plugin data
				// Send a message back to our home site
				$body['message'] .= esc_html( 'We can\'t detect any plugin information. This is most probably because you have not included the code in the plugin main file.', 'simple-link-directory' );
				$body['status'] = 'Data not found'; // Never translated
			} else {
				if( isset( $plugin['Name'] ) ) {
					$body['plugin'] = sanitize_text_field( $plugin['Name'] );
				}
				if( isset( $plugin['Version'] ) ) {
					$body['version'] = sanitize_text_field( $plugin['Version'] );
				}

			}

			// Return the data
			return $body;
	
		}
		
		/**
		 * Return plugin data
		 * @since 1.0.0
		 */
		public function plugin_data() {
			// Being cautious here
			if( ! function_exists( 'get_plugin_data' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}
			// Retrieve current plugin information
			$plugin = get_plugin_data( $this->plugin_file );
			return $plugin;
		}

		/**
		 * Deactivating plugin
		 * @since 1.0.0
		 */
		public function deactivate_this_plugin() {

			$body = $this->get_data();
			$body['status'] = 'Deactivated'; // Never translated
			$body['deactivated_date'] = gmdate('Y-m-d');
			$body['website_url'] = home_url();
			
			// Add deactivation form data
			if( false !== get_option( 'wpbot_deactivation_reason_' . $this->plugin_name ) ) {
				$body['deactivation_reason'] = get_option( 'wpbot_deactivation_reason_' . $this->plugin_name );
				delete_option('wpbot_deactivation_reason_' . $this->plugin_name);
			}
			if( false !== get_option( 'wpbot_deactivation_details_' . $this->plugin_name ) ) {
				$body['deactivation_details'] = get_option( 'wpbot_deactivation_details_' . $this->plugin_name );
				delete_option('wpbot_deactivation_details_' . $this->plugin_name);
			}
			if( false !== get_option( 'wpbot_deactivation_email_' . $this->plugin_name ) ) {
				$body['user_email'] = get_option( 'wpbot_deactivation_email_' . $this->plugin_name );
				delete_option('wpbot_deactivation_email_' . $this->plugin_name);
			}
			
			if( isset( $body['deactivation_reason'] ) || isset( $body['deactivation_details'] ) || isset( $body['user_email'] ) ) {
				$this->send_data( $body );
			}
			

		}
		
		/**
		 * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
		 * @since 1.0.0
		 */
		public function filter_action_links( $links ) {

			if( isset( $links['deactivate'] ) && $this->include_goodbye_form ) {
				$deactivation_link = $links['deactivate'];
				// Attach unique ID and class to the deactivation anchor
				$deactivation_link = str_replace( '<a ', '<a id="wpb-goodbye-link-' . esc_attr( $this->plugin_name ) . '" class="wpb-goodbye-deactivate-link" ', $deactivation_link );
				$links['deactivate'] = $deactivation_link;
			}
			return $links;
		}
		
		/*
		 * Form text strings
		 * These are non-filterable and used as fallback in case filtered strings aren't set correctly
		 * @since 1.0.0
		 */
		public function form_default_text() {
			$form = array();
			$form['heading'] = esc_html__( 'Quick Feedback', 'simple-link-directory' );
			$form['body']    = esc_html__( 'If you have a moment, please let us know why you are deactivating Simple Link Directory:', 'simple-link-directory' );
			$form['options'] = array(
				'temporary' => array(
					'title'        => esc_html__( 'I\'m only deactivating temporarily / Troubleshooting', 'simple-link-directory' ),
					'placeholder'  => '',
					'need_details' => false,
				),
				'pro_upgrade' => array(
					'title'        => esc_html__( 'I upgraded to Simple Link Directory Pro', 'simple-link-directory' ),
					'placeholder'  => '',
					'need_details' => false,
				),
				'broken' => array(
					'title'        => esc_html__( 'I couldn\'t get the plugin to work / Found a bug', 'simple-link-directory' ),
					'placeholder'  => esc_html__( 'What went wrong? Please describe the issue or bug...', 'simple-link-directory' ),
					'need_details' => true,
				),
				'missing_feature' => array(
					'title'        => esc_html__( 'Missing a specific feature I need', 'simple-link-directory' ),
					'placeholder'  => esc_html__( 'What feature would you like to see in Simple Link Directory?', 'simple-link-directory' ),
					'need_details' => true,
				),
				'better_plugin' => array(
					'title'        => esc_html__( 'I found a better directory plugin', 'simple-link-directory' ),
					'placeholder'  => esc_html__( 'Which plugin did you choose and what made you switch?', 'simple-link-directory' ),
					'need_details' => true,
				),
				'other' => array(
					'title'        => esc_html__( 'Other reason', 'simple-link-directory' ),
					'placeholder'  => esc_html__( 'Please share your feedback so we can improve...', 'simple-link-directory' ),
					'need_details' => true,
				),
			);
			$form['email']   = esc_html__( 'Your email (optional - if you\'d like us to follow up)', 'simple-link-directory' );
			$form['details'] = esc_html__( 'Can you share some more details?', 'simple-link-directory' );
			return $form;
		}
		
		/**
		 * Form text strings
		 * These can be filtered
		 * The filter hook must be unique to the plugin
		 * @since 1.0.0
		 */
		public function form_filterable_text() {
			$form = $this->form_default_text();
			return apply_filters( 'wpbot_form_text_' . esc_attr( $this->plugin_name ), $form );
		}
		
		/**
		 * Render goodbye modal in plugins.php footer
		 * @since 1.0.0
		 */
		public function goodbye_ajax() {
			global $pagenow;
			if ( 'plugins.php' !== $pagenow ) {
				return;
			}

			// Get our strings for the form
			$form = $this->form_filterable_text();
			if( ! isset( $form['heading'] ) || ! isset( $form['body'] ) || ! isset( $form['options'] ) || ! is_array( $form['options'] ) ) {
				$form = $this->form_default_text();
			}

			$plugin_slug = esc_attr( $this->plugin_name );
			$admin_email = esc_attr( get_option( 'admin_email' ) );
			$logo_url    = defined( 'SLD_QCOPD_IMG_URL' ) ? esc_url( SLD_QCOPD_IMG_URL . '/sld-logo.png' ) : esc_url( plugins_url( 'assets/images/sld-logo.png', $this->plugin_file ) );
			?>
			<div class="wpb-goodbye-form-bg" id="wpb-goodbye-form-bg-<?php echo $plugin_slug; ?>" style="display:none;"></div>

			<div class="wpb-goodbye-form-modal" id="wpb-goodbye-form-<?php echo $plugin_slug; ?>" role="dialog" aria-modal="true" aria-labelledby="wpb-goodbye-title-<?php echo $plugin_slug; ?>" style="display:none;">
				<div class="wpb-goodbye-modal-card">
					<div class="wpb-goodbye-form-head">
						<div class="wpb-goodbye-header-left">
							<div class="wpb-goodbye-header-icon">
								<img src="<?php echo $logo_url; ?>" alt="<?php esc_attr_e( 'Simple Link Directory', 'simple-link-directory' ); ?>" class="wpb-goodbye-logo-img" />
							</div>
							<div class="wpb-goodbye-header-text">
								<h3 class="wpb-goodbye-title" id="wpb-goodbye-title-<?php echo $plugin_slug; ?>"><?php echo esc_html( $form['heading'] ); ?></h3>
								<span class="wpb-goodbye-subtitle"><?php esc_html_e( 'Simple Link Directory', 'simple-link-directory' ); ?></span>
							</div>
						</div>
						<button type="button" class="wpb-goodbye-close-btn" aria-label="<?php esc_attr_e( 'Close dialog', 'simple-link-directory' ); ?>">&times;</button>
					</div>

					<div class="wpb-goodbye-form-content">
						<div class="wpb-goodbye-form-body">
							<?php if ( ! empty( $form['body'] ) ) : ?>
								<p class="wpb-goodbye-intro"><?php echo esc_html( $form['body'] ); ?></p>
							<?php endif; ?>

							<div class="wpb-goodbye-options-list">
								<?php
								$i = 0;
								foreach ( $form['options'] as $key => $option_data ) :
									$i++;
									$opt_val = is_array( $option_data ) ? ( isset( $option_data['title'] ) ? $option_data['title'] : $key ) : $option_data;
									$opt_placeholder = ( is_array( $option_data ) && isset( $option_data['placeholder'] ) ) ? $option_data['placeholder'] : '';
									$need_details = ( is_array( $option_data ) && isset( $option_data['need_details'] ) ) ? ( $option_data['need_details'] ? '1' : '0' ) : '1';
									$opt_id = 'wpb-opt-' . $plugin_slug . '-' . $i;
									?>
									<label class="wpb-goodbye-option-item" for="<?php echo esc_attr( $opt_id ); ?>">
										<input type="radio" name="wpb-goodbye-option-radio" id="<?php echo esc_attr( $opt_id ); ?>" value="<?php echo esc_attr( $opt_val ); ?>" data-placeholder="<?php echo esc_attr( $opt_placeholder ); ?>" data-need-details="<?php echo esc_attr( $need_details ); ?>" />
										<span class="wpb-option-text"><?php echo esc_html( $opt_val ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>

							<div id="wpb_additional_content" class="wpb-goodbye-additional" style="display:none;">
								<div class="wpb-field-group">
									<label for="wpb-goodbye-reasons" class="wpb-field-label"><?php echo esc_html( $form['details'] ); ?></label>
									<textarea name="wpb-goodbye-reasons" id="wpb-goodbye-reasons" rows="3" class="wpb-form-textarea" placeholder="<?php esc_attr_e( 'Please share what we could improve...', 'simple-link-directory' ); ?>"></textarea>
								</div>
								<div class="wpb-field-group">
									<label for="wpb-goodbye-email" class="wpb-field-label"><?php echo esc_html( $form['email'] ); ?></label>
									<input type="email" name="wpb-goodbye-email" id="wpb-goodbye-email" class="wpb-form-input" value="<?php echo $admin_email; ?>" placeholder="<?php esc_attr_e( 'your-email@example.com', 'simple-link-directory' ); ?>" />
								</div>
								<div id="wpbot_deactivation_error" class="wpb-error-message" style="display:none;"></div>
							</div>
						</div>

						<div class="deactivating-spinner" style="display:none;">
							<div class="wpb-spinner-graphic"></div>
							<p class="wpb-spinner-text"><?php esc_html_e( 'Submitting feedback and deactivating...', 'simple-link-directory' ); ?></p>
						</div>
					</div>

					<div class="wpb-goodbye-form-footer">
						<div class="wpb-footer-left">
							<button type="button" class="wpb-btn-secondary wpb-goodbye-cancel-btn"><?php esc_html_e( 'Cancel', 'simple-link-directory' ); ?></button>
						</div>
						<div class="wpb-footer-right">
							<a class="wpbot_just_deactivate" href="#" id="wpb-skip-deactivate"><?php esc_html_e( 'Skip & Deactivate', 'simple-link-directory' ); ?></a>
							<button type="button" id="wpb-submit-form" class="wpb-btn-primary wpbot_submit_deactivate"><?php esc_html_e( 'Submit & Deactivate', 'simple-link-directory' ); ?></button>
						</div>
					</div>
				</div>
			</div>

			<style type="text/css">
				.wpb-goodbye-form-bg {
					display: none;
					position: fixed !important;
					top: 0 !important;
					left: 0 !important;
					right: 0 !important;
					bottom: 0 !important;
					width: 100vw !important;
					height: 100vh !important;
					background: rgba(15, 23, 42, 0.7) !important;
					backdrop-filter: blur(4px) !important;
					-webkit-backdrop-filter: blur(4px) !important;
					z-index: 999998 !important;
				}
				body.wpb-form-active .wpb-goodbye-form-bg {
					display: block !important;
				}
				body.wpb-form-active {
					overflow: hidden !important;
				}
				.wpb-goodbye-form-modal {
					display: none;
					position: fixed !important;
					top: 50% !important;
					left: 50% !important;
					transform: translate(-50%, -50%) scale(0.96) !important;
					width: 520px !important;
					max-width: 92vw !important;
					background: #ffffff !important;
					border-radius: 12px !important;
					box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(0, 0, 0, 0.08) !important;
					z-index: 999999 !important;
					overflow: hidden !important;
					box-sizing: border-box !important;
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
					color: #1e293b !important;
					opacity: 0;
					transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
				}
				.wpb-goodbye-form-sld_modal.is-open {
					display: block !important;
					transform: translate(-50%, -50%) scale(1) !important;
					opacity: 1 !important;
				}
				.wpb-goodbye-modal-card {
					display: flex;
					flex-direction: column;
					max-height: 90vh;
				}
				.wpb-goodbye-form-head {
					background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
					color: #ffffff;
					padding: 16px 22px;
					display: flex;
					align-items: center;
					justify-content: space-between;
					position: relative;
				}
				.wpb-goodbye-header-left {
					display: flex;
					align-items: center;
					gap: 12px;
				}
				.wpb-goodbye-header-icon {
					display: flex;
					align-items: center;
					justify-content: center;
					width: 38px;
					height: 38px;
					border-radius: 9px;
					background: #ffffff;
					padding: 4px;
					box-sizing: border-box;
					box-shadow: 0 2px 5px rgba(0, 0, 0, 0.18);
					flex-shrink: 0;
				}
				.wpb-goodbye-header-icon img.wpb-goodbye-logo-img {
					width: 100%;
					height: 100%;
					object-fit: contain;
					display: block;
				}
				.wpb-goodbye-header-text .wpb-goodbye-title {
					margin: 0;
					font-size: 16px;
					font-weight: 600;
					color: #ffffff;
					line-height: 1.25;
					letter-spacing: -0.01em;
				}
				.wpb-goodbye-header-text .wpb-goodbye-subtitle {
					display: block;
					font-size: 12px;
					color: #94a3b8;
					margin-top: 2px;
					font-weight: 400;
				}
				.wpb-goodbye-close-btn {
					background: transparent;
					border: none;
					color: #94a3b8;
					font-size: 24px;
					line-height: 1;
					width: 32px;
					height: 32px;
					border-radius: 6px;
					cursor: pointer;
					display: flex;
					align-items: center;
					justify-content: center;
					transition: all 0.15s ease;
					padding: 0;
				}
				.wpb-goodbye-close-btn:hover {
					color: #ffffff;
					background: rgba(255, 255, 255, 0.15);
				}
				.wpb-goodbye-form-content {
					overflow-y: auto;
					flex: 1 1 auto;
					background: #ffffff;
				}
				.wpb-goodbye-form-body {
					padding: 20px 24px;
				}
				.wpb-goodbye-intro {
					margin: 0 0 16px 0;
					font-size: 13.5px;
					line-height: 1.5;
					color: #475569;
					font-weight: 450;
				}
				.wpb-goodbye-options-list {
					display: flex;
					flex-direction: column;
					gap: 8px;
				}
				.wpb-goodbye-option-item {
					display: flex;
					align-items: center;
					gap: 12px;
					padding: 10px 14px;
					border: 1px solid #e2e8f0;
					border-radius: 8px;
					cursor: pointer;
					background: #ffffff;
					transition: all 0.15s ease;
					user-select: none;
				}
				.wpb-goodbye-option-item:hover {
					border-color: #cbd5e1;
					background: #f8fafc;
				}
				.wpb-goodbye-option-item.is-selected {
					border-color: #0284c7;
					background: #f0f9ff;
					box-shadow: 0 0 0 1px #0284c7;
				}
				.wpb-goodbye-option-item input[type="radio"] {
					appearance: none;
					-webkit-appearance: none;
					width: 17px;
					height: 17px;
					border: 1.5px solid #94a3b8;
					border-radius: 50%;
					margin: 0;
					outline: none;
					cursor: pointer;
					display: grid;
					place-content: center;
					background: #fff;
					flex-shrink: 0;
					transition: all 0.15s ease;
				}
				.wpb-goodbye-option-item input[type="radio"]::before {
					content: "";
					width: 7px;
					height: 7px;
					border-radius: 50%;
					transform: scale(0);
					transition: transform 0.15s ease-in-out;
					background-color: #0284c7;
				}
				.wpb-goodbye-option-item input[type="radio"]:checked {
					border-color: #0284c7;
				}
				.wpb-goodbye-option-item input[type="radio"]:checked::before {
					transform: scale(1);
				}
				.wpb-goodbye-option-item .wpb-option-text {
					font-size: 13.5px;
					color: #334155;
					font-weight: 500;
					line-height: 1.35;
				}
				.wpb-goodbye-option-item.is-selected .wpb-option-text {
					color: #0369a1;
					font-weight: 600;
				}
				.wpb-goodbye-additional {
					margin-top: 16px;
					padding-top: 16px;
					border-top: 1px solid #f1f5f9;
					animation: wpbFadeSlide 0.2s ease-out;
				}
				@keyframes wpbFadeSlide {
					from { opacity: 0; transform: translateY(-6px); }
					to { opacity: 1; transform: translateY(0); }
				}
				.wpb-field-group {
					margin-bottom: 14px;
				}
				.wpb-field-group:last-child {
					margin-bottom: 0;
				}
				.wpb-field-label {
					display: block;
					font-size: 12.5px;
					font-weight: 600;
					color: #334155;
					margin-bottom: 6px;
				}
				.wpb-form-textarea,
				.wpb-form-input {
					width: 100% !important;
					box-sizing: border-box !important;
					padding: 8px 12px !important;
					font-size: 13px !important;
					color: #1e293b !important;
					background: #ffffff !important;
					border: 1px solid #cbd5e1 !important;
					border-radius: 6px !important;
					outline: none !important;
					transition: border-color 0.15s, box-shadow 0.15s !important;
					box-shadow: none !important;
					font-family: inherit !important;
				}
				.wpb-form-textarea:focus,
				.wpb-form-input:focus {
					border-color: #0284c7 !important;
					box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
				}
				.wpb-form-textarea {
					resize: vertical;
					min-height: 72px;
					line-height: 1.45;
				}
				.wpb-error-message {
					color: #e11d48;
					font-size: 12px;
					font-weight: 500;
					margin-top: 8px;
					background: #fff1f2;
					border: 1px solid #ffe4e6;
					padding: 6px 10px;
					border-radius: 6px;
				}
				.deactivating-spinner {
					padding: 45px 24px;
					text-align: center;
				}
				.wpb-spinner-graphic {
					display: inline-block;
					width: 36px;
					height: 36px;
					border: 3px solid rgba(2, 132, 199, 0.2);
					border-radius: 50%;
					border-top-color: #0284c7;
					animation: wpbSpin 0.8s linear infinite;
					margin-bottom: 14px;
				}
				@keyframes wpbSpin {
					to { transform: rotate(360deg); }
				}
				.wpb-spinner-text {
					margin: 0;
					font-size: 14px;
					font-weight: 500;
					color: #475569;
				}
				.wpb-goodbye-form-footer {
					background: #f8fafc;
					border-top: 1px solid #e2e8f0;
					padding: 14px 22px;
					display: flex;
					align-items: center;
					justify-content: space-between;
					gap: 12px;
				}
				.wpb-footer-right {
					display: flex;
					align-items: center;
					gap: 14px;
				}
				.wpb-btn-secondary {
					background: #ffffff;
					border: 1px solid #cbd5e1;
					color: #475569;
					padding: 7px 14px;
					border-radius: 6px;
					font-size: 13px;
					font-weight: 500;
					cursor: pointer;
					transition: all 0.15s ease;
					line-height: 1.4;
				}
				.wpb-btn-secondary:hover {
					background: #f1f5f9;
					color: #1e293b;
					border-color: #94a3b8;
				}
				.wpbot_just_deactivate {
					color: #64748b;
					font-size: 13px;
					text-decoration: underline;
					cursor: pointer;
					transition: color 0.15s ease;
				}
				.wpbot_just_deactivate:hover {
					color: #0f172a;
				}
				.wpb-btn-primary {
					background: #0284c7;
					border: 1px solid #0284c7;
					color: #ffffff;
					padding: 8px 16px;
					border-radius: 6px;
					font-size: 13px;
					font-weight: 600;
					cursor: pointer;
					transition: all 0.15s ease;
					box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
					line-height: 1.4;
				}
				.wpb-btn-primary:hover {
					background: #0369a1;
					border-color: #0369a1;
					color: #ffffff;
				}
				.wpb-btn-primary:disabled {
					opacity: 0.6;
					cursor: not-allowed;
				}
			</style>

			<script type="text/javascript">
				jQuery(document).ready(function($){
					var sld_pluginName = "<?php echo esc_js( $this->plugin_name ); ?>";
					var sld_modal = $("#wpb-goodbye-form-" + sld_pluginName);
					var sld_bgOverlay = $("#wpb-goodbye-form-bg-" + sld_pluginName);
					var sld_deactivationUrl = "";

					function sld_closeModal() {
						sld_modal.removeClass('is-open');
						sld_bgOverlay.stop(true, true).fadeOut(150);
						setTimeout(function(){
							sld_modal.css('display', 'none');
							$('body').removeClass('wpb-form-active');
						}, 200);
					}

					function sld_openModal(url) {
						sld_deactivationUrl = url;
						$('body').addClass('wpb-form-active');
						sld_bgOverlay.stop(true, true).fadeIn(150);
						sld_modal.css({'opacity': '0', 'display': 'block'});
						setTimeout(function(){
							sld_modal.addClass('is-open');
							sld_modal.css('opacity', '1');
						}, 20);
						sld_modal.find('#wpb-skip-deactivate').attr('href', sld_deactivationUrl);
					}

					// Intercept plugin row deactivation click (exclude modal's own skip/deactivate buttons)
					$(document).on('click', "#wpb-goodbye-link-" + sld_pluginName + ", a[href*='action=deactivate'][href*='" + sld_pluginName + "']:not(.wpbot_just_deactivate):not(#wpb-skip-deactivate)", function(e){
						e.preventDefault();
						e.stopPropagation();
						var href = $(this).attr('href');
						sld_openModal(href);
					});

					// Handle Skip / Just Deactivate click
					$(document).on('click', "#wpb-goodbye-form-" + sld_pluginName + " .wpbot_just_deactivate, #wpb-skip-deactivate", function(e){
						e.preventDefault();
						e.stopPropagation();
						var targetUrl = sld_deactivationUrl || $(this).attr('href');
						if (targetUrl && targetUrl !== '#') {
							window.location.href = targetUrl;
						}
					});

					// Close modal button & cancel button
					sld_modal.find('.wpb-goodbye-close-btn, .wpb-goodbye-cancel-btn').on('click', function(e){
						e.preventDefault();
						sld_closeModal();
					});

					// Background overlay click to close
					sld_bgOverlay.on('click', function(e){
						sld_closeModal();
					});

					// ESC key to close
					$(document).on('keyup', function(e){
						if (e.key === 'Escape' && $('body').hasClass('wpb-form-active')) {
							sld_closeModal();
						}
					});

					// Radio selection change
					sld_modal.find("input[name='wpb-goodbye-option-radio']").on('change', function(){
						sld_modal.find('.wpb-goodbye-option-item').removeClass('is-selected');
						$(this).closest('.wpb-goodbye-option-item').addClass('is-selected');

						var needDetails = $(this).data('need-details');
						var placeholder = $(this).data('placeholder');

						if (needDetails == '1' || needDetails === 1 || needDetails === true) {
							if (placeholder) {
								sld_modal.find('#wpb-goodbye-reasons').attr('placeholder', placeholder);
							}
							sld_modal.find('#wpb_additional_content').slideDown(200);
						} else {
							sld_modal.find('#wpb_additional_content').slideUp(200);
						}
						sld_modal.find('#wpbot_deactivation_error').hide();
					});

					// Submit & Deactivate
					sld_modal.find('#wpb-submit-form').on('click', function(e){
						e.preventDefault();

						var selectedRadio = sld_modal.find("input[name='wpb-goodbye-option-radio']:checked");
						var selectedVal = selectedRadio.val() || '';
						var details = sld_modal.find('#wpb-goodbye-reasons').val() || '';
						var email = sld_modal.find('#wpb-goodbye-email').val() || '';

						if (!selectedVal && !details) {
							sld_modal.find('#wpbot_deactivation_error').text('<?php echo esc_js( __( 'Please select an option or share your feedback.', 'simple-link-directory' ) ); ?>').slideDown(150);
							return;
						}

						// Transition to loading view
						sld_modal.find('.wpb-goodbye-form-body').hide();
						sld_modal.find('.wpb-goodbye-form-footer').hide();
						sld_modal.find('.deactivating-spinner').fadeIn(200);

						var data = {
							'action': 'goodbye_form',
							'values': selectedVal,
							'details': details,
							'email': email,
							'security': "<?php echo esc_js( wp_create_nonce( 'wpbot_goodbye_form' ) ); ?>"
						};

						$.post(ajaxurl, data).always(function(){
							if (sld_deactivationUrl) {
								window.location.href = sld_deactivationUrl;
							}
						});
					});
				});
			</script>
		<?php }
		
		/**
		 * AJAX callback when the form is submitted
		 * @since 1.0.0
		 */
		public function goodbye_form_callback() {
			check_ajax_referer( 'wpbot_goodbye_form', 'security' );
	
			if( isset( $_POST['values'] ) ) {
				$values = is_array( $_POST['values'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['values'] ) ) : sanitize_text_field( wp_unslash( $_POST['values'] ) );
				$reason = is_array( $values ) ? implode( ', ', $values ) : $values;
				update_option( 'wpbot_deactivation_reason_' . $this->plugin_name, $reason );
			}

			if( isset( $_POST['details'] ) ) {
				$details = sanitize_textarea_field( wp_unslash( $_POST['details'] ) );
				update_option( 'wpbot_deactivation_details_' . $this->plugin_name, $details );
			}

			if( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {
				$email = sanitize_email( wp_unslash( $_POST['email'] ) );
				update_option( 'wpbot_deactivation_email_' . $this->plugin_name, $email );
			}

			wp_send_json_success( array( 'message' => 'Feedback saved' ) );
		}
		
	}
	
}


