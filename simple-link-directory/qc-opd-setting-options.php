<?php

defined('ABSPATH') or die("No direct script access!");

//Setting options page
/*******************************
 * Callback function to add the menu
 *******************************/
function show_settngs_page_callback_func(){

	add_submenu_page(
		'edit.php?post_type=sld',
		esc_html__('Settings', 'qc-opd'),
		esc_html__('Settings', 'qc-opd'),
		'manage_options',
		'sld_settings',
		'qcsettings_page_callback_func'
	);
	add_action( 'admin_init', 'sld_register_plugin_settings' );
  
} //show_settings_page_callback_func
add_action( 'admin_menu', 'show_settngs_page_callback_func');

function sld_register_plugin_settings() {


    $args = array(
      'type' => 'string', 
      'sanitize_callback' => 'sanitize_text_field',
      'default' => NULL,
    );  

    $args_email = array(
      'type' => 'string', 
      'sanitize_callback' => 'sanitize_email',
      'default' => NULL,
    );  
  	//register our settings
  	//general Section
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_top_part', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_upvote', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_add_new_button', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_add_item_link', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_click_tracking', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_embed_credit_title', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_embed_credit_link', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_scroll_to_top', $args );
    register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_rtl', $args );
    register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_search', $args );
    register_setting( 'qc-sld-plugin-settings-group', 'sld_enable_dark_mode', $args );
  	//Language Settings
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_lan_add_link', $args );
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_lan_share_list', $args );
    register_setting( 'qc-sld-plugin-settings-group', 'sld_lan_live_search', $args );
    register_setting( 'qc-sld-plugin-settings-group', 'sld_no_results_found', $args );
    register_setting( 'qc-sbd-plugin-settings-group', 'sld_lan_enable_dark_mode', $args );
    register_setting( 'qc-sbd-plugin-settings-group', 'sld_lan_dark_mode_on', $args );
    register_setting( 'qc-sbd-plugin-settings-group', 'sld_lan_light_mode_on', $args );
  	//custom css section
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_custom_style', $args );
  	//custom js section
  	register_setting( 'qc-sld-plugin-settings-group', 'sld_custom_js', $args );
  	//help sectio
	
}

function qcsettings_page_callback_func(){
	
	?>

<div class="wrap swpm-admin-menu-wrap">
  <h1><?php esc_html_e('SLD Settings Page', 'qc-opd'); ?></h1>
  <h2 class="nav-tab-wrapper sld_nav_container"> 
  <a class="nav-tab sld_click_handle qcld_getting_started nav-tab-active" href="#getting_started"><?php esc_html_e('Getting Started', 'qc-opd'); ?></a> 
  <a class="nav-tab sld_click_handle " href="#general_settings"><?php esc_html_e('General Settings', 'qc-opd'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#language_settings"><?php esc_html_e('Language Settings', 'qc-opd'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#custom_css"><?php esc_html_e("Custom CSS", 'qc-opd'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#custom_js"><?php esc_html_e('Custom Javascript', 'qc-opd'); ?></a> 
  <a class="nav-tab sld_click_handle" href="#help"><?php esc_html_e('Shortcodes and Help', 'qc-opd'); ?></a> 
  </h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'qc-sld-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'qc-sld-plugin-settings-group' ); ?>
   
      <div id="getting_started" >
        <div class="sld-container"><div class="sld-row">
          <div class="is-dismissible sld-Getting-Started " style="display:none">
            <div class="sld_Started_carousel slick-slider">
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php esc_html_e('Step 1', 'qc-opd'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
              
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php esc_html_e('Create List with Link Details ', 'qc-opd'); ?></h3>
                       <?php esc_html_e('Go to New List and create one by giving it a name. Then simply start adding List items or Links by filling up the fields you want. Use the Add New button to add more Links to your list.', 'qc-opd'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/image.png" />
                    </div>
                  </div>
                </div>
              </div>
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php esc_html_e('Step 2', 'qc-opd'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php esc_html_e('Create More Lists', 'qc-opd'); ?> </h3>
                       <?php esc_html_e('You can just create a single list and use the Single List mode. But this plugin works the best when you create a few Lists each conatining about 15-20 Llinks. This yields the best view.', 'qc-opd'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/step2.png" />
                    </div>
                  </div>   
                </div>
              </div>          
            
              <div class="sld_info_item">
                <div class="serviceBox">
                  <div class="service-count"><?php esc_html_e('Step 3', 'qc-opd'); ?></div>
                  <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                  <div class="sldslider-Details">
                    <div class="description">
                      <h3><?php esc_html_e('Generate and Paste Shortcode on a Page', 'qc-opd'); ?></h3>
                      <?php esc_html_e('Go to the page or post you want to display the directory. On the right sidebar you will see a ShortCode Generator block. Generate a shortcode with the options you want. Copy paste that to a section on your page.', 'qc-opd'); ?>
                    </div>
                    <div class="Getting_Started_img">
                      <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/step3.png" />
                    </div>
                  </div>
                </div>          
              </div>   
            
            
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="general_settings" style="display:none" class="qcld-tabs-custom">
      
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Top Area', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_top_part" value="on" <?php echo (esc_attr( get_option('sld_enable_top_part') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('Top area includes Embed button (more options coming soon)', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Live Search', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_search" value="on" <?php echo (esc_attr( get_option('sld_enable_search') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('Live search through directory items.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Upvote', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_upvote" value="on" <?php echo (esc_attr( get_option('sld_enable_upvote') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('Turn ON to visible Upvote feature for all templates.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Add New Button', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_add_new_button" value="<?php echo esc_attr('on'); ?>" <?php echo (esc_attr( get_option('sld_add_new_button') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('The button will link to a page of your choice where you can place a contact form or instructions to submit links to your directory. Links have to be manually added by the admin.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Add Button Link', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_add_item_link" size="100" value="<?php echo esc_attr( get_option('sld_add_item_link') ); ?>"  />
            <i><?php esc_html_e('Example:  https://yourdomain.com/submit-link/', 'qc-opd'); ?></i>
            <p><?php esc_html_e('Paste the full URL of a page that contains a contact form to submit link ', 'qc-opd'); ?> ( <?php esc_html_e('Front end submission with monetization feature is available with the ', 'qc-opd'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank" rel="nofollow"><?php esc_html_e('Pro version', 'qc-opd'); ?></a> ) </p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Track Outbound Clicks', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_click_tracking" value="on" <?php echo (esc_attr( get_option('sld_enable_click_tracking') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('You need to have the analytics.js', 'qc-opd'); ?> [<a href="https://support.google.com/analytics/answer/1008080#GA" target="_blank"><?php esc_html_e('Analytics tracking code in every page of your site', 'qc-opd'); ?></a>].</i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Embed Credit Title', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_embed_credit_title" size="100" value="<?php echo esc_attr( get_option('sld_embed_credit_title') ); ?>"  />
            <i><?php esc_html_e('This text will be displayed below embedded list in other sites.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Embed Credit Link', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_embed_credit_link" size="100" value="<?php echo esc_attr( get_option('sld_embed_credit_link') ); ?>"  />
            <i><?php esc_html_e('This text will be displayed below embedded list in other sites.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Scroll to Top Button', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_scroll_to_top" value="on" <?php echo (esc_attr( get_option('sld_enable_scroll_to_top') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('Show Scroll to Top.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable RTL Direction', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_rtl" value="on" <?php echo (esc_attr( get_option('sld_enable_rtl') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('If you make this option ON, then list items will be arranged in Right-to-Left direction.', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Enable Dark Mode', 'qc-opd'); ?></th>
          <td><input type="checkbox" name="sld_enable_dark_mode" value="on" <?php echo (esc_attr( get_option('sld_enable_dark_mode') )=='on'?'checked="checked"':''); ?> />
            <i><?php esc_html_e('Enable this option to show Dark Mode for all themes', 'qc-opd'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="language_settings" style="display:none" class="qcld-tabs-custom">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Add New', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_add_link" size="100" value="<?php echo esc_attr( get_option('sld_lan_add_link') ); ?>"  />
            <i><?php esc_html_e('Change the language for Add New', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Share List', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_share_list" size="100" value="<?php echo esc_attr( get_option('sld_lan_share_list') ); ?>"  />
            <i><?php esc_html_e('Change the language for Share List', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Live Search Items', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_live_search" size="100" value="<?php echo esc_attr( get_option('sld_lan_live_search') ); ?>"  />
            <i><?php esc_html_e('Change the language for Live Search Items', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e('No Results Found for Your Search', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_no_results_found" size="100" value="<?php echo esc_attr( get_option('sld_no_results_found') ); ?>"  />
            <i><?php esc_html_e('Change the language for No Results Found for Your Search', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"> <?php esc_html_e('Enable Dark Mode', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_enable_dark_mode" size="100" value="<?php echo esc_attr( get_option('sld_lan_enable_dark_mode') ); ?>"  />
            <i> <?php esc_html_e('Change the language for - Enable Dark Mode', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"> <?php esc_html_e('Dark Mode On', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_dark_mode_on" size="100" value="<?php echo esc_attr( get_option('sld_lan_dark_mode_on') ); ?>"  />
            <i> <?php esc_html_e('Change the language for - Dark Mode On', 'qc-opd'); ?></i></td>
        </tr>
        <tr valign="top">
          <th scope="row"> <?php esc_html_e('Light Mode On', 'qc-opd'); ?></th>
          <td><input type="text" name="sld_lan_light_mode_on" size="100" value="<?php echo esc_attr( get_option('sld_lan_light_mode_on') ); ?>"  />
            <i> <?php esc_html_e('Change the language for - Light Mode On', 'qc-opd'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="custom_css" style="display:none" class="qcld-tabs-custom">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Custom CSS (Use *!important* flag if the changes does not take place)', 'qc-opd'); ?></th>
          <td><textarea name="sld_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option('sld_custom_style') ); ?></textarea>
            <i style="display:block;"><?php esc_html_e('Write your custom CSS here. Please do not use', 'qc-opd'); ?> <b><?php esc_html_e('style', 'qc-opd'); ?></b> <?php esc_html_e('tag in this textarea.', 'qc-opd'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="custom_js" style="display:none" class="qcld-tabs-custom">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php esc_html_e('Custom Javascript', 'qc-opd'); ?></th>
          <td><textarea name="sld_custom_js" rows="10" cols="100"><?php echo esc_attr( get_option('sld_custom_js') ); ?></textarea>
            <i style="display:block;"><?php esc_html_e('Write your custom JS here. Please do not use', 'qc-opd'); ?> <b><?php esc_html_e('script', 'qc-opd'); ?></b> <?php esc_html_e('tag in this textarea.', 'qc-opd'); ?></i></td>
        </tr>
      </table>
    </div>
    <div id="help" style="display:none" class="qcld-tabs-custom">
      <table class="form-table">
      <th class="Shortcodestitle" scope="row"><?php esc_html_e('Shortcodes and Help', 'qc-opd'); ?></th>
        <tr valign="top">
         
          <td>
         
          <div class="wrap">
              <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                  <div id="post-body-content" style="position: relative;"> 
                    

                    <div class="clear"></div>


                    <h3 class="qcld_short_genarator_scroll_wrap"><?php esc_html_e('Shortcode Generator', 'qc-opd'); ?></h3>
                    <p><?php esc_html_e('We encourage you to use the ShortCode generator found in the toolbar of your page/post editor in visual mode.', 'qc-opd'); ?></p>
                    <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/classic.jpg" alt="shortcode generator" />
                    <h3><?php esc_html_e('See sample below for where to find it for Gutenberg.', 'qc-opd'); ?></h3>
                    <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/gutenburg.jpg" alt="shortcode generator" /> <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/gutenburg2.jpg" alt="shortcode generator" />
                    <h3><?php esc_html_e('This is how the shortcode generator will look like.', 'qc-opd'); ?></h3>
                    <img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/shortcode-generator1.jpg" alt="shortcode generator" />
                    <div class="qcld-sld-shortcode-example">
                      <h3><?php esc_html_e('Shortcode Example', 'qc-opd'); ?></h3>
                      <p> <strong><?php esc_html_e('You can use our given SHORTCODE GENERATOR to generate and insert shortcode easily, titled as "SLD" with WordPress content editor.', 'qc-opd'); ?></strong> </p>
                      <p> <strong><u><?php esc_html_e('For all the lists:', 'qc-opd'); ?></u></strong> <br>
                        <span class="qcld-sld-code-highlight"><?php esc_html_e('[qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"]', 'qc-opd'); ?></span> </p>

                       <p> <strong><u><?php esc_html_e('For only a single list:', 'qc-opd'); ?></u></strong> <br>
                       <span class="qcld-sld-code-highlight"> <?php esc_html_e('[qcopd-directory mode="one" list_id="75"]', 'qc-opd'); ?></span> </p>
                        <p><strong><u><?php esc_html_e('Available Parameters:', 'qc-opd'); ?></u></strong> <br>
                      </p>
                      <p> <strong><?php esc_html_e('1. mode', 'qc-opd'); ?></strong> <br>
                    <span class="qcld-sld-code-highlight"><?php esc_html_e('[Value for this option can be set as "one" or "all".]', 'qc-opd'); ?></span> </p>
                      <p> <strong><?php esc_html_e('2. column', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Avaialble values: "1", "2", "3" or "4".', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('3. style', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Avaialble values: "simple", "style-1", "style-2", "style-3", "style-4", "style-5", "style-16".', 'qc-opd'); ?> <br>
                        <strong style="color: red; padding: 10px 10px; display: inline-block; border: 1px solid; border-radius: 6px; margin: 5px 0 5px 0;"> <?php esc_html_e('Only 6 templates are available in the free version. For more styles or templates, please purchase the', 'qc-opd'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank" target="_blank" rel="nofollow"><?php esc_html_e('premium version', 'qc-opd'); ?></a>. </strong> </p>
                      <p> <strong><?php esc_html_e('4. orderby', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e("Compatible order by values: 'ID', 'author', 'title', 'name', 'type', 'date', 'modified', 'rand' and 'menu_order'.", 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('5. order', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Value for this option can be set as "ASC" for Ascending or "DESC" for Descending order.', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('6. item_orderby', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Value for this option are "title", "upvotes", "timestamp" that will be set as "ASC" & others will be "DESC" order.', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('7. list_id', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Only applicable if you want to display a single list [not all]. You can provide specific list id here as a value. You can also get ready shortcode for a single list under "Manage List Items" menu.', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('8. enable_embedding', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Allow visitors to embed list in other sites. Supported values - "true", "false".', 'qc-opd'); ?> <br>
                        <?php esc_html_e('Example: enable_embedding="true"', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('8. upvote', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e('Allow visitors to list item. Supported values - "on", "off".', 'qc-opd'); ?> <br>
                        <?php esc_html_e('Example: upvote="on"', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('9. style-16 image show', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e(' Add the shortcode parameter enable_image="true" to show images with style-16', 'qc-opd'); ?> <br>
                        <?php esc_html_e('Example: enable_image="true"', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('10. Search', 'qc-opd'); ?></strong> <br>
                        <?php esc_html_e(' Add the shortcode parameter search="true" to show Live Search', 'qc-opd'); ?> <br>
                        <?php esc_html_e('Example: search="true"', 'qc-opd'); ?> </p>
                      <p> <strong><?php esc_html_e('11. dark_mode', 'qc-opd'); ?></strong> <br>
                      <?php esc_html_e('You can use this value to use the dark_mode of SLD templates. Available values for this option "on", "off".', 'qc-opd'); ?> </p>

                    </div>


                    <div class="clear"></div>
                    <h3> <?php esc_html_e('== Frequently Asked Questions ==', 'qc-opd'); ?></h3>
                    <div class="qcld_sld_tabs">
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_1" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_1"><?php esc_html_e('= I cannot save my List. delete item or add new items to the List =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('The issue you are having with saving the Lists is because of a limitation set in your server. Your server probably has a low limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save new link items. The server’s configuration that dictates this is max_input_vars. Set it to a high limit like max_input_vars = 10000. You can do it with local php.ini file or htaccess if your server supports it, Otherwise, please contact your hosting company support if needed. ', 'qc-opd'); ?></p>

                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_2" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_2"><?php esc_html_e('= The sub title is not showing =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php esc_html_e('The default template does not show subtitles. Use style-1 from the shortcode generator to display subtitles.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_3" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_3"> <?php esc_html_e('= Does the free version have filter buttons? =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('No. It is a pro version feature at the moment. But in the future, we have plans to make it available in the free version as well.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_4" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_4"><?php esc_html_e('= I cannot have more than 1 columns =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p> <?php esc_html_e('To display more than one column, you need to create multiple Lists and choose to Show All Lists from the shortcode generator. A single list will always show in a single column.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_5" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_5"> <?php esc_html_e('= I’m having trouble grasping the use of categories. It seems like and that they can only be assigned to a list rather than a specific link. =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('The base pillars of SLD are Lists, not individual links. The most common use case scenario of SLD is to create and display multiple Lists of many Links on specific topics. As such, there is no option for a Link (list item) to belong to multiple Lists or Categories. That would make the process of creating Lists slower. For each link you would have to select a List and a Category from drop downs despite the chances of a single List item to belong to multiple Lists are usually not that high. When you have dozens or hundreds of Lists that would become a real issue to create or manage your Lists.', 'qc-opd'); ?>
                          </p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_6" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_6"><?php esc_html_e('= Do you have pagination or load more items? I have thousands of links=', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('Items in lists can be paginated in the pro version. This option is available in the shortcode generator. But you can also add the parameters manually.', 'qc-opd'); ?></p>
                         
                          <p><?php esc_html_e('Values: “true”, “false”. This option will allow you to paginate list items. It will break the list page wise. Example: paginate_items=“true”. You also need to add the parameter per_page. This option indicates the number of items per page. Default is “5”. paginate_items=“true” is required to get this parameter working. Example: per_page=“5”', 'qc-opd'); ?></p>
                          
                          <p><?php esc_html_e('Lists themselves cannot be paginated as the main concept of SLD is to be a Simple, One Page Directory.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_7" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_7"><?php esc_html_e('= I have a list to import but it does not work and there is no message saying why my import does not work. =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('The most common reason for failed import is encoding. The CSV file itself and characters in it must be in utf-8 format. Please check your CSV file for any unusual/non-utf-8 characters. If the problem persists, please email us the CSV file.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_8" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_8"><?php esc_html_e('= I have some blank pages that are crawled by Google! How to avoid them? =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                          <p><?php esc_html_e('Like many, if not most, WordPress plugins SLD uses custom posts and WordPress creates slug URLs even though they are not being used by SLD at the moment. We are working on making use of them.', 'qc-opd'); ?></p>
                        <p><?php esc_html_e('But rest assured they are not harmful. They are generally not linked from anywhere and not indexed by Google. The only exception is if you have an XML sitemap generator that automatically scans and generates Links to these slug URLs. Yoast SEO plugin does that. You can exclude those slugs from xml sitemap. Go to Yoast->XML Sitemap->Post Types tag and select Manage List Items (sld) to Not in sitemap – then Save.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_9" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_9"><?php esc_html_e('= How can I upgrade from free version of SLD to Pro version? =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                           <p><?php esc_html_e('1. Download the latest pro version of the plugin from website', 'qc-opd'); ?></p>
                           <p><?php esc_html_e('2. Log in to your WordPress admin area and go to the Plugins management page.', 'qc-opd'); ?></p>
                           <p><?php esc_html_e('3. Deactivate and Delete the old version of the plugin (don’t worry – your data is safe)', 'qc-opd'); ?></p>
                           <p><?php esc_html_e('4. Upload and Activate the latest pro version of the plugin', 'qc-opd'); ?></p>
                           <p><?php esc_html_e('5. You are done.', 'qc-opd'); ?></p>
                      
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_10" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_10"><?php esc_html_e('= I have setup List Categories and Lists and neither is showing on home page. Did I install correctly? =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php esc_html_e('You have to put the short code on the WordPress oage or post page where you want to show the List/s. There is a Shortcode generator in your page or post visual editor. Use that to create shortcode and insert to your page, where you want to display the lists, easily.', 'qc-opd'); ?></p>
                        </div>
                      </div>
                      <div class="qcld_sld_tab">
                        <input type="radio" id="qcld_sld_tab_11" name="rd">
                        <label class="qcld_sld_tab-label" for="qcld_sld_tab_11"><?php esc_html_e('= Is SLD mobile friendly? Does it function well on cells and tablets? =', 'qc-opd'); ?></label>
                        <div class="qcld_sld_tab-content">
                         <p><?php esc_html_e('Yes, all templates are mobile device friendly and Responsive.', 'qc-opd'); ?></p>
                        </div>
                      </div>



                    </div>
                   <br><br>
                    <!-- <h3><?php esc_html_e('Please take a quick look at our', 'qc-opd'); ?> <a href="http://dev.quantumcloud.com/sld/tutorials/" class="button button-primary" target="_blank"><?php esc_html_e('Video Tutorials', 'qc-opd'); ?></a></h3> -->
                    <h3><?php esc_html_e('Note', 'qc-opd'); ?></h3>
                    <p><strong><?php esc_html_e('If you are having problem with adding more items or saving a list or your changes in the list are not getting saved then it is most likely because of a limitation set in your server. Your server has a limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save the List. The server’s configuration that dictates this is max_input_vars. You need to Set it to a high limit like max_input_vars = 15000. Since this is a server setting - you may need to contact your hosting company\'s support for this.', 'qc-opd'); ?></strong></p>
                    <div style="padding: 15px 10px; border: 1px solid #ccc; text-align: center; margin-top: 20px; background: #222; color: #fff;"> <?php esc_html_e('Crafted By:', 'qc-opd'); ?> <a href="<?php echo esc_url('http://www.quantumcloud.com'); ?>" target="_blank"><?php esc_html_e('Web Design Company', 'qc-opd'); ?></a> <?php esc_html_e('- QuantumCloud', 'qc-opd'); ?> </div>
                  </div>
                  <!-- /post-body-content --> 
                  
                </div>
                <!-- /post-body--> 
                
              </div>
              <!-- /poststuff --> 
              
            </div></td>
        </tr>
      </table>
    </div>
    <?php submit_button(); ?>
  </form>
</div>




<?php
	
}

