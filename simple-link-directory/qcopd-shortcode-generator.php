<?php

defined('ABSPATH') or die("No direct script access!");

/*TinyMCE Shortcode Generator Button - 25-01-2017*/
if ( ! function_exists( 'qcopd_sld_tinymce_shortcode_button_function' ) ) {
	function qcopd_sld_tinymce_shortcode_button_function() {
		add_filter ("mce_external_plugins", "qcopd_sld_shortcode_generator_btn_js");
		add_filter ("mce_buttons", "qcopd_sld_shortcode_generator_btn");
	}
}

if ( ! function_exists( 'qcopd_sld_shortcode_generator_btn_js' ) ) {
	function qcopd_sld_shortcode_generator_btn_js($plugin_array) {
		$plugin_array['qcopd_sld_shortcode_btn'] = plugins_url('assets/js/qcsld-tinymce-button.js', __FILE__);
		return $plugin_array;
	}
}

if ( ! function_exists( 'qcopd_sld_shortcode_generator_btn' ) ) {
	function qcopd_sld_shortcode_generator_btn($buttons) {
		array_push ($buttons, 'qcopd_sld_shortcode_btn');
		return $buttons;
	}
}

add_action ('init', 'qcopd_sld_tinymce_shortcode_button_function');

if ( ! function_exists( 'qcopd_sld_load_custom_wp_admin_style_free' ) ) {
	function qcopd_sld_load_custom_wp_admin_style_free($hook) {
		if( 'post.php' == $hook || 'post-new.php' == $hook ){
	        wp_register_style( 'sld_shortcode_gerator_css', QCOPD_ASSETS_URL . '/css/shortcode-modal.css', false, '1.0.0' );
	        wp_enqueue_style( 'sld_shortcode_gerator_css' );
	    }
	}
}
add_action( 'admin_enqueue_scripts', 'qcopd_sld_load_custom_wp_admin_style_free' );


if ( ! function_exists( 'qcopd_sld_render_shortcode_modal_free' ) ) {
	function qcopd_sld_render_shortcode_modal_free() {

		?>

		<div id="sm-modal" class="modal">

			<!-- Modal content -->
			<div class="modal-content">
			
				<span class="close">
					<span class="dashicons dashicons-no"></span>
				</span>
				<h3> 
					<?php esc_html_e( 'SLD - Shortcode Generator' , 'simple-link-directory' ); ?></h3>
				<hr/>
				
				<div class="sm_shortcode_list">

					<div class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_label_mode">
							<?php esc_html_e('Mode', 'simple-link-directory'); ?>
						</label>
						<select id="sld_mode">
							<option value="all"><?php esc_html_e('All List', 'simple-link-directory'); ?></option>
							<option value="one"><?php esc_html_e('One List', 'simple-link-directory'); ?></option>

						</select>
					</div>
					
					<div id="sld_list_div" class="qcopd_sld_single_field_shortcode hidden-div">
						<label class="qcopd_select_list">
							<?php esc_html_e('Select List ', 'simple-link-directory'); ?>
						</label>
						<select id="sld_list_id">
						
							<option value=""><?php esc_html_e('Please Select List', 'simple-link-directory'); ?></option>
							
							<?php
							
								$ilist = new WP_Query( array( 'post_type' => 'sld', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC') );
								if( $ilist->have_posts()){
									while( $ilist->have_posts() ){
										$ilist->the_post();
							?>
							
							<option value="<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html(get_the_title()); ?></option>
							
							<?php } } ?>
							
						</select>
					</div>
					
					<div id="sld_list_cat" class="qcopd_sld_single_field_shortcode hidden-div">
						<label class="qcopd_list_category">
							<?php esc_html_e('List Category', 'simple-link-directory'); ?>
						</label>
						<select id="sld_list_cat_id">
						
							<option value=""><?php esc_html_e('Please Select Category', 'simple-link-directory'); ?></option>
							
							<?php
							
								$terms = get_terms( 'sld_cat', array(
									'hide_empty' => true,
								) );
								if( $terms ){
									foreach( $terms as $term ){
							?>
							
							<option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
							
							<?php } } ?>
							
						</select>
					</div>
					
					<div class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_template_style">
							<?php esc_html_e('Template Style', 'simple-link-directory'); ?>
						</label>
						<select id="sld_style">
							<option value="simple"><?php esc_html_e('Default Style', 'simple-link-directory'); ?></option>
							<option value="style-1"><?php esc_html_e('Style 01', 'simple-link-directory'); ?></option>
							<option value="style-2"><?php esc_html_e('Style 02', 'simple-link-directory'); ?></option>
							<option value="style-3"><?php esc_html_e('Style 03', 'simple-link-directory'); ?></option>
							<option value="style-4"><?php esc_html_e('Style 04', 'simple-link-directory'); ?></option>
							<option value="style-5"><?php esc_html_e('Style 05', 'simple-link-directory'); ?></option>
							<option value="style-16"><?php esc_html_e('Style 16 (**NEW)', 'simple-link-directory'); ?></option> 
						</select>
						
						<div id="demo-preview-link">
							<?php esc_html_e('Demo URL: ', 'simple-link-directory'); ?>
							<div id="demo-url">
								<a href="<?php echo esc_url('http://dev.quantumcloud.com/sld/'); ?>" target="_blank"><?php echo esc_url('http://dev.quantumcloud.com/sld/'); ?></a>
							</div>
						</div>
						
					</div>
					
					<div id="sld_column_div" class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_label_column">
							<?php esc_html_e('Column', 'simple-link-directory'); ?>
						</label>
						<select id="sld_column">
							<option value="1"><?php esc_html_e('Column 1', 'simple-link-directory'); ?></option>
							<option value="2"><?php esc_html_e('Column 2', 'simple-link-directory'); ?></option>
							<option value="3"><?php esc_html_e('Column 3', 'simple-link-directory'); ?></option>
							<option value="4"><?php esc_html_e('Column 4', 'simple-link-directory'); ?></option>
						</select>
					</div>
				
	                <div class="qcopd_sld_single_field_shortcode">
	                    <label class="qcopd_title_font">
	                        <?php esc_html_e('Title Font Size', 'simple-link-directory'); ?>
	                    </label>
	                    <select id="sld_title_font_size">
	                        <option value=""><?php esc_html_e('Default', 'simple-link-directory'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>

	                <div class="qcopd_sld_single_field_shortcode">
	                    <label class="qcopd_title_line">
	                        <?php esc_html_e('Title Line Height', 'simple-link-directory'); ?>
	                    </label>
	                    <select id="sld_title_line_height">
	                        <option value=""><?php esc_html_e('Default', 'simple-link-directory'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>

	                <div class="qcopd_sld_single_field_shortcode">
	                    <label class="qcopd_subtitle_font">
	                        <?php esc_html_e('Subtitle Font Size', 'simple-link-directory'); ?>
	                    </label>
	                    <select id="sld_subtitle_font_size">
	                        <option value=""><?php esc_html_e('Default', 'simple-link-directory'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>



	                <div class="qcopd_sld_single_field_shortcode">
	                    <label class="qcopd_subtitle_line">
	                        <?php esc_html_e('Subtitle Line Height', 'simple-link-directory'); ?>
	                    </label>
	                    <select id="sld_subtitle_line_height">
	                        <option value=""><?php esc_html_e('Default', 'simple-link-directory'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>
					<div id="sld_orderby_div" class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_order_by">
							<?php esc_html_e('List Order By', 'simple-link-directory'); ?>
						</label>
						<select id="sld_orderby">
							<option value="date"><?php esc_html_e('Date', 'simple-link-directory'); ?></option>
							<option value="ID"><?php esc_html_e('ID', 'simple-link-directory'); ?></option>
							<option value="title"><?php esc_html_e('Title', 'simple-link-directory'); ?></option>
							<option value="modified"><?php esc_html_e('Date Modified', 'simple-link-directory'); ?></option>
							<option value="rand"><?php esc_html_e('Random', 'simple-link-directory'); ?></option>
							<option value="menu_order"><?php esc_html_e('Menu Order', 'simple-link-directory'); ?></option>
						</select>
					</div>
					
					<div id="sld_order_div" class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_order">
							<?php esc_html_e('List Order', 'simple-link-directory'); ?>
						</label>
						<select id="sld_order">
							<option value="ASC"><?php esc_html_e('Ascending', 'simple-link-directory'); ?></option>
							<option value="DESC"><?php esc_html_e('Descending', 'simple-link-directory'); ?></option>
						</select>
					</div>
					<div class="qcopd_sld_single_field_shortcode">
						<label class="qcopd_item_orderby">
							<?php esc_html_e('List Item Orderby', 'simple-link-directory'); ?>
						</label>
						<select id="sld_itemorderby">
							<option value="menu_order"><?php esc_html_e('Menu Order', 'simple-link-directory'); ?></option>
							<option value="title"><?php esc_html_e('Title', 'simple-link-directory'); ?></option>
							<option value="upvotes"><?php esc_html_e('Upvotes', 'simple-link-directory'); ?></option>
							<option value="timestamp"><?php esc_html_e('Date Modified', 'simple-link-directory'); ?></option>
						</select>
					</div>
					<div class="qcopd_sld_single_field_shortcode checkbox-sld">
						<label>
							<input class="sld_embeding" name="ckbox" value="true" type="checkbox">
							<?php esc_html_e('Enable Embeding', 'simple-link-directory'); ?>
						</label>
					</div>
					
					<div class="qcopd_sld_single_field_shortcode">
						<label for="qcopd_generate_label">
						</label>
						<input class="sld-sc-btn" type="button" id="qcopd_sld_add_shortcode" value="<?php echo esc_attr('Generate Shortcode'); ?>" />
					</div>
					
				</div>
				<div class="sld_shortcode_container" style="display:none;">
					<div class="qcopd_sld_single_field_shortcode">
						<textarea id="sld_shortcode_container"></textarea>
						<p><b><?php esc_html_e('Copy', 'simple-link-directory'); ?></b> <?php esc_html_e('the shortcode & use it any text block.', 'simple-link-directory'); ?> <button class="sld_copy_close button button-primary button-small" ><?php esc_html_e('Copy & Close', 'simple-link-directory'); ?></button></p>
					</div>
				</div>
			</div>

		</div>
		<?php
		exit;
	}
}

add_action( 'wp_ajax_show_qcopd_sld_shortcodes', 'qcopd_sld_render_shortcode_modal_free');

if ( ! function_exists( 'qcopd_sld_render_message_alert_modal_free' ) ) {
	function qcopd_sld_render_message_alert_modal_free() {

		$screen = get_current_screen();
      	if( isset( $screen->post_type ) && ( $screen->post_type == 'sld' ) ){
		?>
		<div class="modal qcld_alert_msg_modal" style="display: none">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="sld_alert_msg_close">
					<span class="dashicons dashicons-no"></span>
				</span>
				<h3><?php esc_html_e( 'SLD - Alert' , 'simple-link-directory' ); ?></h3>
				
				<div class="qcld_alert_wrap">
					<div class="qcld_alert_msg">
						<p><?php esc_html_e( 'A list should have more than just 1 item.' , 'simple-link-directory' ); ?></p>
						<p> <?php esc_html_e( 'We recommend' , 'simple-link-directory' ); ?> <b><?php esc_html_e( '10-30' , 'simple-link-directory' ); ?></b> <?php esc_html_e( 'links/businesses for each List.' , 'simple-link-directory' ); ?> </p> 
						<p><?php esc_html_e( 'Create multiple Lists and show them all on a page using the' , 'simple-link-directory' ); ?> <a href="<?php echo esc_url( admin_url('edit.php?post_type=sld&page=sld_settings#help') ); ?>" target="_blank"><?php esc_html_e( 'Shortcode Generator.' , 'simple-link-directory' ); ?></a></p>
					</div>
				</div>
				<div class="qcld_alert_msg_footer">
					<button class="sld_alert_msg_close button button-primary button-small" ><?php esc_html_e('No', 'simple-link-directory'); ?></button> 
					<button class="sld_add_more_item button button-primary button-small" ><?php esc_html_e('Add more item', 'simple-link-directory'); ?></button>
				</div>
			</div>
		</div>
		<?php

		}
	}
}

add_action( 'admin_footer', 'qcopd_sld_render_message_alert_modal_free');
