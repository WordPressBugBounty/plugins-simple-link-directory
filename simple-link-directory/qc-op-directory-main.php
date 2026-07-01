<?php
/**
 * Plugin Name: Link Directory - Simple Link Directory
 * Plugin URI: https://wordpress.org/plugins/simple-link-directory
 * Description: Link Directory WordPress plugin to curate topic based link collections. Curate gorgeous Link Directory, Local Business Directory, Partners or Vendors Directory
 * Version: 9.0.9
 * Author: QuantumCloud
 * Author URI: https://www.quantumcloud.com/products/simple-link-directory/
 * Requires at least: 4.6
 * Tested up to: 7.0
 * Text Domain: simple-link-directory
 * Domain Path: /lang/
 * License: GPL2
 */

defined('ABSPATH') or die("No direct script access!");

// Abort execution if Pro version is active to prevent conflicts
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if ( is_plugin_active( 'qc-simple-link-directory/qc-op-directory-main.php' ) ) {
    return;
}

// Also abort if we are currently activating the Pro plugin
if ( isset($_REQUEST['action']) ) {
    if ( $_REQUEST['action'] == 'activate' && isset($_REQUEST['plugin']) && strpos($_REQUEST['plugin'], 'qc-simple-link-directory') !== false ) {
        return;
    }
    if ( $_REQUEST['action'] == 'activate-selected' && isset($_POST['checked']) && in_array('qc-simple-link-directory/qc-op-directory-main.php', $_POST['checked']) ) {
        return;
    }
}

//Custom Constants
if (!defined('QCOPD_URL')) {
    define('QCOPD_URL', plugin_dir_url(__FILE__));
}

if (!defined('QCOPD_IMG_URL')) {
    define('QCOPD_IMG_URL', QCOPD_URL . "assets/images");
}

if (!defined('QCOPD_ASSETS_URL')) {
    define('QCOPD_ASSETS_URL', QCOPD_URL . "assets");
}

if (!defined('QCOPD_DIR')) {
    define('QCOPD_DIR', dirname(__FILE__));
}

if (!defined('QCOPD_INC_DIR')) {
    define('QCOPD_INC_DIR', QCOPD_DIR . "/inc");
}

if (!defined('OCOPD_TPL_URL')) {
    define('OCOPD_TPL_URL', QCOPD_URL . "templates");
}

if (!defined('OCOPD_TPL_DIR')) {
    define('OCOPD_TPL_DIR', QCOPD_DIR . "templates");
}

// Define a constant for the CSV file path within the plugin directory
if (!defined('SLD_CSV_FILE_PATH')) {
    define('SLD_CSV_FILE_PATH', plugin_dir_path(__FILE__) . 'assets/file/sample-csv-file-demo.csv');
}

if (!function_exists('qcopd_sld_languages_function_callback')) {
    function qcopd_sld_languages_function_callback()
    {
        load_plugin_textdomain('simple-link-directory', false, dirname(plugin_basename(__FILE__)) . '/lang');
    }
}
add_action('init', 'qcopd_sld_languages_function_callback');

//Include files and scripts

require_once('qc-op-directory-post-type.php');
require_once('qc-op-directory-assets.php');
require_once('qc-op-directory-shortcodes.php');
require_once('embed/embedder.php');

require_once('qcopd-shortcode-generator.php');
require_once('qc-op-directory-import.php');
require_once('qc-opd-ajax-stuffs.php');

/*01-27-2026*/
require_once('qc-sld-import-demo-data.php');



/*05-31-2017*/
require_once('qc-support-promo-page/class-qc-support-promo-page.php');
require_once('qc-free-ai-chatbot/class-qc-free-ai-page.php');
require_once('class-qc-free-plugin-upgrade-notice.php');
/*05-31-2017 - Ends*/
/* Option page */
require_once('qc-opd-setting-options.php');

require_once('qc-rating-feature/qc-rating-class.php');
require_once('modules/addons/addons.php');


add_action('wp_head', 'qcopd_add_outbound_click_tracking_script');

function qcopd_add_outbound_click_tracking_script()
{


    if (!function_exists('wp_get_current_user')) {
        include(ABSPATH . "wp-includes/pluggable.php");
    }


    if (is_user_logged_in()) {

        $current_user = wp_get_current_user();
        if (in_array('administrator', $current_user->roles)) {
            return;
        }

    }

    $outbound_conf = get_option('sld_enable_click_tracking');

    if (isset($outbound_conf) && $outbound_conf == 'on') {
        wp_enqueue_script('sld-admin-trackoutbound-script');
    }
}

/*Add Promotional Link - Bue Pro - 12-30-2016*/
add_action('manage_posts_extra_tablenav', 'qcopd_promo_link_in_cpt_table');

function qcopd_promo_link_in_cpt_table()
{
    $screen = get_current_screen();

    $current_screen = $screen->id;

    $link = "";

    if ($current_screen == 'edit-sld') {
        $link = '<div class="alignleft actions"><a href="' . esc_url("https://www.quantumcloud.com/products/simple-link-directory/") . '" target="_blank" class="button qcsld-promo-link" rel="nofollow">' . esc_html("Upgrade to Pro", 'simple-link-directory') . '</a></div>';
        $link .= '<div class="alignleft actions"><a href="' . esc_url(admin_url('post-new.php?post_type=sld')) . '" class="button">' . esc_html("Add New List of Links", 'simple-link-directory') . '</a></div>';
    }

    echo wp_kses_post($link);

}

add_action('buypro_promotional_link', 'qcopd_promo_link_in_settings_page');

function qcopd_promo_link_in_settings_page()
{
    $screen = get_current_screen();

    $current_screen = $screen->id;

    $link = "";

    $link = '<div class="alignleft actions"><a href="' . esc_url("https://www.quantumcloud.com/products/simple-link-directory/") . '" target="_blank" class="button qcsld-promo-link" rel="nofollow">' . esc_html("Upgrade to Pro", 'simple-link-directory') . '</a></div>';

    echo wp_kses_post($link);

}

/**
 * Submenu filter function. Tested with Wordpress 4.1.1
 * Sort and order submenu positions to match your custom order.
 *
 * @author Hendrik Schuster <contact@deviantdev.com>
 */
function qcopd_order_index_catalog_menu_page($menu_ord)
{

    global $submenu;

    // Enable the next line to see a specific menu and it's order positions
    //echo '<pre>'; print_r( $submenu['edit.php?post_type=sld'] ); echo '</pre>'; exit();

    $arr = array();
    if (current_user_can('edit_posts')) {

        if (isset($submenu['edit.php?post_type=sld'][5]))
            $arr[] = $submenu['edit.php?post_type=sld'][5];

        if (isset($submenu['edit.php?post_type=sld'][10]))
            $arr[] = $submenu['edit.php?post_type=sld'][10];

        if (isset($submenu['edit.php?post_type=sld'][15]))
            $arr[] = $submenu['edit.php?post_type=sld'][15];

        if (isset($submenu['edit.php?post_type=sld'][16]))
            $arr[] = $submenu['edit.php?post_type=sld'][16];

        if (isset($submenu['edit.php?post_type=sld'][18]))
            $arr[] = $submenu['edit.php?post_type=sld'][18];

        if (isset($submenu['edit.php?post_type=sld'][17]))
            $arr[] = $submenu['edit.php?post_type=sld'][17];

        if (isset($submenu['edit.php?post_type=sld'][250]))
            $arr[] = $submenu['edit.php?post_type=sld'][250];

        if (isset($submenu['edit.php?post_type=sld'][302]))
            $arr[] = $submenu['edit.php?post_type=sld'][302];

        if (isset($submenu['edit.php?post_type=sld'][300]))
            $arr[] = $submenu['edit.php?post_type=sld'][300];

        if (isset($submenu['edit.php?post_type=sld'][301]))
            $arr[] = $submenu['edit.php?post_type=sld'][301];

    }
    $submenu['edit.php?post_type=sld'] = $arr;

    return $menu_ord;

}
add_filter('custom_menu_order', 'qcopd_order_index_catalog_menu_page');

add_action('admin_menu', 'qcopd_help_link_submenu', 20);
function qcopd_help_link_submenu()
{
    global $submenu;

    $link_text = esc_html("Shortcodes and Help", 'simple-link-directory');
    $submenu["edit.php?post_type=sld"][250] = array($link_text, 'activate_plugins', admin_url('edit.php?post_type=sld&page=sld_settings#help'));
    ksort($submenu["edit.php?post_type=sld"]);

    return ($submenu);
}


function qcopd_options_instructions_example()
{
    global $my_admin_page;
    $screen = get_current_screen();

    if (is_admin() && ($screen->post_type == 'sld')) {
        wp_enqueue_script('jqc-slick.min-js', QCOPD_ASSETS_URL . '/js/slick.min.js', array('jquery'));
        ?>
        <div class="notice notice-info is-dismissible sld-notice" style="display:none">
            <div class="sld_info_carousel">

                <div class="sld_info_item"><?php esc_html_e('**SLD Pro Tip: Did you know that you can', 'simple-link-directory'); ?> <strong
                        style="color: #E91E63"><?php esc_html_e('Auto Generate', 'simple-link-directory'); ?></strong>
                    <?php esc_html_e('Title, Subtitle & Thumbnail with the Pro Version in Just 2 Clicks?', 'simple-link-directory'); ?>
                    <strong style="color: #E91E63"><?php esc_html_e('Triple Your Link Entry Speed!', 'simple-link-directory'); ?></strong>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: Lists are the base pillars of SLD, not individual links. Group your links into different Lists for the best performance.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: SLD looks the best when you create multiple Lists and use the Show All Lists mode.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Pro Tip: Did you know that SLD Pro version lets you monetize your directory and earn', 'simple-link-directory'); ?>
                    <strong style="color: #E91E63"><?php esc_html_e('passive income?', 'simple-link-directory'); ?></strong>
                    <?php esc_html_e('Upgrade now!', 'simple-link-directory'); ?></div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: Try to keep the maximum number of links below 30 per list. Create multiple Lists as needed.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: Use the handy shortcode generator to make life easy. It is a small, blue [SLD] button found at the toolbar of any page\'s visual editor.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item"><?php esc_html_e('**SLD Pro Tip: You can display your', 'simple-link-directory'); ?> <strong
                        style="color: #E91E63"><?php esc_html_e('Lists by category', 'simple-link-directory'); ?>
                    </strong><?php esc_html_e('with the SLD pro version.', 'simple-link-directory'); ?> <strong
                        style="color: #E91E63"><?php esc_html_e('16+ Templates, Multi page mode', 'simple-link-directory'); ?></strong><?php esc_html_e(', Widgets are also available.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: You can create a page with a contact form and link the Add Link button to that page so people can submit links to your directory by email.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item">
                    <?php esc_html_e('**SLD Tip: If you are having problem with adding more items or saving a list then you may need to increase max_input_vars value in server. Check the help section for more details.', 'simple-link-directory'); ?>
                </div>

                <div class="sld_info_item"><?php esc_html_e('**SLD Pro Tip: SLD pro version has', 'simple-link-directory'); ?> <strong
                        style="color: #E91E63"><?php esc_html_e('front end dashboard', 'simple-link-directory'); ?></strong>
                    <?php esc_html_e('for user registration and link management. As well as tags and instant search.', 'simple-link-directory'); ?>
                    <strong style="color:#E91E63"><?php esc_html_e('Upgrade to the Pro version now!', 'simple-link-directory'); ?></strong>
                </div>

            </div>

        </div>
        <?php


    }
}

add_action('admin_notices', 'qcopd_options_instructions_example');

/*
 * This is for radium-importer plugin conflict issue.
 */



/**
 * Detect plugin. For use in Admin area only.
 */
// For removing conflict with Demo Data Impoter
class Radium_Theme_Demo_Data_Importer
{
    static $instance;
}





add_action('add_meta_boxes', 'qcopd_sld_meta_box_video');
function qcopd_sld_meta_box_video()
{					                  // --- Parameters: ---
    add_meta_box(
        'qc-sld-meta-box-id', // ID attribute of metabox
        esc_html('Shortcode Generator for SLD', 'simple-link-directory'),       // Title of metabox visible to user
        'qcopd_sld_meta_box_callback', // Function that prints box in wp-admin
        'page',              // Show box for posts, pages, custom, etc.
        'side',            // Where on the page to show the box
        'high'
    );            // Priority of box in display order

    add_meta_box(
        'sld-ai-generator-box',
        esc_html('AI List Items Generator', 'simple-link-directory'),
        'qcopd_sld_ai_generator_box_callback',
        'sld',
        'side',
        'high'
    );
}

function qcopd_sld_meta_box_callback($post)
{
    ?>
    <p>
        <label for="sh_meta_box_bg_effect">
            <p><?php esc_html_e('Click the button below to generate shortcode', 'simple-link-directory'); ?></p>
        </label>
        <input type="button" id="sld_shortcode_generator_meta" class="button button-primary button-large"
            value="<?php echo esc_attr('Generate Shortcode', 'simple-link-directory'); ?>" />
    </p>

    <?php
}

function qcopd_sld_ai_generator_box_callback($post)
{
    $active_provider = get_option('sld_enable_ai_provider', 'none');
    
    if ($active_provider === 'openai') {
        $provider_name = 'OpenAI';
    } elseif ($active_provider === 'gemini') {
        $provider_name = 'Google Gemini';
    } elseif ($active_provider === 'openrouter') {
        $provider_name = 'OpenRouter';
    } else {
        $provider_name = 'None';
    }
    ?>
    <div class="sld-ai-sidebar-generator-wrap">
        <input type="hidden" id="sld_ai_auto_save_val" value="<?php echo esc_attr(get_option('sld_ai_auto_save', '0')); ?>" />
        <?php if ($active_provider === 'none'): ?>
            <div class="notice notice-warning inline sld-ai-sidebar-notice"><p><?php printf(esc_html('Please enable an AI provider (OpenAI, Gemini or OpenRouter) in Simple Link Directory %s first.', 'simple-link-directory'), '<a href="' . esc_url(admin_url('edit.php?post_type=sld&page=sld_settings#ai_settings')) . '" target="_blank" style="text-decoration: underline;">' . esc_html('settings tab', 'simple-link-directory') . '</a>'); ?></p></div>
        <?php else: ?>
            <p class="sld-ai-sidebar-active-provider">
                <strong><?php esc_html_e('Active AI Provider:', 'simple-link-directory'); ?></strong> 
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=sld&page=sld_settings#ai_settings')); ?>" target="_blank" style="text-decoration: none; border-bottom: 1px dashed #4f46e5;">
                    <span class="sld-ai-sidebar-provider-badge"><?php echo esc_html($provider_name); ?></span>
                </a>
            </p>
            
            <p class="sld-ai-sidebar-field-wrap">
                <label for="sld_ai_prompt"><?php esc_html_e('Topic:', 'simple-link-directory'); ?></label>
                <textarea id="sld_ai_prompt" placeholder="<?php esc_attr_e('e.g., JavaScript coding', 'simple-link-directory'); ?>"></textarea>
            </p>
            
            <p class="sld-ai-sidebar-field-wrap">
                <label for="sld_ai_count"><?php esc_html_e('Number of Items:', 'simple-link-directory'); ?></label>
                <select id="sld_ai_count">
                    <?php for($i=1; $i<=15; $i++): ?>
                        <option value="<?php echo esc_attr($i); ?>" <?php selected($i, 5); ?>><?php echo esc_attr($i); ?></option>
                    <?php endfor; ?>
                </select>
            </p>
            
            <p class="sld-ai-sidebar-btn-wrap">
                <button type="button" id="sld_btn_ai_generate" class="button button-primary button-large">
                    <span class="dashicons dashicons-admin-generic sld-spinner-icon"></span>
                    <span class="sld-btn-text"><?php esc_html_e('Generate & Add Items', 'simple-link-directory'); ?></span>
                </button>
            </p>
            
            <div id="sld_ai_gen_status"></div>
        <?php endif; ?>
    </div>
    <?php
}

//convert previous settings to new settings
add_action('plugins_loaded', 'qcopd_sld_plugin_loaded_fnc');
function qcopd_sld_plugin_loaded_fnc()
{

    if (!get_option('sld_ot_convrt')) {
        $prevOptions = get_option('option_tree');
        if (!empty($prevOptions) && is_array($prevOptions) && array_key_exists('sld_enable_top_part', $prevOptions)) {

            foreach ($prevOptions as $key => $val) {

                update_option($key, $val);
            }
        }
        add_option('sld_ot_convrt', 'yes');
    }

}

register_activation_hook(__FILE__, 'qcopd_sld_activate_callback');

function qcopd_sld_activate_callback($plugin)
{

    if (!get_option('sld_enable_top_part')) {
        update_option('sld_enable_top_part', 'on');
    }

    if (!get_option('sld_enable_search')) {
        update_option('sld_enable_search', 'on');
    }

    if (!get_option('sld_enable_upvote')) {
        update_option('sld_enable_upvote', 'on');
    }

}

function qcopd_sld_activation_redirect($plugin)
{

    $screen = get_current_screen();

    if ((isset($screen->base) && $screen->base == 'plugins') && $plugin == plugin_basename(__FILE__)) {
        //if( $plugin == plugin_basename( __FILE__ ) ) {
        if ('cli' !== php_sapi_name()) {
            exit(wp_redirect(admin_url('edit.php?post_type=sld&page=sld_settings#help')));
        }
    }
}
add_action('activated_plugin', 'qcopd_sld_activation_redirect');


if (function_exists('register_block_type')) {
    function qcopd_sld_gutenberg_block()
    {
        require_once plugin_dir_path(__FILE__) . '/gutenberg/sld-block/plugin.php';
    }
    add_action('init', 'qcopd_sld_gutenberg_block');
}


// Remove view from custom post type.
add_filter('post_row_actions', 'qcopd_sld_remove_row_actions', 10, 1);
function qcopd_sld_remove_row_actions($actions)
{
    if (get_post_type() === 'sld') {
        unset($actions['view']);
    }

    return $actions;
}
// Remove view from taxonomies
add_filter('sld_cat_row_actions', 'qcopd_sld_category_remove_row_actions', 10, 1);
function qcopd_sld_category_remove_row_actions($actions)
{
    unset($actions['view']);
    return $actions;
}

if (is_admin()) {
    require_once('class-plugin-deactivate-feedback.php');
    $SlD_feedback = new QCOPD_SLD_Usage_Feedback(__FILE__, 'plugins@quantumcloud.com', false, true);
}

function qcopd_sld_remove_admin_menu_items()
{
    if (!current_user_can('edit_posts')):
        remove_menu_page('edit.php?post_type=sld');
    endif;
}
add_action('admin_menu', 'qcopd_sld_remove_admin_menu_items');


add_action('admin_notices', 'qcopd_sld_wp_shortcode_notice', 100);
function qcopd_sld_wp_shortcode_notice()
{

    global $pagenow, $typenow;

    if (isset($typenow) && $typenow == 'sld') {
        ?>

        <!-- <div id="message-sld" class="notice notice-info is-dismissible"> -->
        <?php
        /*printf(
            __('%s  %s  %s', 'dna88-wp-notice'),
            '<a href="'.esc_url('https://www.quantumcloud.com/products/simple-link-directory/').'" target="_blank">',
            '<img src="'.esc_url(QCOPD_ASSETS_URL).'/images/halloween25-sld.jpg" >',
            '</a>'
        );*/

        ?>
        <!-- </div> -->


        <div id="message" class="notice notice-info is-dismissible qcld-sld-notic-alart">
            <p>
            <?php
            printf(
                esc_html('%1$s Simple Link Directory %2$s works the best when you create multiple Lists and show them all in a page. Use the following shortcode to display All lists on any page:  %3$s  %4$s  %5$s  Use the %6$s shortcode generator %7$s to select style and other options. ', 'simple-link-directory'),
                '<strong>',
                '</strong>',
                '<code>',
                ' [qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"]',
                '</code>',
                '<strong>',
                '</strong>'
            );

            ?>
            </p>
        </div>


        <?php
        //$page_slug = 'sld-demo-data';

        //$existing_page = get_page_by_path($page_slug);
        //if (!$existing_page) {
            ?>

            <div id="message" class="notice notice-info is-dismissible qcld-sld-demonotic-alart">
                <div class="sld-demo-notice-content">
                    <div class="sld-demo-notice-icon">
                        <img src="<?php echo esc_url(QCOPD_IMG_URL . '/sld-logo.png'); ?>" alt="SLD Logo">
                    </div>
                    <div class="sld-demo-notice-text">
                        <p>
                            <?php
                            printf(
                                esc_html('%1$s Import Simple Link Directory Demo Data:%2$s %3$sImport demo data to see how the plugin works.%4$s %5$sWe will create a sample directory with multiple Lists and create a new demo page using shortcode.%6$s %7$sYou can edit or delete the sample Lists to your requirements.%8$s %9$s Click to Import Data %10$s %11$s', 'simple-link-directory'),
                                '<strong>',
                                '</strong>',
                                '<p>',
                                '</p>',
                                '<p>',
                                '</p>',
                                '<p>',
                                '</p>',
                                '<button type="button" id="sld-start-import-btn" class="button button-primary">',
                                '</button>',
                                '<div id="sld-import-message"></div>',

                            );
                            ?>
                        </p>
                    </div>
                </div>
            </div>



        <div class="qcld-sldquick-flyout">
            <div class="qcld-sldquick-flyout-items">
                <a href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/simple-link-directory/'); ?>"
                    target="_blank" class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item qcld-sldquick-flyout-premium"
                    rel="noopener noreferrer" target="_blank" style="transition-delay: 0ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('Getting Started', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-admin-home"></i>
                </a>
                <a href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/simple-link-directory/'); ?>"
                    target="_blank" class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item" rel="noopener noreferrer"
                    target="_blank" style="transition-delay: 60ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('FAQ', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-flag"></i>
                </a>
                <a href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/simple-link-directory/'); ?>"
                    target="_blank" class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item"
                    style="transition-delay: 90ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('Read the Documentation', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-sos"></i>
                </a>
                <a href="<?php echo esc_url('https://www.quantumcloud.com/resources/free-support/'); ?>" target="_blank"
                    class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item" rel="noopener noreferrer" target="_blank"
                    style="transition-delay: 120ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('Ask for Help', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-email"></i>
                </a>
                <a href="<?php echo esc_url('https://dev.quantumcloud.com/sld/'); ?>" target="_blank"
                    class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item" style="transition-delay: 30ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('Check out the SLD Demo', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-welcome-view-site"></i>
                </a>
                <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"
                    class="qcld-sldquick-flyout-button qcld-sldquick-flyout-item qcld-sldquick-flyout-premium"
                    rel="noopener noreferrer" target="_blank" style="transition-delay: 0ms;">
                    <div class="qcld-sldquick-flyout-label">
                        <div><?php esc_html_e('Upgrade to Premium', 'simple-link-directory'); ?></div>
                    </div>
                    <i class="dashicons dashicons-star-filled"></i>
                </a>
            </div>
            <a href="javascript:void(0);" class="qcld-sldquick-flyout-button qcld-sldquick-flyout-mascot">
                <div class="qcld-sldquick-flyout-label">
                    <div><?php esc_html_e('Start Here', 'simple-link-directory'); ?></div>
                </div>
                <img style="width:100%" src="<?php echo esc_url(QCOPD_IMG_URL . '/logo.png'); ?>" alt="Dialogflow CX">
            </a>
        </div>
    <?php

    }

}