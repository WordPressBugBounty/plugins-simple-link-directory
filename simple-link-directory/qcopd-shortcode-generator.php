<?php

defined('ABSPATH') or die("No direct script access!");

/*TinyMCE Shortcode Generator Button - 25-01-2017*/
if ( ! function_exists( 'qcsld_tinymce_shortcode_button_function' ) ) {
	function qcsld_tinymce_shortcode_button_function() {
		add_filter ("mce_external_plugins", "qcsld_shortcode_generator_btn_js");
		add_filter ("mce_buttons", "qcsld_shortcode_generator_btn");
	}
}

if ( ! function_exists( 'qcsld_shortcode_generator_btn_js' ) ) {
	function qcsld_shortcode_generator_btn_js($plugin_array) {
		$plugin_array['qcsld_shortcode_btn'] = plugins_url('assets/js/qcsld-tinymce-button.js', __FILE__);
		return $plugin_array;
	}
}

if ( ! function_exists( 'qcsld_shortcode_generator_btn' ) ) {
	function qcsld_shortcode_generator_btn($buttons) {
		array_push ($buttons, 'qcsld_shortcode_btn');
		return $buttons;
	}
}

add_action ('init', 'qcsld_tinymce_shortcode_button_function');

if ( ! function_exists( 'qcsld_load_custom_wp_admin_style_free' ) ) {
	function qcsld_load_custom_wp_admin_style_free($hook) {
		if( 'post.php' == $hook || 'post-new.php' == $hook ){
	        wp_register_style( 'sld_shortcode_gerator_css', QCOPD_ASSETS_URL . '/css/shortcode-modal.css', false, '1.0.0' );
	        wp_enqueue_style( 'sld_shortcode_gerator_css' );
	    }
	}
}
add_action( 'admin_enqueue_scripts', 'qcsld_load_custom_wp_admin_style_free' );


if ( ! function_exists( 'qcsld_render_shortcode_modal_free' ) ) {
	function qcsld_render_shortcode_modal_free() {

		?>

		<div id="sm-modal" class="modal">

			<!-- Modal content -->
			<div class="modal-content">
			
				<span class="close">
					<span class="dashicons dashicons-no"></span>
				</span>
				<h3> 
					<?php esc_html_e( 'SLD - Shortcode Generator' , 'qc-opd' ); ?></h3>
				<hr/>
				
				<div class="sm_shortcode_list">

					<div class="qcsld_single_field_shortcode">
						<label class="qcopd_label_mode">
							<?php esc_html_e('Mode', 'qc-opd'); ?>
						</label>
						<select id="sld_mode">
							<option value="all"><?php esc_html_e('All List', 'qc-opd'); ?></option>
							<option value="one"><?php esc_html_e('One List', 'qc-opd'); ?></option>

						</select>
					</div>
					
					<div id="sld_list_div" class="qcsld_single_field_shortcode hidden-div">
						<label class="qcopd_select_list">
							<?php esc_html_e('Select List ', 'qc-opd'); ?>
						</label>
						<select id="sld_list_id">
						
							<option value=""><?php esc_html_e('Please Select List', 'qc-opd'); ?></option>
							
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
					
					<div id="sld_list_cat" class="qcsld_single_field_shortcode hidden-div">
						<label class="qcopd_list_category">
							<?php esc_html_e('List Category', 'qc-opd'); ?>
						</label>
						<select id="sld_list_cat_id">
						
							<option value=""><?php esc_html_e('Please Select Category', 'qc-opd'); ?></option>
							
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
					
					<div class="qcsld_single_field_shortcode">
						<label class="qcopd_template_style">
							<?php esc_html_e('Template Style', 'qc-opd'); ?>
						</label>
						<select id="sld_style">
							<option value="simple"><?php esc_html_e('Default Style', 'qc-opd'); ?></option>
							<option value="style-1"><?php esc_html_e('Style 01', 'qc-opd'); ?></option>
							<option value="style-2"><?php esc_html_e('Style 02', 'qc-opd'); ?></option>
							<option value="style-3"><?php esc_html_e('Style 03', 'qc-opd'); ?></option>
							<option value="style-4"><?php esc_html_e('Style 04', 'qc-opd'); ?></option>
							<option value="style-5"><?php esc_html_e('Style 05', 'qc-opd'); ?></option>
							<option value="style-16"><?php esc_html_e('Style 16 (**NEW)', 'qc-opd'); ?></option> 
						</select>
						
						<div id="demo-preview-link">
							<?php esc_html_e('Demo URL: ', 'qc-opd'); ?>
							<div id="demo-url">
								<a href="<?php echo esc_url('http://dev.quantumcloud.com/sld/'); ?>" target="_blank"><?php echo esc_url('http://dev.quantumcloud.com/sld/'); ?></a>
							</div>
						</div>
						
					</div>
					
					<div id="sld_column_div" class="qcsld_single_field_shortcode">
						<label class="qcopd_label_column">
							<?php esc_html_e('Column', 'qc-opd'); ?>
						</label>
						<select id="sld_column">
							<option value="1"><?php esc_html_e('Column 1', 'qc-opd'); ?></option>
							<option value="2"><?php esc_html_e('Column 2', 'qc-opd'); ?></option>
							<option value="3"><?php esc_html_e('Column 3', 'qc-opd'); ?></option>
							<option value="4"><?php esc_html_e('Column 4', 'qc-opd'); ?></option>
						</select>
					</div>
				
	                <div class="qcsld_single_field_shortcode">
	                    <label class="qcopd_title_font">
	                        <?php esc_html_e('Title Font Size', 'qc-opd'); ?>
	                    </label>
	                    <select id="sld_title_font_size">
	                        <option value=""><?php esc_html_e('Default', 'qc-opd'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>

	                <div class="qcsld_single_field_shortcode">
	                    <label class="qcopd_title_line">
	                        <?php esc_html_e('Title Line Height', 'qc-opd'); ?>
	                    </label>
	                    <select id="sld_title_line_height">
	                        <option value=""><?php esc_html_e('Default', 'qc-opd'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>

	                <div class="qcsld_single_field_shortcode">
	                    <label class="qcopd_subtitle_font">
	                        <?php esc_html_e('Subtitle Font Size', 'qc-opd'); ?>
	                    </label>
	                    <select id="sld_subtitle_font_size">
	                        <option value=""><?php esc_html_e('Default', 'qc-opd'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>



	                <div class="qcsld_single_field_shortcode">
	                    <label class="qcopd_subtitle_line">
	                        <?php esc_html_e('Subtitle Line Height', 'qc-opd'); ?>
	                    </label>
	                    <select id="sld_subtitle_line_height">
	                        <option value=""><?php esc_html_e('Default', 'qc-opd'); ?></option>
							<?php
							for($i=10;$i<50;$i++){
								echo '<option value="'.esc_attr($i."px").'">'.esc_html($i."px").'</option>';
							}
							?>
	                    </select>
	                </div>
					<div id="sld_orderby_div" class="qcsld_single_field_shortcode">
						<label class="qcopd_order_by">
							<?php esc_html_e('List Order By', 'qc-opd'); ?>
						</label>
						<select id="sld_orderby">
							<option value="date"><?php esc_html_e('Date', 'qc-opd'); ?></option>
							<option value="ID"><?php esc_html_e('ID', 'qc-opd'); ?></option>
							<option value="title"><?php esc_html_e('Title', 'qc-opd'); ?></option>
							<option value="modified"><?php esc_html_e('Date Modified', 'qc-opd'); ?></option>
							<option value="rand"><?php esc_html_e('Random', 'qc-opd'); ?></option>
							<option value="menu_order"><?php esc_html_e('Menu Order', 'qc-opd'); ?></option>
						</select>
					</div>
					
					<div id="sld_order_div" class="qcsld_single_field_shortcode">
						<label class="qcopd_order">
							<?php esc_html_e('List Order', 'qc-opd'); ?>
						</label>
						<select id="sld_order">
							<option value="ASC"><?php esc_html_e('Ascending', 'qc-opd'); ?></option>
							<option value="DESC"><?php esc_html_e('Descending', 'qc-opd'); ?></option>
						</select>
					</div>
					<div class="qcsld_single_field_shortcode">
						<label class="qcopd_item_orderby">
							<?php esc_html_e('List Item Orderby', 'qc-opd'); ?>
						</label>
						<select id="sld_itemorderby">
							<option value="menu_order"><?php esc_html_e('Menu Order', 'qc-opd'); ?></option>
							<option value="title"><?php esc_html_e('Title', 'qc-opd'); ?></option>
							<option value="upvotes"><?php esc_html_e('Upvotes', 'qc-opd'); ?></option>
							<option value="timestamp"><?php esc_html_e('Date Modified', 'qc-opd'); ?></option>
						</select>
					</div>
					<div class="qcsld_single_field_shortcode checkbox-sld">
						<label>
							<input class="sld_embeding" name="ckbox" value="true" type="checkbox">
							<?php esc_html_e('Enable Embeding', 'qc-opd'); ?>
						</label>
					</div>
					
					<div class="qcsld_single_field_shortcode">
						<label for="qcopd_generate_label">
						</label>
						<input class="sld-sc-btn" type="button" id="qcsld_add_shortcode" value="<?php echo esc_attr('Generate Shortcode'); ?>" />
					</div>
					
				</div>
				<div class="sld_shortcode_container" style="display:none;">
					<div class="qcsld_single_field_shortcode">
						<textarea id="sld_shortcode_container"></textarea>
						<p><b><?php esc_html_e('Copy', 'qc-opd'); ?></b> <?php esc_html_e('the shortcode & use it any text block.', 'qc-opd'); ?> <button class="sld_copy_close button button-primary button-small" ><?php esc_html_e('Copy & Close', 'qc-opd'); ?></button></p>
					</div>
				</div>
			</div>

		</div>
		<?php
		exit;
	}
}

add_action( 'wp_ajax_show_qcsld_shortcodes', 'qcsld_render_shortcode_modal_free');

if ( ! function_exists( 'qcsld_render_message_alert_modal_free' ) ) {
	function qcsld_render_message_alert_modal_free() {

		$screen = get_current_screen();
      	if( isset( $screen->post_type ) && ( $screen->post_type == 'sld' ) ){
		?>
		<div class="modal qcld_alert_msg_modal" style="display: none">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="sld_alert_msg_close">
					<span class="dashicons dashicons-no"></span>
				</span>
				<h3><?php esc_html_e( 'SLD - Alert' , 'qc-opd' ); ?></h3>
				
				<div class="qcld_alert_wrap">
					<div class="qcld_alert_msg">
						<p><?php esc_html_e( 'A list should have more than just 1 item.' , 'qc-opd' ); ?></p>
						<p> <?php esc_html_e( 'We recommend' , 'qc-opd' ); ?> <b><?php esc_html_e( '10-30' , 'qc-opd' ); ?></b> <?php esc_html_e( 'links/businesses for each List.' , 'qc-opd' ); ?> </p> 
						<p><?php esc_html_e( 'Create multiple Lists and show them all on a page using the' , 'qc-opd' ); ?> <a href="<?php echo esc_url( admin_url('edit.php?post_type=sld&page=sld_settings#help') ); ?>" target="_blank"><?php esc_html_e( 'Shortcode Generator.' , 'qc-opd' ); ?></a></p>
					</div>
				</div>
				<div class="qcld_alert_msg_footer">
					<button class="sld_alert_msg_close button button-primary button-small" ><?php esc_html_e('No', 'qc-opd'); ?></button> 
					<button class="sld_add_more_item button button-primary button-small" ><?php esc_html_e('Add more item', 'qc-opd'); ?></button>
				</div>
			</div>
		</div>
		<?php

		}
	}
}

add_action( 'admin_footer', 'qcsld_render_message_alert_modal_free');
