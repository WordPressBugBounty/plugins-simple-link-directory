<?php

defined('ABSPATH') or die("No direct script access!");

//Setting options page
/*******************************
 * Callback function to add the menu
 *******************************/
function qcopd_show_settngs_page_callback_func()
{

  add_submenu_page(
    'edit.php?post_type=sld',
    esc_html('Settings', 'simple-link-directory'),
    esc_html('Settings', 'simple-link-directory'),
    'manage_options',
    'sld_settings',
    'qcopd_settings_page_callback_func'
  );
  add_action('admin_init', 'qcopd_sld_register_plugin_settings');

} //show_settings_page_callback_func
add_action('admin_menu', 'qcopd_show_settngs_page_callback_func');

function qcopd_sld_register_plugin_settings()
{


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

  $args_textarea = array(
    'type' => 'string',
    'sanitize_callback' => 'sanitize_textarea_field',
    'default' => NULL,
  );
  //register our settings
  //general Section
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_top_part', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_upvote', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_add_new_button', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_add_item_link', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_click_tracking', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_embed_credit_title', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_embed_credit_link', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_scroll_to_top', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_rtl', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_search', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_dark_mode', $args);
  //Language Settings
  register_setting('qc-sld-plugin-settings-group', 'sld_lan_add_link', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_lan_share_list', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_lan_live_search', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_no_results_found', $args);
  register_setting('qc-sbd-plugin-settings-group', 'sld_lan_enable_dark_mode', $args);
  register_setting('qc-sbd-plugin-settings-group', 'sld_lan_dark_mode_on', $args);
  register_setting('qc-sbd-plugin-settings-group', 'sld_lan_light_mode_on', $args);
  //custom css section
  register_setting('qc-sld-plugin-settings-group', 'sld_custom_style', $args_textarea);
  //custom js section
  register_setting('qc-sld-plugin-settings-group', 'sld_custom_js', $args_textarea);
  //AI settings section
  register_setting('qc-sld-plugin-settings-group', 'sld_enable_ai_provider', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_ai_auto_save', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openai_api_key', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openai_model', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openai_manual_models', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openai_temperature', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_gemini_api_key', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_gemini_model', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_gemini_manual_models', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_gemini_temperature', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openrouter_api_key', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openrouter_model', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openrouter_manual_models', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_openrouter_temperature', $args);
  register_setting('qc-sld-plugin-settings-group', 'sld_ai_prompt_instruction', $args_textarea);
  //help section

}

function qcopd_settings_page_callback_func()
{

  ?>

  <div class="wrap swpm-admin-menu-wrap  sld-dashboard-wrap">


    <div class="sld-row">
      <div class="sld-col-md-9">
        <div class="sld-col-bg-color">
          <h3><?php esc_html_e('SLD Settings Page', 'simple-link-directory'); ?></h3>
          <div class="nav-tab-wrapper sld_nav_container">
            <a class="nav-tab sld_click_handle qcld_getting_started nav-tab-active"
              href="#getting_started"><?php esc_html_e('Getting Started', 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle "
              href="#general_settings"><?php esc_html_e('General Settings', 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle" href="#ai_settings"><?php esc_html_e('AI Settings', 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle"
              href="#language_settings"><?php esc_html_e('Language Settings', 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle" href="#custom_css"><?php esc_html_e("Custom CSS", 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle" href="#custom_js"><?php esc_html_e('Custom Javascript', 'simple-link-directory'); ?></a>
            <a class="nav-tab sld_click_handle" href="#help"><?php esc_html_e('Shortcodes and Help', 'simple-link-directory'); ?></a>
          </div>
          <form method="post" action="options.php">
            <?php settings_fields('qc-sld-plugin-settings-group'); ?>
            <?php do_settings_sections('qc-sld-plugin-settings-group'); ?>

            <div id="getting_started">
              <div class="sld-container">
                <div class="sld-row">
                  <div class="is-dismissible sld-Getting-Started " style="display:none">
                    <div class="sld_Started_carousel slick-slider">

                      <div class="sld_info_item">
                        <div class="serviceBox">
                          <div class="service-count"><?php esc_html_e('Step 1', 'simple-link-directory'); ?></div>
                          <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>

                          <div class="sldslider-Details">
                            <div class="description">
                              <h3><?php esc_html_e('Create List with Link Details ', 'simple-link-directory'); ?></h3>
                              <?php esc_html_e('Go to New List and create one by giving it a name. Then simply start adding List items or Links by filling up the fields you want. Use the Add New button to add more Links to your list.', 'simple-link-directory'); ?>
                            </div>
                            <div class="Getting_Started_img">
                              <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/image.png" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="sld_info_item">
                        <div class="serviceBox">
                          <div class="service-count"><?php esc_html_e('Step 2', 'simple-link-directory'); ?></div>
                          <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                          <div class="sldslider-Details">
                            <div class="description">
                              <h3><?php esc_html_e('Create More Lists', 'simple-link-directory'); ?> </h3>
                              <?php esc_html_e('You can just create a single list and use the Single List mode. But this plugin works the best when you create a few Lists each conatining about 15-20 Llinks. This yields the best view.', 'simple-link-directory'); ?>
                            </div>
                            <div class="Getting_Started_img">
                              <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/step2.png" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="sld_info_item">
                        <div class="serviceBox">
                          <div class="service-count"><?php esc_html_e('Step 3', 'simple-link-directory'); ?></div>
                          <div class="service-icon"><span><i class="fa fa-thumbs-up"></i></span></div>
                          <div class="sldslider-Details">
                            <div class="description">
                              <h3><?php esc_html_e('Generate and Paste Shortcode on a Page', 'simple-link-directory'); ?></h3>
                              <?php esc_html_e('Go to the page or post you want to display the directory. On the right sidebar you will see a ShortCode Generator block. Generate a shortcode with the options you want. Copy paste that to a section on your page.', 'simple-link-directory'); ?>
                            </div>
                            <div class="Getting_Started_img">
                              <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/step3.png" />
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
                  <th scope="row"><?php esc_html_e('Enable Top Area', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_top_part" value="on" <?php echo (esc_attr(get_option('sld_enable_top_part')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('Top area includes Embed button (more options coming soon)', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable Live Search', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_search" value="on" <?php echo (esc_attr(get_option('sld_enable_search')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('Live search through directory items.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable Upvote', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_upvote" value="on" <?php echo (esc_attr(get_option('sld_enable_upvote')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('Turn ON to visible Upvote feature for all templates.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable Add New Button', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_add_new_button" value="<?php echo esc_attr('on'); ?>" <?php echo (esc_attr(get_option('sld_add_new_button')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('The button will link to a page of your choice where you can place a contact form or instructions to submit links to your directory. Links have to be manually added by the admin.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Add Button Link', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_add_item_link" size="100"
                      value="<?php echo esc_attr(get_option('sld_add_item_link')); ?>" />
                    <i><?php esc_html_e('Example:  https://yourdomain.com/submit-link/', 'simple-link-directory'); ?></i>
                    <p>
                      <?php esc_html_e('Paste the full URL of a page that contains a contact form to submit link ', 'simple-link-directory'); ?>
                      (
                      <?php esc_html_e('Front end submission with monetization feature is available with the ', 'simple-link-directory'); ?>
                      <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>"
                        target="_blank" rel="nofollow"><?php esc_html_e('Pro version', 'simple-link-directory'); ?></a> )
                    </p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Track Outbound Clicks', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_click_tracking" value="on" <?php echo (esc_attr(get_option('sld_enable_click_tracking')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('You need to have the analytics.js', 'simple-link-directory'); ?> [<a
                        href="https://support.google.com/analytics/answer/1008080#GA"
                        target="_blank"><?php esc_html_e('Analytics tracking code in every page of your site', 'simple-link-directory'); ?></a>].</i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Embed Credit Title', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_embed_credit_title" size="100"
                      value="<?php echo esc_attr(get_option('sld_embed_credit_title')); ?>" />
                    <i><?php esc_html_e('This text will be displayed below embedded list in other sites.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Embed Credit Link', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_embed_credit_link" size="100"
                      value="<?php echo esc_attr(get_option('sld_embed_credit_link')); ?>" />
                    <i><?php esc_html_e('This text will be displayed below embedded list in other sites.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable Scroll to Top Button', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_scroll_to_top" value="on" <?php echo (esc_attr(get_option('sld_enable_scroll_to_top')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('Show Scroll to Top.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable RTL Direction', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_rtl" value="on" <?php echo (esc_attr(get_option('sld_enable_rtl')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('If you make this option ON, then list items will be arranged in Right-to-Left direction.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Enable Dark Mode', 'simple-link-directory'); ?></th>
                  <td><input type="checkbox" name="sld_enable_dark_mode" value="on" <?php echo (esc_attr(get_option('sld_enable_dark_mode')) == 'on' ? 'checked="checked"' : ''); ?> />
                    <i><?php esc_html_e('Enable this option to show Dark Mode for all themes', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
              </table>
            </div>
            <div id="language_settings" style="display:none" class="qcld-tabs-custom">
              <table class="form-table">
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Add New', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_add_link" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_add_link')); ?>" />
                    <i><?php esc_html_e('Change the language for Add New', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Share List', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_share_list" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_share_list')); ?>" />
                    <i><?php esc_html_e('Change the language for Share List', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Live Search Items', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_live_search" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_live_search')); ?>" />
                    <i><?php esc_html_e('Change the language for Live Search Items', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('No Results Found for Your Search', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_no_results_found" size="100"
                      value="<?php echo esc_attr(get_option('sld_no_results_found')); ?>" />
                    <i><?php esc_html_e('Change the language for No Results Found for Your Search', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"> <?php esc_html_e('Enable Dark Mode', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_enable_dark_mode" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_enable_dark_mode')); ?>" />
                    <i> <?php esc_html_e('Change the language for - Enable Dark Mode', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"> <?php esc_html_e('Dark Mode On', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_dark_mode_on" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_dark_mode_on')); ?>" />
                    <i> <?php esc_html_e('Change the language for - Dark Mode On', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"> <?php esc_html_e('Light Mode On', 'simple-link-directory'); ?></th>
                  <td><input type="text" name="sld_lan_light_mode_on" size="100"
                      value="<?php echo esc_attr(get_option('sld_lan_light_mode_on')); ?>" />
                    <i> <?php esc_html_e('Change the language for - Light Mode On', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
              </table>
            </div>
            <div id="custom_css" style="display:none" class="qcld-tabs-custom">
              <table class="form-table">
                <tr valign="top">
                  <th scope="row">
                    <?php esc_html_e('Custom CSS (Use *!important* flag if the changes does not take place)', 'simple-link-directory'); ?>
                  </th>
                  <td><textarea id="sld_custom_style" name="sld_custom_style" rows="10"
                      cols="100"><?php echo esc_attr(get_option('sld_custom_style')); ?></textarea>
                    <i style="display:block;"><?php esc_html_e('Write your custom CSS here. Please do not use', 'simple-link-directory'); ?>
                      <b><?php esc_html_e('style', 'simple-link-directory'); ?></b>
                      <?php esc_html_e('tag in this textarea.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
              </table>
            </div>
            <div id="custom_js" style="display:none" class="qcld-tabs-custom">
              <table class="form-table">
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Custom Javascript', 'simple-link-directory'); ?></th>
                  <td><textarea id="sld_custom_js" name="sld_custom_js" rows="10"
                      cols="100"><?php echo esc_attr(get_option('sld_custom_js')); ?></textarea>
                    <i style="display:block;"><?php esc_html_e('Write your custom JS here. Please do not use', 'simple-link-directory'); ?>
                      <b><?php esc_html_e('script', 'simple-link-directory'); ?></b>
                      <?php esc_html_e('tag in this textarea.', 'simple-link-directory'); ?></i>
                  </td>
                </tr>
              </table>
            </div>
            <div id="ai_settings" style="display:none" class="qcld-tabs-custom">
              <table class="form-table">
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Select Active AI Provider', 'simple-link-directory'); ?></th>
                  <td>
                    <?php $selected_provider = get_option('sld_enable_ai_provider', 'none'); ?>
                    <div class="sld-ai-provider-toggle-group">
                      <label class="sld-ai-provider-card <?php echo ($selected_provider === 'none') ? 'active' : ''; ?>">
                        <input type="radio" name="sld_enable_ai_provider" value="none" <?php checked($selected_provider, 'none'); ?> />
                        <span class="sld-ai-provider-icon">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m4.93 4.93 14.14 14.14" />
                          </svg>
                        </span>
                        <span class="sld-ai-provider-title"><?php esc_html_e('Disable AI', 'simple-link-directory'); ?></span>
                      </label>
                      <label
                        class="sld-ai-provider-card <?php echo ($selected_provider === 'openai') ? 'active' : ''; ?>">
                        <input type="radio" name="sld_enable_ai_provider" value="openai" <?php checked($selected_provider, 'openai'); ?> />
                        <span class="sld-ai-provider-icon">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M21.74 11.45a3.83 3.83 0 0 0-.25-1.92 4 4 0 0 0-1.12-1.46 3.73 3.73 0 0 0-.58-.4l.26-.45a3.78 3.78 0 0 0-.5-4.8 3.85 3.85 0 0 0-4.82-.48l-.45.26a3.83 3.83 0 0 0-1.85-.66 3.89 3.89 0 0 0-4.04 2.37l-.27.46a3.85 3.85 0 0 0-3.32.92 3.78 3.78 0 0 0-.5 4.8l.26.45A3.83 3.83 0 0 0 4 13a3.89 3.89 0 0 0 2 3.42l-.26.46a3.79 3.79 0 0 0 .5 4.8 3.85 3.85 0 0 0 4.82.48l.45-.26a3.83 3.83 0 0 0 1.85.66c1.93.07 3.63-1.16 4.04-2.37l.27-.46a3.85 3.85 0 0 0 3.32-.92 3.78 3.78 0 0 0 .5-4.8l-.26-.45a3.81 3.81 0 0 0 .85-2.07zm-7.79 8.2l-3.37-1.94a.5.5 0 0 1-.25-.43v-4.72l1.62.93a.5.5 0 0 0 .75-.43v-2.04l3.18 1.84a.5.5 0 0 1 .25.43v3.91a2.82 2.82 0 0 1-2.18 2.48zm-5.73-2.6a2.85 2.85 0 0 1-1.07-3.08l1.62-.94a.5.5 0 0 0 .25-.43v-4.08L12 10.45v3.68a.5.5 0 0 1-.25.43zm-1.63-7.53a2.81 2.81 0 0 1 3.25-.6l3.37 1.94a.5.5 0 0 1 .25.43v1.89L13.1 11.2a.5.5 0 0 0-.75.43v2.04l-3.18-1.84a.5.5 0 0 1-.25-.43zm12.39.9l-1.62.94a.5.5 0 0 0-.25.43v4.08l-3.06-1.77v-3.68a.5.5 0 0 1 .25-.43l3.37-1.94a2.84 2.84 0 0 1 4.31 2.37zm-2.9-4.88a2.81 2.81 0 0 1-3.25.6l-3.37-1.94a.5.5 0 0 1-.25-.43v-1.89l1.62.93a.5.5 0 0 0 .75-.43v-2.04l3.18 1.84a.5.5 0 0 1 .25.43zM12 11.08l1.69-.97 1.69.97v1.95L13.69 14 12 13.03z" />
                          </svg>
                        </span>
                        <span class="sld-ai-provider-title"><?php esc_html_e('OpenAI', 'simple-link-directory'); ?></span>
                      </label>
                      <label
                        class="sld-ai-provider-card <?php echo ($selected_provider === 'gemini') ? 'active' : ''; ?>">
                        <input type="radio" name="sld_enable_ai_provider" value="gemini" <?php checked($selected_provider, 'gemini'); ?> />
                        <span class="sld-ai-provider-icon">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                              d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                          </svg>
                        </span>
                        <span class="sld-ai-provider-title"><?php esc_html_e('Google Gemini', 'simple-link-directory'); ?></span>
                      </label>
                      <label
                        class="sld-ai-provider-card <?php echo ($selected_provider === 'openrouter') ? 'active' : ''; ?>">
                        <input type="radio" name="sld_enable_ai_provider" value="openrouter" <?php checked($selected_provider, 'openrouter'); ?> />
                        <span class="sld-ai-provider-icon">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                              d="M9 17h6M9 12h6M9 7h6M4 21h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                          </svg>
                        </span>
                        <span class="sld-ai-provider-title"><?php esc_html_e('OpenRouter', 'simple-link-directory'); ?></span>
                      </label>
                    </div>
                    <p class="description" style="margin-top: 10px;">
                      <?php esc_html_e('Choose which AI provider to enable for the directory features. Selecting "Disable AI" turns off all AI functionalities.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php esc_html_e('Auto Save Generated Items', 'simple-link-directory'); ?></th>
                  <td>
                    <?php $auto_save = get_option('sld_ai_auto_save', '0'); ?>
                    <label class="sld-switch">
                      <input type="checkbox" name="sld_ai_auto_save" value="1" <?php checked($auto_save, '1'); ?> />
                      <span class="sld-slider round"></span>
                    </label>
                    <p class="description">
                      <?php esc_html_e('If enabled, directory posts will be automatically saved/updated immediately after the elements are successfully generated by the AI.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openai-settings-row">
                  <th scope="row" colspan="2"
                    style="background: #f1f5f9; padding: 10px 15px; font-weight: bold; font-size: 16px; border-radius: 8px;">
                    <?php esc_html_e('OpenAI Integration Settings', 'simple-link-directory'); ?>
                  </th>
                </tr>
                <tr valign="top" class="sld-openai-settings-row">
                  <th scope="row"><?php esc_html_e('OpenAI API Key', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="password" name="sld_openai_api_key" id="sld_openai_api_key" size="80"
                      value="<?php echo esc_attr(get_option('sld_openai_api_key')); ?>"
                      placeholder="<?php esc_attr_e('sk-...', 'simple-link-directory'); ?>" style="vertical-align: middle;" />
                    <button type="button" class="button button-secondary" id="sld_test_openai_btn"
                      style="vertical-align: middle; margin-left: 5px;"><?php esc_html_e('Test Connection', 'simple-link-directory'); ?></button>
                    <span id="sld_openai_test_status"
                      style="margin-left: 10px; font-weight: bold; vertical-align: middle;"></span>
                    <p class="description">
                      <?php esc_html_e('Enter your OpenAI API key for AI generation features.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openai-settings-row">
                  <th scope="row"><?php esc_html_e('OpenAI Model', 'simple-link-directory'); ?></th>
                  <td>
                    <select name="sld_openai_model" id="sld_openai_model">
                      <?php
                      $selected_model = get_option('sld_openai_model', 'gpt-4o');
                      $models = qcopd_sld_get_openai_models();

                      $manual_models = get_option('sld_openai_manual_models');
                      if (!empty($manual_models)) {
                        $manual_models_arr = explode(',', $manual_models);
                        foreach($manual_models_arr as $manual_model) {
                          $m = trim($manual_model);
                          if (!empty($m)) {
                            $models[$m] = $m;
                          }
                        }
                      }

                      if (!empty($selected_model) && !array_key_exists($selected_model, $models)) {
                        $models[$selected_model] = $selected_model;
                      }
                      foreach ($models as $val => $label) {
                        echo '<option value="' . esc_attr($val) . '" ' . selected($selected_model, $val, false) . '>' . esc_html($label) . '</option>';
                      }
                      ?>
                    </select>
                    <p class="description">
                      <?php esc_html_e('Select the OpenAI model to use. You can load all latest available models using the Test Connection button.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openai-settings-row">
                  <th scope="row"><?php esc_html_e('Add Custom Models', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="text" name="sld_openai_manual_models" id="sld_openai_manual_models" size="80"
                      value="<?php echo esc_attr(get_option('sld_openai_manual_models')); ?>" placeholder="gpt-4o-mini, gpt-3.5-turbo" />
                    <p class="description">
                      <?php esc_html_e('Manually add custom OpenAI models (comma separated).', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openai-settings-row">
                  <th scope="row"><?php esc_html_e('OpenAI Temperature', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="number" name="sld_openai_temperature" id="sld_openai_temperature" min="0" max="2"
                      step="0.1" value="<?php echo esc_attr(get_option('sld_openai_temperature', '1.0')); ?>" />
                    <p class="description">
                      <?php esc_html_e('Controls randomness: Higher values like 1.2 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. Range: 0.0 to 2.0 (Default: 1.0). Note: Temperature is ignored for reasoning models (o1/o3 series).', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>

                <tr valign="top" class="sld-gemini-settings-row">
                  <th scope="row" colspan="2"
                    style="background: #f1f5f9; padding: 10px 15px; font-weight: bold; font-size: 16px; border-radius: 8px; margin-top: 20px;">
                    <?php esc_html_e('Google Gemini Integration Settings', 'simple-link-directory'); ?>
                  </th>
                </tr>
                <tr valign="top" class="sld-gemini-settings-row">
                  <th scope="row"><?php esc_html_e('Gemini API Key', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="password" name="sld_gemini_api_key" id="sld_gemini_api_key" size="80"
                      value="<?php echo esc_attr(get_option('sld_gemini_api_key')); ?>" style="vertical-align: middle;" />
                    <button type="button" class="button button-secondary" id="sld_test_gemini_btn"
                      style="vertical-align: middle; margin-left: 5px;"><?php esc_html_e('Test Connection', 'simple-link-directory'); ?></button>
                    <span id="sld_gemini_test_status"
                      style="margin-left: 10px; font-weight: bold; vertical-align: middle;"></span>
                    <p class="description"><?php esc_html_e('Enter your Google Gemini API key.', 'simple-link-directory'); ?></p>
                  </td>
                </tr>
                <tr valign="top" class="sld-gemini-settings-row">
                  <th scope="row"><?php esc_html_e('Gemini Model', 'simple-link-directory'); ?></th>
                  <td>
                    <select name="sld_gemini_model" id="sld_gemini_model">
                      <?php
                      $selected_gemini = get_option('sld_gemini_model', 'gemini-1.5-flash');
                      $gemini_models = qcopd_sld_get_gemini_models();

                      $manual_models = get_option('sld_gemini_manual_models');
                      if (!empty($manual_models)) {
                        $manual_models_arr = explode(',', $manual_models);
                        foreach($manual_models_arr as $manual_model) {
                          $m = trim($manual_model);
                          if (!empty($m)) {
                            $gemini_models[$m] = $m;
                          }
                        }
                      }

                      if (!empty($selected_gemini) && !array_key_exists($selected_gemini, $gemini_models)) {
                        $gemini_models[$selected_gemini] = $selected_gemini;
                      }
                      foreach ($gemini_models as $val => $label) {
                        echo '<option value="' . esc_attr($val) . '" ' . selected($selected_gemini, $val, false) . '>' . esc_html($label) . '</option>';
                      }
                      ?>
                    </select>
                    <p class="description">
                      <?php esc_html_e('Select the Gemini model to use. You can load all latest available models using the Test Connection button.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-gemini-settings-row">
                  <th scope="row"><?php esc_html_e('Add Custom Models', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="text" name="sld_gemini_manual_models" id="sld_gemini_manual_models" size="80"
                      value="<?php echo esc_attr(get_option('sld_gemini_manual_models')); ?>" placeholder="gemini-1.5-pro, gemini-ultra" />
                    <p class="description">
                      <?php esc_html_e('Manually add custom Google Gemini models (comma separated).', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-gemini-settings-row">
                  <th scope="row"><?php esc_html_e('Gemini Temperature', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="number" name="sld_gemini_temperature" id="sld_gemini_temperature" min="0" max="2"
                      step="0.1" value="<?php echo esc_attr(get_option('sld_gemini_temperature', '1.0')); ?>" />
                    <p class="description">
                      <?php esc_html_e('Controls randomness: Higher values like 1.2 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. Range: 0.0 to 2.0 (Default: 1.0).', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openrouter-settings-row">
                  <th scope="row" colspan="2"
                    style="background: #f1f5f9; padding: 10px 15px; font-weight: bold; font-size: 16px; border-radius: 8px; margin-top: 20px;">
                    <?php esc_html_e('OpenRouter Integration Settings', 'simple-link-directory'); ?>
                  </th>
                </tr>
                <tr valign="top" class="sld-openrouter-settings-row">
                  <th scope="row"><?php esc_html_e('OpenRouter API Key', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="password" name="sld_openrouter_api_key" id="sld_openrouter_api_key" size="80"
                      value="<?php echo esc_attr(get_option('sld_openrouter_api_key')); ?>"
                      style="vertical-align: middle;" />
                    <button type="button" class="button button-secondary" id="sld_test_openrouter_btn"
                      style="vertical-align: middle; margin-left: 5px;"><?php esc_html_e('Test Connection', 'simple-link-directory'); ?></button>
                    <span id="sld_openrouter_test_status"
                      style="margin-left: 10px; font-weight: bold; vertical-align: middle;"></span>
                    <p class="description">
                      <?php esc_html_e('Enter your OpenRouter API Key. You can get one from openrouter.ai.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openrouter-settings-row">
                  <th scope="row"><?php esc_html_e('OpenRouter Model', 'simple-link-directory'); ?></th>
                  <td>
                    <select name="sld_openrouter_model" id="sld_openrouter_model">
                      <?php
                      $selected_openrouter = get_option('sld_openrouter_model', 'google/gemini-2.5-flash');
                      $openrouter_models = qcopd_sld_get_openrouter_models();

                      $manual_models = get_option('sld_openrouter_manual_models');
                      if (!empty($manual_models)) {
                        $manual_models_arr = explode(',', $manual_models);
                        foreach($manual_models_arr as $manual_model) {
                          $m = trim($manual_model);
                          if (!empty($m)) {
                            $openrouter_models[$m] = $m;
                          }
                        }
                      }

                      if (!array_key_exists($selected_openrouter, $openrouter_models)) {
                        $openrouter_models[$selected_openrouter] = $selected_openrouter;
                      }
                      foreach ($openrouter_models as $val => $label) {
                        echo '<option value="' . esc_attr($val) . '" ' . selected($selected_openrouter, $val, false) . '>' . esc_html($label) . '</option>';
                      }
                      ?>
                    </select>
                    <p class="description">
                      <?php esc_html_e('Select the OpenRouter model to use. Models are dynamically fetched if an API key is active.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openrouter-settings-row">
                  <th scope="row"><?php esc_html_e('Add Custom Models', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="text" name="sld_openrouter_manual_models" id="sld_openrouter_manual_models" size="80"
                      value="<?php echo esc_attr(get_option('sld_openrouter_manual_models')); ?>" placeholder="meta-llama/llama-3-8b-instruct" />
                    <p class="description">
                      <?php esc_html_e('Manually add custom OpenRouter models (comma separated).', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
                <tr valign="top" class="sld-openrouter-settings-row">
                  <th scope="row"><?php esc_html_e('OpenRouter Temperature', 'simple-link-directory'); ?></th>
                  <td>
                    <input type="number" name="sld_openrouter_temperature" id="sld_openrouter_temperature" min="0" max="2"
                      step="0.1" value="<?php echo esc_attr(get_option('sld_openrouter_temperature', '1.0')); ?>" />
                    <p class="description">
                      <?php esc_html_e('Controls randomness: Range 0.0 to 2.0 (Default: 1.0).', 'simple-link-directory'); ?></p>
                  </td>
                </tr>
                <tr valign="top" class="sld-ai-general-settings-row">
                  <th scope="row" colspan="2"
                    style="background: #f1f5f9; padding: 10px 15px; font-weight: bold; font-size: 16px; border-radius: 8px; margin-top: 20px;">
                    <?php esc_html_e('AI Prompt Settings', 'simple-link-directory'); ?>
                  </th>
                </tr>
                <tr valign="top" class="sld-ai-general-settings-row">
                  <th scope="row"><?php esc_html_e('Custom Prompt Instruction', 'simple-link-directory'); ?></th>
                  <td>
                    <?php
                    $default_prompt = "Generate a JSON list of exactly {count} items about '{prompt}'.\nThe output MUST be a valid JSON array of objects. Do not include any markdown format, backticks, or explanation.\nEach object must contain:\n1. 'title': The name of the website or resource.\n2. 'link': A valid URL of the resource starting with http:// or https://.\n3. 'subtitle': A brief description or subtitle (max 10-15 words).\n\nJSON Schema example:\n[\n  {\n    \"title\": \"Example Site\",\n    \"link\": \"https://example.com\",\n    \"subtitle\": \"This is a short description.\"\n  }\n]";
                    ?>
                    <textarea name="sld_ai_prompt_instruction" id="sld_ai_prompt_instruction" rows="12" cols="80"
                      style="width: 100%; max-width: 600px; font-family: monospace; font-size: 13px;"><?php echo esc_textarea(get_option('sld_ai_prompt_instruction', $default_prompt)); ?></textarea>
                    <div style="margin-top: 8px; margin-bottom: 10px;">
                      <button type="button" class="button button-secondary"
                        id="sld_reset_ai_prompt_btn"><?php esc_html_e('Reset to Default', 'simple-link-directory'); ?></button>
                      <span id="sld_reset_ai_prompt_status"
                        style="margin-left: 10px; font-weight: bold; vertical-align: middle;"></span>
                    </div>
                    <p class="description sld-alert-warning">
                      <?php esc_html_e('You can customize the prompt instructions sent to the AI model. Be sure to include the placeholders {count} and {prompt} so they can be replaced by the user\'s inputs dynamically. Also, keep the general format of the prompt as is. Do not modify if you are uncertain.', 'simple-link-directory'); ?>
                    </p>
                  </td>
                </tr>
              </table>
            </div>
            <div id="help" style="display:none" class="qcld-tabs-custom">
              <table class="form-table">
                <th class="Shortcodestitle" scope="row"><?php esc_html_e('Shortcodes and Help', 'simple-link-directory'); ?></th>
                <tr valign="top">

                  <td>

                    <div class="wrap">
                      <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                          <div id="post-body-content" style="position: relative;">


                            <div class="clear"></div>


                            <h3 class="qcld_short_genarator_scroll_wrap">
                              <?php esc_html_e('Shortcode Generator', 'simple-link-directory'); ?>
                            </h3>
                            <p>
                              <?php esc_html_e('We encourage you to use the ShortCode generator found in the toolbar of your page/post editor in visual mode.', 'simple-link-directory'); ?>
                            </p>
                            <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/classic.jpg" alt="shortcode generator" />
                            <h3><?php esc_html_e('See sample below for where to find it for Gutenberg.', 'simple-link-directory'); ?>
                            </h3>
                            <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/gutenburg.jpg" alt="shortcode generator" />
                            <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/gutenburg2.jpg" alt="shortcode generator" />
                            <h3><?php esc_html_e('This is how the shortcode generator will look like.', 'simple-link-directory'); ?></h3>
                            <img src="<?php echo esc_url(QCOPD_IMG_URL); ?>/shortcode-generator1.jpg"
                              alt="shortcode generator" />
                            <div class="qcld-sld-shortcode-example">
                              <h3><?php esc_html_e('Shortcode Example', 'simple-link-directory'); ?></h3>
                              <p>
                                <strong><?php esc_html_e('You can use our given SHORTCODE GENERATOR to generate and insert shortcode easily, titled as "SLD" with WordPress content editor.', 'simple-link-directory'); ?></strong>
                              </p>
                              <p> <strong><u><?php esc_html_e('For all the lists:', 'simple-link-directory'); ?></u></strong> <br>
                                <span
                                  class="qcld-sld-code-highlight"><?php esc_html_e('[qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"]', 'simple-link-directory'); ?></span>
                              </p>

                              <p> <strong><u><?php esc_html_e('For only a single list:', 'simple-link-directory'); ?></u></strong> <br>
                                <span class="qcld-sld-code-highlight">
                                  <?php esc_html_e('[qcopd-directory mode="one" list_id="75"]', 'simple-link-directory'); ?></span>
                              </p>
                              <p><strong><u><?php esc_html_e('Available Parameters:', 'simple-link-directory'); ?></u></strong> <br>
                              </p>
                              <p> <strong><?php esc_html_e('1. mode', 'simple-link-directory'); ?></strong> <br>
                                <span
                                  class="qcld-sld-code-highlight"><?php esc_html_e('[Value for this option can be set as "one" or "all".]', 'simple-link-directory'); ?></span>
                              </p>
                              <p> <strong><?php esc_html_e('2. column', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Avaialble values: "1", "2", "3" or "4".', 'simple-link-directory'); ?> </p>
                              <p> <strong><?php esc_html_e('3. style', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Avaialble values: "simple", "style-1", "style-2", "style-3", "style-4", "style-5", "style-16".', 'simple-link-directory'); ?>
                                <br>
                                <strong
                                  style="color: red; padding: 10px 10px; display: inline-block; border: 1px solid; border-radius: 6px; margin: 5px 0 5px 0;">
                                  <?php esc_html_e('Only 6 templates are available in the free version. For more styles or templates, please purchase the', 'simple-link-directory'); ?>
                                  <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>"
                                    target="_blank" target="_blank"
                                    rel="nofollow"><?php esc_html_e('premium version', 'simple-link-directory'); ?></a>. </strong>
                              </p>
                              <p> <strong><?php esc_html_e('4. orderby', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e("Compatible order by values: 'ID', 'author', 'title', 'name', 'type', 'date', 'modified', 'rand' and 'menu_order'.", 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('5. order', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Value for this option can be set as "ASC" for Ascending or "DESC" for Descending order.', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('6. item_orderby', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Value for this option are "title", "upvotes", "timestamp" that will be set as "ASC" & others will be "DESC" order.', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('7. list_id', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Only applicable if you want to display a single list [not all]. You can provide specific list id here as a value. You can also get ready shortcode for a single list under "Manage List Items" menu.', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('8. enable_embedding', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Allow visitors to embed list in other sites. Supported values - "true", "false".', 'simple-link-directory'); ?>
                                <br>
                                <?php esc_html_e('Example: enable_embedding="true"', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('8. upvote', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('Allow visitors to list item. Supported values - "on", "off".', 'simple-link-directory'); ?>
                                <br>
                                <?php esc_html_e('Example: upvote="on"', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('9. style-16 image show', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e(' Add the shortcode parameter enable_image="true" to show images with style-16', 'simple-link-directory'); ?>
                                <br>
                                <?php esc_html_e('Example: enable_image="true"', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('10. Search', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e(' Add the shortcode parameter search="true" to show Live Search', 'simple-link-directory'); ?>
                                <br>
                                <?php esc_html_e('Example: search="true"', 'simple-link-directory'); ?>
                              </p>
                              <p> <strong><?php esc_html_e('11. dark_mode', 'simple-link-directory'); ?></strong> <br>
                                <?php esc_html_e('You can use this value to use the dark_mode of SLD templates. Available values for this option "on", "off".', 'simple-link-directory'); ?>
                              </p>

                            </div>


                            <div class="clear"></div>
                            <h3> <?php esc_html_e('== Frequently Asked Questions ==', 'simple-link-directory'); ?></h3>
                            <div class="qcld_sld_tabs">
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_1" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_1"><?php esc_html_e('= I cannot save my List. delete item or add new items to the List =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('The issue you are having with saving the Lists is because of a limitation set in your server. Your server probably has a low limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save new link items. The server’s configuration that dictates this is max_input_vars. Set it to a high limit like max_input_vars = 10000. You can do it with local php.ini file or htaccess if your server supports it, Otherwise, please contact your hosting company support if needed. ', 'simple-link-directory'); ?>
                                  </p>

                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_2" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_2"><?php esc_html_e('= The sub title is not showing =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('The default template does not show subtitles. Use style-1 from the shortcode generator to display subtitles.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_3" name="rd">
                                <label class="qcld_sld_tab-label" for="qcld_sld_tab_3">
                                  <?php esc_html_e('= Does the free version have filter buttons? =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('No. It is a pro version feature at the moment. But in the future, we have plans to make it available in the free version as well.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_4" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_4"><?php esc_html_e('= I cannot have more than 1 columns =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('To display more than one column, you need to create multiple Lists and choose to Show All Lists from the shortcode generator. A single list will always show in a single column.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_5" name="rd">
                                <label class="qcld_sld_tab-label" for="qcld_sld_tab_5">
                                  <?php esc_html_e('= I’m having trouble grasping the use of categories. It seems like and that they can only be assigned to a list rather than a specific link. =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('The base pillars of SLD are Lists, not individual links. The most common use case scenario of SLD is to create and display multiple Lists of many Links on specific topics. As such, there is no option for a Link (list item) to belong to multiple Lists or Categories. That would make the process of creating Lists slower. For each link you would have to select a List and a Category from drop downs despite the chances of a single List item to belong to multiple Lists are usually not that high. When you have dozens or hundreds of Lists that would become a real issue to create or manage your Lists.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_6" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_6"><?php esc_html_e('= Do you have pagination or load more items? I have thousands of links=', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('Items in lists can be paginated in the pro version. This option is available in the shortcode generator. But you can also add the parameters manually.', 'simple-link-directory'); ?>
                                  </p>

                                  <p>
                                    <?php esc_html_e('Values: “true”, “false”. This option will allow you to paginate list items. It will break the list page wise. Example: paginate_items=“true”. You also need to add the parameter per_page. This option indicates the number of items per page. Default is “5”. paginate_items=“true” is required to get this parameter working. Example: per_page=“5”', 'simple-link-directory'); ?>
                                  </p>

                                  <p>
                                    <?php esc_html_e('Lists themselves cannot be paginated as the main concept of SLD is to be a Simple, One Page Directory.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_7" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_7"><?php esc_html_e('= I have a list to import but it does not work and there is no message saying why my import does not work. =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('The most common reason for failed import is encoding. The CSV file itself and characters in it must be in utf-8 format. Please check your CSV file for any unusual/non-utf-8 characters. If the problem persists, please email us the CSV file.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_8" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_8"><?php esc_html_e('= I have some blank pages that are crawled by Google! How to avoid them? =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('Like many, if not most, WordPress plugins SLD uses custom posts and WordPress creates slug URLs even though they are not being used by SLD at the moment. We are working on making use of them.', 'simple-link-directory'); ?>
                                  </p>
                                  <p>
                                    <?php esc_html_e('But rest assured they are not harmful. They are generally not linked from anywhere and not indexed by Google. The only exception is if you have an XML sitemap generator that automatically scans and generates Links to these slug URLs. Yoast SEO plugin does that. You can exclude those slugs from xml sitemap. Go to Yoast->XML Sitemap->Post Types tag and select Manage List Items (sld) to Not in sitemap – then Save.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_9" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_9"><?php esc_html_e('= How can I upgrade from free version of SLD to Pro version? =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('1. Download the latest pro version of the plugin from website', 'simple-link-directory'); ?>
                                  </p>
                                  <p>
                                    <?php esc_html_e('2. Log in to your WordPress admin area and go to the Plugins management page.', 'simple-link-directory'); ?>
                                  </p>
                                  <p>
                                    <?php esc_html_e('3. Deactivate and Delete the old version of the plugin (don’t worry – your data is safe)', 'simple-link-directory'); ?>
                                  </p>
                                  <p>
                                    <?php esc_html_e('4. Upload and Activate the latest pro version of the plugin', 'simple-link-directory'); ?>
                                  </p>
                                  <p><?php esc_html_e('5. You are done.', 'simple-link-directory'); ?></p>

                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_10" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_10"><?php esc_html_e('= I have setup List Categories and Lists and neither is showing on home page. Did I install correctly? =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('You have to put the short code on the WordPress oage or post page where you want to show the List/s. There is a Shortcode generator in your page or post visual editor. Use that to create shortcode and insert to your page, where you want to display the lists, easily.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>
                              <div class="qcld_sld_tab">
                                <input type="radio" id="qcld_sld_tab_11" name="rd">
                                <label class="qcld_sld_tab-label"
                                  for="qcld_sld_tab_11"><?php esc_html_e('= Is SLD mobile friendly? Does it function well on cells and tablets? =', 'simple-link-directory'); ?></label>
                                <div class="qcld_sld_tab-content">
                                  <p>
                                    <?php esc_html_e('Yes, all templates are mobile device friendly and Responsive.', 'simple-link-directory'); ?>
                                  </p>
                                </div>
                              </div>



                            </div>
                            <br><br>
                            <!-- <h3><?php esc_html_e('Please take a quick look at our', 'simple-link-directory'); ?> <a href="http://dev.quantumcloud.com/sld/tutorials/" class="button button-primary" target="_blank"><?php esc_html_e('Video Tutorials', 'simple-link-directory'); ?></a></h3> -->
                            <h3><?php esc_html_e('Note', 'simple-link-directory'); ?></h3>
                            <p>
                              <strong><?php esc_html_e('If you are having problem with adding more items or saving a list or your changes in the list are not getting saved then it is most likely because of a limitation set in your server. Your server has a limit for how many form fields it will process at a time. So, after you have added a certain number of links, the server refuses to save the List. The server’s configuration that dictates this is max_input_vars. You need to Set it to a high limit like max_input_vars = 15000. Since this is a server setting - you may need to contact your hosting company\'s support for this.', 'simple-link-directory'); ?></strong>
                            </p>
                            <div
                              style="padding: 15px 10px; border: 1px solid #ccc; text-align: center; margin-top: 20px; background: #222; color: #fff;">
                              <?php esc_html_e('Crafted By:', 'simple-link-directory'); ?> <a
                                href="<?php echo esc_url('http://www.quantumcloud.com'); ?>"
                                target="_blank"><?php esc_html_e('Web Design Company', 'simple-link-directory'); ?></a>
                              <?php esc_html_e('- QuantumCloud', 'simple-link-directory'); ?>
                            </div>
                          </div>
                          <!-- /post-body-content -->

                        </div>
                        <!-- /post-body-->

                      </div>
                      <!-- /poststuff -->

                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <?php submit_button(); ?>
          </form>
        </div>
      </div>


      <!-- Sidebar -->
      <?php qcopd_help_render_sidebar(); ?>
    </div>
  </div>




  <?php

}

/**
 * Get OpenAI Models list via curl or fallback/transient cache
 */
function qcopd_sld_get_openai_models()
{
  $api_key = get_option('sld_openai_api_key');
  if (empty($api_key)) {
    return array(
      'gpt-4o' => 'gpt-4o (Recommended)',
      'gpt-4o-mini' => 'gpt-4o-mini',
      'o1-mini' => 'o1-mini',
      'o1-preview' => 'o1-preview',
      'o3-mini' => 'o3-mini',
      'gpt-4-turbo' => 'gpt-4-turbo',
      'gpt-4' => 'gpt-4',
      'gpt-3.5-turbo' => 'gpt-3.5-turbo',
    );
  }

  $transient_key = 'sld_openai_models_list';
  $models = get_transient($transient_key);
  if (false !== $models) {
    return $models;
  }

  $url = 'https://api.openai.com/v1/models';
  $headers = array(
    'Authorization' => 'Bearer ' . $api_key,
  );
  $response = wp_remote_get($url, array(
    'headers' => $headers,
    'timeout' => 10,
  ));

  if (!is_wp_error($response)) {
    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['data'])) {
      $fetched_models = array();
      foreach ($data['data'] as $model) {
        $id = $model['id'];
        if (strpos($id, 'gpt') === 0 || strpos($id, 'o1') === 0 || strpos($id, 'o3') === 0) {
          $fetched_models[$id] = $id;
        }
      }
      if (!empty($fetched_models)) {
        asort($fetched_models);
        set_transient($transient_key, $fetched_models, DAY_IN_SECONDS);
        return $fetched_models;
      }
    }
  }

  return array(
    'gpt-4o' => 'gpt-4o (Recommended)',
    'gpt-4o-mini' => 'gpt-4o-mini',
    'o1-mini' => 'o1-mini',
    'o1-preview' => 'o1-preview',
    'o3-mini' => 'o3-mini',
    'gpt-4-turbo' => 'gpt-4-turbo',
    'gpt-4' => 'gpt-4',
    'gpt-3.5-turbo' => 'gpt-3.5-turbo',
  );
}

/**
 * Get Google Gemini Models list via curl or fallback/transient cache
 */
function qcopd_sld_get_gemini_models()
{
  $api_key = get_option('sld_gemini_api_key');
  if (empty($api_key)) {
    return array(
      'gemini-1.5-flash' => 'gemini-1.5-flash (Recommended)',
      'gemini-1.5-pro' => 'gemini-1.5-pro',
      'gemini-2.0-flash' => 'gemini-2.0-flash',
      'gemini-2.0-pro' => 'gemini-2.0-pro',
      'gemini-1.0-pro' => 'gemini-1.0-pro',
    );
  }

  $transient_key = 'sld_gemini_models_list';
  $models = get_transient($transient_key);
  if (false !== $models) {
    return $models;
  }

  $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $api_key;
  $response = wp_remote_get($url, array(
    'timeout' => 10,
  ));

  if (!is_wp_error($response)) {
    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['models'])) {
      $fetched_models = array();
      foreach ($data['models'] as $model) {
        $name = str_replace('models/', '', $model['name']);
        $fetched_models[$name] = $name;
      }
      if (!empty($fetched_models)) {
        asort($fetched_models);
        set_transient($transient_key, $fetched_models, DAY_IN_SECONDS);
        return $fetched_models;
      }
    }
  }

  return array(
    'gemini-1.5-flash' => 'gemini-1.5-flash (Recommended)',
    'gemini-1.5-pro' => 'gemini-1.5-pro',
    'gemini-2.0-flash' => 'gemini-2.0-flash',
    'gemini-2.0-pro' => 'gemini-2.0-pro',
    'gemini-1.0-pro' => 'gemini-1.0-pro',
  );
}

add_action('update_option_sld_openai_api_key', 'qcopd_sld_clear_openai_models_transient');
function qcopd_sld_clear_openai_models_transient()
{
  delete_transient('sld_openai_models_list');
}

add_action('update_option_sld_gemini_api_key', 'qcopd_sld_clear_gemini_models_transient');
function qcopd_sld_clear_gemini_models_transient()
{
  delete_transient('sld_gemini_models_list');
}

/**
 * Get OpenRouter Models list via curl or fallback/transient cache
 */
function qcopd_sld_get_openrouter_models()
{
  $api_key = get_option('sld_openrouter_api_key');
  $fallback_models = array(
    'google/gemini-2.5-flash' => 'Google: Gemini 2.5 Flash',
    'meta-llama/llama-3.3-70b-instruct' => 'Meta: Llama 3.3 70B Instruct',
    'deepseek/deepseek-chat' => 'DeepSeek: DeepSeek Chat',
    'anthropic/claude-3.5-sonnet' => 'Anthropic: Claude 3.5 Sonnet',
    'openai/gpt-4o-mini' => 'OpenAI: GPT-4o Mini',
    'openai/gpt-4o' => 'OpenAI: GPT-4o',
  );

  if (empty($api_key)) {
    return $fallback_models;
  }

  $transient_key = 'sld_openrouter_models_list';
  $models = get_transient($transient_key);
  if (false !== $models) {
    return $models;
  }

  $url = 'https://openrouter.ai/api/v1/models';
  $response = wp_remote_get($url, array(
    'timeout' => 12,
  ));

  if (!is_wp_error($response)) {
    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['data'])) {
      $fetched_models = array();
      foreach ($data['data'] as $model) {
        $id = $model['id'];
        $name = !empty($model['name']) ? $model['name'] : $id;
        $fetched_models[$id] = $name;
      }
      if (!empty($fetched_models)) {
        asort($fetched_models);
        set_transient($transient_key, $fetched_models, DAY_IN_SECONDS);
        return $fetched_models;
      }
    }
  }

  return $fallback_models;
}

add_action('update_option_sld_openrouter_api_key', 'qcopd_sld_clear_openrouter_models_transient');
function qcopd_sld_clear_openrouter_models_transient()
{
  delete_transient('sld_openrouter_models_list');
}


