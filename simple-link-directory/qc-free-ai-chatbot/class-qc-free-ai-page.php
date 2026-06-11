<?php
if (defined('ABSPATH') === false) {
    exit;
}
/*
* QuantumCloud Promo + Ai Chatbot Page
* Revised On: 05-05-2026
*/

if ( ! defined( 'qcopd_sld_free_ai_support_path' ) ) {
    define('qcopd_sld_free_ai_support_path', plugin_dir_path(__FILE__));
}

if ( ! defined( 'qcopd_sld_free_ai_support_url' ) )
    define('qcopd_sld_free_ai_support_url', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'qcopd_sld_free_ai_img_url' ) )
    define('qcopd_sld_free_ai_img_url', qcopd_sld_free_ai_support_url . "/images" );


/*Callback function to add the menu */
if ( ! function_exists( 'qcopd_sld_free_ai_show_promo_page_callback_func' ) ) {
    function qcopd_sld_free_ai_show_promo_page_callback_func(){

        add_submenu_page(
            "edit.php?post_type=sld",
            esc_html('Try our Free AI ChatBot - WPBot', 'simple-link-directory'),
            esc_html('Try our Free AI ChatBot - WPBot', 'simple-link-directory'),
            'manage_options',
            "qcopd_sld_free_ai",
            'qcopd_sld_free_ai_promo_support_page_callback_func'
        );
        
    }
}
add_action( 'admin_menu', 'qcopd_sld_free_ai_show_promo_page_callback_func', 100 );

add_action('admin_head', 'qcopd_sld_free_ai_admin_submenu_css');
if ( ! function_exists( 'qcopd_sld_free_ai_admin_submenu_css' ) ) {
    function qcopd_sld_free_ai_admin_submenu_css() {
        echo '<style>
            #adminmenu .wp-submenu li a[href*="edit.php?post_type=sld&page=qcopd_sld_free_ai"] {
                border-top: 1px solid #ddd;
                margin: 5px 1px;
            }
        </style>';
    }
}


/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/

if ( ! function_exists( 'qcopd_sld_free_ai_include_promo_page_scripts' ) ) {	
	function qcopd_sld_free_ai_include_promo_page_scripts( ) {   


        if( isset($_GET["page"]) && !empty($_GET["page"]) && (   $_GET["page"] == "qcopd_sld_free_ai"  ) ){
                             
            wp_enqueue_style( 'qcopd_sld_free_ai_css', qcopd_sld_free_ai_support_url . "css/qc-ai-free-style.css");

            wp_enqueue_script( 'jquery' );

            wp_enqueue_script( 'qcopd_sld_free_ai_js', qcopd_sld_free_ai_support_url . 'js/qc-ai-free-script.js',  array('jquery') );

            wp_add_inline_script( 'qcopd_sld_free_ai_js', 
                                    'var qcopd_sld_free_ai_ajaxurl    = "' . admin_url('admin-ajax.php') . '";
                                    var qcopd_sld_free_ai_ajax_nonce  = "'. wp_create_nonce( 'qc-clr' ).'";   
                                ', 'before');
            
        }
	   
	}
	add_action('admin_enqueue_scripts', 'qcopd_sld_free_ai_include_promo_page_scripts');
	
}
		
/*******************************
 * Callback function to show the HTML
 *******************************/

include_once qcopd_sld_free_ai_support_path . '/qc-sld-ai-free-plugin.php';

if ( ! function_exists( 'qcopd_sld_free_ai_promo_support_page_callback_func' ) ) {

	function qcopd_sld_free_ai_promo_support_page_callback_func() {
		
?>

        <div class="wrap">
            <div class="qcopd_sld_free_ai-wrapper">
                <header class="qcopd_sld_free_ai-header">
                    <h3><?php esc_html_e('Get the Best ChatBot for WordPress – WPBot', 'simple-link-directory'); ?></h3>
                    <p><?php esc_html_e('Do more than just chat with your ChatBot!', 'simple-link-directory'); ?></p>
                    
                                        
                </header>

                <div class="qcopd_sld_free_ai-grid">
                    <!-- Section: Getting Started -->
                 
                    <div class="qcopd_sld_free_ai-card">
                        <div class="qcopd_sld_free_ai-content">
                            <p><b><?php esc_html_e('Do more than just chat with your ChatBot', 'simple-link-directory'); ?></b>! <?php esc_html_e('More Leads, Conversions', 'simple-link-directory'); ?> &amp; <?php esc_html_e('Satisfied customers while', 'simple-link-directory'); ?> <strong><?php esc_html_e('saving time', 'simple-link-directory'); ?></strong> &amp; <?php esc_html_e('increasing your', 'simple-link-directory'); ?> <strong><?php esc_html_e('business opportunities', 'simple-link-directory'); ?></strong>. <?php esc_html_e('WPBot is the best ChatBot for WordPress to improve user engagement,', 'simple-link-directory'); ?> <b><?php esc_html_e('Generate Leads,', 'simple-link-directory'); ?></b> &amp; <?php esc_html_e('provide', 'simple-link-directory'); ?> <b><?php esc_html_e('Automated Live', 'simple-link-directory'); ?>&nbsp;</b><?php esc_html_e('customer', 'simple-link-directory'); ?> <strong><?php esc_html_e('support', 'simple-link-directory'); ?></strong> <?php esc_html_e('across your website', 'simple-link-directory'); ?> &amp; <?php esc_html_e('social platforms.', 'simple-link-directory'); ?>&nbsp;<?php esc_html_e('The', 'simple-link-directory'); ?> <strong><?php esc_html_e('AI Insight', 'simple-link-directory'); ?></strong> <?php esc_html_e('feature analyzes chat histories', 'simple-link-directory'); ?> &amp; <?php esc_html_e('reveals what your users want.', 'simple-link-directory'); ?></p> <p> <?php esc_html_e('Deliver AI-powered ChatBot services from your websites with LLMs like', 'simple-link-directory'); ?> <b><?php esc_html_e('OpenAI', 'simple-link-directory'); ?> </b> <?php esc_html_e('(ChatGPT), Gemini etc. along with many built-in, powerful features like', 'simple-link-directory'); ?> <b><?php esc_html_e('Live', 'simple-link-directory'); ?></b>&nbsp;<?php esc_html_e('Chat, Chat', 'simple-link-directory'); ?> <b><?php esc_html_e('Histories', 'simple-link-directory'); ?></b>, <b><?php esc_html_e('Conversational forms', 'simple-link-directory'); ?></b>, <strong><?php esc_html_e('Webhooks', 'simple-link-directory'); ?></strong> &amp; <?php esc_html_e('more!', 'simple-link-directory'); ?>&nbsp;</p> 
                        </div>        
                        
                        <div class="qcopd_sld_free_ai_loading">
                            <img src="<?php echo esc_url(qcopd_sld_free_ai_img_url); ?>/loading.gif" alt="loading">
                        </div>
                    </div>

                </div>

                <div class="qcopd_sld_free_ai-footer">
                    <h3><?php esc_html_e('Need More Assistance?', 'simple-link-directory'); ?></h3>
                    <p><?php esc_html_e('Our support team is ready to help you with any issues or custom requests.', 'simple-link-directory'); ?></p>
                    <div class="qcopd_sld_free_ai_btn-group">
                        <a href="<?php echo esc_url('https://www.wpbot.pro/docs/'); ?>" target="_blank" class="qcopd_sld_free_ai_btn-docs"><?php esc_html_e('Knowledge Base', 'simple-link-directory'); ?></a>
                        <a href="<?php echo esc_url('https://www.wpbot.pro/free-support/'); ?>" target="_blank" class="qcopd_sld_free_ai_btn-support"><?php esc_html_e('Get Direct Support', 'simple-link-directory'); ?></a>
                    </div>
                    <div style="margin-top: 40px; color: #8c8f94;">
                        <p><small>&copy; QuantumCloud. <?php esc_html_e('Handcrafted for productivity.', 'simple-link-directory'); ?></small></p>
                    </div>
                </div>

            </div>
        </div>
			

<?php
            
       
    }
}
