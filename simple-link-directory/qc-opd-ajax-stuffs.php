<?php

defined('ABSPATH') or die("No direct script access!");

//add_action('wp_head', 'qcopd_ajax_ajaxurl');
add_action('admin_head', 'qcopd_ajax_ajaxurl');

if (!function_exists('qcopd_ajax_ajaxurl')) {
  function qcopd_ajax_ajaxurl()
  {

    $sld_enable_rtl = (get_option('sld_enable_rtl') == 'on') ? 'on' : '';
    $sld_no_results_found = get_option('sld_no_results_found') ? get_option('sld_no_results_found') : esc_html('No Results Found for Your Search', 'simple-link-directory');

    echo '<script type="text/javascript">
                var ajaxurl = "' . admin_url('admin-ajax.php') . '";
                var qc_sld_get_ajax_nonce = "' . wp_create_nonce('qc-opd') . '";
                var sld_ajax_object_rtl = "' . esc_attr($sld_enable_rtl) . '";
                var sld_no_results_found = "' . esc_attr($sld_no_results_found) . '";
             </script>';
  }
}

//Doing ajax action stuff
if (!function_exists('qcopd_upvote_ajax_action_stuff')) {
  function qcopd_upvote_ajax_action_stuff()
  {

    check_ajax_referer('qc-opd', 'security');

    //Get posted items
    $action = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : '';
    $post_id = isset($_POST['post_id']) ? absint(sanitize_text_field(wp_unslash($_POST['post_id']))) : '';
    $meta_title = isset($_POST['meta_title']) ? sanitize_text_field(wp_unslash($_POST['meta_title'])) : '';
    $meta_link = isset($_POST['meta_link']) ? esc_url_raw(wp_unslash($_POST['meta_link'])) : '';
    $li_id = isset($_POST['li_id']) ? sanitize_text_field(wp_unslash($_POST['li_id'])) : '';

    //Check wpdb directly, for all matching meta items
    global $wpdb;

    // $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = 'qcopd_list_item01'");
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = 'qcopd_list_item01'", $post_id));

    //Defaults
    $votes = 0;

    $data['votes'] = 0;
    $data['vote_status'] = 'failed';

    $voted_id = isset($_COOKIE['voted_li']) ? sanitize_text_field(wp_unslash($_COOKIE['voted_li'])) : array();

    $exists = in_array($li_id, $voted_id);

    //If li-id not exists in the cookie, then prceed to vote
    if (!$exists) {

      if (!empty($results)) {

        //Iterate through items
        foreach ($results as $key => $value) {

          $item = $value;

          $meta_id = $value->meta_id;

          $unserialized = maybe_unserialize($value->meta_value);

          //If meta title and link matches with unserialized data
          if (trim($unserialized['qcopd_item_title']) == trim($meta_title) && trim($unserialized['qcopd_item_link']) == trim($meta_link)) {

            $metaId = $meta_id;

            //Defaults for current iteration
            $upvote_count = 0;
            $new_array = array();
            $flag = 0;

            //Check if there already a set value (previous)
            if (!empty($unserialized) && is_array($unserialized) && array_key_exists('qcopd_upvote_count', $unserialized)) {
              $upvote_count = (int) $unserialized['qcopd_upvote_count'];
              $flag = 1;
            }

            foreach ($unserialized as $key => $value) {
              if ($flag) {
                if ($key == 'qcopd_upvote_count') {
                  $new_array[$key] = $upvote_count + 1;
                } else {
                  $new_array[$key] = $value;
                }
              } else {
                $new_array[$key] = $value;
              }
            }

            if (!$flag) {
              $new_array['qcopd_upvote_count'] = $upvote_count + 1;
            }

            $votes = (int) $new_array['qcopd_upvote_count'];

            $updated_value = maybe_serialize($new_array);

            $wpdb->update(
              $wpdb->postmeta,
              array(
                'meta_value' => $updated_value,
              ),
              array('meta_id' => $metaId)
            );

            $voted_li = array("$li_id");

            $total = 0;
            $total = count($voted_id);
            $total = $total + 1;

            setcookie("voted_li[$total]", $li_id, time() + (86400 * 30), "/");

            $data['vote_status'] = 'success';
            $data['votes'] = $votes;
          }

        }

      }



    }

    $data['cookies'] = $voted_id;

    echo json_encode($data);


    die(); // stop executing script
  }
}

//Implementing the ajax action for frontend users
add_action('wp_ajax_qcopd_upvote_action', 'qcopd_upvote_ajax_action_stuff'); // ajax for logged in users
add_action('wp_ajax_nopriv_qcopd_upvote_action', 'qcopd_upvote_ajax_action_stuff'); // ajax for not logged in users



if (!function_exists('qcopd_help_render_sidebar')) {
  function qcopd_help_render_sidebar()
  {
    ?>
    <div class="sld-col-md-3 sld-sidebar-column">
      <div class="sld-sidebar-card sld-feature-list-card">
        <h3><?php esc_html_e('Simple Link Directory Pro Features', 'simple-link-directory'); ?></h3>



        <div class="sld-pro-banner">
          <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank">
            <img src="<?php echo esc_url(QCOPD_IMG_URL . '/sld-logo.png'); ?>" />
          </a>
        </div>

        <div class="sld-feature-accordion">
          <!-- Usability Features -->
          <div class="sld-feature-accordion-item active">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-admin-customizer sld-color-blue"></span><?php esc_html_e('Enhanced Usability', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Build unlimited Lists and show them in multi-page mode', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Auto Generate Title, Description & Thumbnail from URL', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Video Gallery – Vimeo and Youtube Video Directory', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Live, on page, instant search & filtering of lists', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Tabbed Category Listing of All Your Directory Lists', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Community Features -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-groups sld-color-orange"></span><?php esc_html_e('Community Features', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Complete front end user registration, login and submission', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Allow users to submit links to your directory', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Invite user interaction with Upvotes (Thumbs up, Heart, etc)', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Allow users to embed your lists on their websites', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Allow registered users to create Favorite Lists', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Monetization Features -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-money-alt sld-color-green"></span><?php esc_html_e('Monetization Options', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Integrated PayPal & Stripe payment for link submission', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Create recurring subscription packages for listings', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Claim Listing for Payment feature', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Option to Mark Paid Items as Featured at Top', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('URL masking option for affiliate links', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Design & Layouts -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-art sld-color-red"></span><?php esc_html_e('Design & Layouts', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('16 Premium templates for Single Page mode', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('2 Premium templates for Multi Page mode', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Create or customize templates from your theme folder', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Use theme fonts or choose Google fonts', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Configurable highlight color for each list', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Admin Tools -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-admin-tools sld-color-blue"></span><?php esc_html_e('Admin Tools & Statistics', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Click statistics for admin dashboard', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('CSV Import/Export to manage lists easily', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Order Listing Directory by Link Clicks or Upvotes', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Restrict UpVote by IP or Logged in Users only', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Copy List Items or Links to Other Lists', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Front End Submission Features -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-welcome-write-blog sld-color-blue"></span><?php esc_html_e('Front End Submission Features', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Front End User Registration with Captcha, Log in, Link Submission', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Admin Approves User Submitted Links or Set to Auto Approve', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Enable Free Frontend Submission & Free Submission Limit', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Enable Email Notification for New Item Submission', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Allow User to Update Profile & Upload Image', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Customizability and Flexibility -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-admin-appearance sld-color-orange"></span><?php esc_html_e('Customizability and Flexibility', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Choose theme font or google font', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Customize colors, fonts and almost all aspects of the link lists', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Complete control over directory list ordering', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Custom JS and CSS panel to modify directory functionality', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Over a dozen shortcode parameters & Shortcode Generator', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

          <!-- Topsite List Script Features -->
          <div class="sld-feature-accordion-item">
            <div class="sld-feature-accordion-header">
              <span><span
                  class="dashicons dashicons-chart-bar sld-color-green"></span><?php esc_html_e('Topsite List Script Features', 'simple-link-directory'); ?></span>
              <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
            <div class="sld-feature-accordion-content">
              <ul>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Order Listing Directory by Link Clicks', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Click statistics for admin', 'simple-link-directory'); ?></li>
                <li><span class="dashicons dashicons-yes"></span><?php esc_html_e('Restrict upvote by IP', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Restrict UpVote for Logged in Users only', 'simple-link-directory'); ?>
                </li>
                <li><span
                    class="dashicons dashicons-yes"></span><?php esc_html_e('Enable Upvote for Main Click in General settings', 'simple-link-directory'); ?>
                </li>
              </ul>
            </div>
          </div>

        </div>

        <div class="sld-pro-upgrade">
          <a href="<?php echo esc_url('https://dev.quantumcloud.com/sld/'); ?>" target="_blank"
            class="button button-primary"><?php esc_html_e('View Pro Demo', 'simple-link-directory'); ?></a>
        </div>

        <div class="sld-pro-upgrade" style="margin-top:10px;">
          <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"
            class="button button-primary"><?php esc_html_e('Upgrade to Pro Now', 'simple-link-directory'); ?></a>
        </div>
      </div>
    </div>
    <?php
  }
}

/**
 * Test OpenAI Connection and Retrieve Available Models
 */
function qcopd_sld_test_openai_connection()
{
  if (!current_user_can('manage_options')) {
    wp_send_json_error(array('message' => esc_html('Unauthorized user.', 'simple-link-directory')));
  }

  $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';

  if (empty($api_key)) {
    wp_send_json_error(array('message' => esc_html('API Key is empty.', 'simple-link-directory')));
  }

  $url = 'https://api.openai.com/v1/models';
  $headers = array(
    'Authorization' => 'Bearer ' . $api_key,
  );
  $response = wp_remote_get($url, array(
    'headers' => $headers,
    'timeout' => 15,
  ));

  if (is_wp_error($response)) {
    wp_send_json_error(array('message' => $response->get_error_message()));
  }

  $code = wp_remote_retrieve_response_code($response);
  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body, true);

  if ($code === 200 && !empty($data['data'])) {
    $models = array();
    foreach ($data['data'] as $model) {
      $id = $model['id'];
      if (strpos($id, 'gpt') === 0 || strpos($id, 'o1') === 0 || strpos($id, 'o3') === 0) {
        $models[] = $id;
      }
    }

    asort($models);
    $models = array_values($models);

    wp_send_json_success(array(
      'message' => esc_html('Connection Successful!', 'simple-link-directory'),
      'models' => $models,
    ));
  } else {
    $error_message = isset($data['error']['message']) ? $data['error']['message'] : esc_html('Invalid API key or network error.', 'simple-link-directory');
    wp_send_json_error(array('message' => $error_message));
  }
}
add_action('wp_ajax_qcopd_sld_test_openai_connection', 'qcopd_sld_test_openai_connection');

/**
 * Test Google Gemini Connection and Retrieve Available Models
 */
function qcopd_sld_test_gemini_connection()
{
  if (!current_user_can('manage_options')) {
    wp_send_json_error(array('message' => esc_html('Unauthorized user.', 'simple-link-directory')));
  }

  $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';

  if (empty($api_key)) {
    wp_send_json_error(array('message' => esc_html('API Key is empty.', 'simple-link-directory')));
  }

  $url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $api_key;
  $response = wp_remote_get($url, array(
    'timeout' => 15,
  ));

  if (is_wp_error($response)) {
    wp_send_json_error(array('message' => $response->get_error_message()));
  }

  $code = wp_remote_retrieve_response_code($response);
  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body, true);

  if ($code === 200 && !empty($data['models'])) {
    $models = array();
    foreach ($data['models'] as $model) {
      $name = str_replace('models/', '', $model['name']);
      $models[] = $name;
    }

    asort($models);
    $models = array_values($models);

    wp_send_json_success(array(
      'message' => esc_html('Connection Successful!', 'simple-link-directory'),
      'models' => $models,
    ));
  } else {
    $error_message = isset($data['error']['message']) ? $data['error']['message'] : esc_html('Invalid API key or network error.', 'simple-link-directory');
    wp_send_json_error(array('message' => $error_message));
  }
}
add_action('wp_ajax_qcopd_sld_test_gemini_connection', 'qcopd_sld_test_gemini_connection');

/**
 * Test OpenRouter Connection and Retrieve Available Models
 */
function qcopd_sld_test_openrouter_connection()
{
  if (!current_user_can('manage_options')) {
    wp_send_json_error(array('message' => esc_html('Unauthorized user.', 'simple-link-directory')));
  }

  $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';

  if (empty($api_key)) {
    wp_send_json_error(array('message' => esc_html('API Key is empty.', 'simple-link-directory')));
  }

  // Step 1: Verify the API Key using the auth key endpoint
  $auth_url = 'https://openrouter.ai/api/v1/auth/key';
  $auth_response = wp_remote_get($auth_url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . $api_key,
    ),
    'timeout' => 15,
  ));

  if (is_wp_error($auth_response)) {
    wp_send_json_error(array('message' => $auth_response->get_error_message()));
  }

  $auth_code = wp_remote_retrieve_response_code($auth_response);
  if ($auth_code !== 200) {
    $auth_body = wp_remote_retrieve_body($auth_response);
    $auth_data = json_decode($auth_body, true);
    $error_msg = isset($auth_data['error']['message']) ? $auth_data['error']['message'] : esc_html('Invalid API Key.', 'simple-link-directory');
    wp_send_json_error(array('message' => $error_msg));
  }

  // Step 2: Fetch models list
  $models_url = 'https://openrouter.ai/api/v1/models';
  $response = wp_remote_get($models_url, array(
    'timeout' => 15,
  ));

  if (is_wp_error($response)) {
    wp_send_json_error(array('message' => $response->get_error_message()));
  }

  $code = wp_remote_retrieve_response_code($response);
  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body, true);

  if ($code === 200 && !empty($data['data'])) {
    $models = array();
    $transient_models = array();
    foreach ($data['data'] as $model) {
      $id = $model['id'];
      $name = !empty($model['name']) ? $model['name'] : $id;
      $models[] = array(
        'id' => $id,
        'name' => $name,
      );
      $transient_models[$id] = $name;
    }

    // Sort models by name
    usort($models, function ($a, $b) {
      return strcasecmp($a['name'], $b['name']);
    });

    // Update transient list
    set_transient('sld_openrouter_models_list', $transient_models, DAY_IN_SECONDS);

    wp_send_json_success(array(
      'message' => esc_html('Connection Successful!', 'simple-link-directory'),
      'models' => $models,
    ));
  } else {
    wp_send_json_error(array('message' => esc_html('Failed to fetch OpenRouter models.', 'simple-link-directory')));
  }
}
add_action('wp_ajax_qcopd_sld_test_openrouter_connection', 'qcopd_sld_test_openrouter_connection');

/**
 * AJAX Handler to Reset AI Prompt Instruction to Default
 */
function qcopd_sld_reset_ai_prompt_instruction()
{
  if (!current_user_can('manage_options')) {
    wp_send_json_error(array('message' => esc_html('Unauthorized user.', 'simple-link-directory')));
  }

  $default_prompt = "Generate a JSON list of exactly {count} items about '{prompt}'.\nThe output MUST be a valid JSON array of objects. Do not include any markdown format, backticks, or explanation.\nEach object must contain:\n1. 'title': The name of the website or resource.\n2. 'link': A valid URL of the resource starting with http:// or https://.\n3. 'subtitle': A brief description or subtitle (max 10-15 words).\n\nJSON Schema example:\n[\n  {\n    \"title\": \"Example Site\",\n    \"link\": \"https://example.com\",\n    \"subtitle\": \"This is a short description.\"\n  }\n]";

  update_option('sld_ai_prompt_instruction', $default_prompt);

  wp_send_json_success(array(
    'message' => esc_html('Prompt reset to default successfully!', 'simple-link-directory'),
    'default_prompt' => $default_prompt,
  ));
}
add_action('wp_ajax_qcopd_sld_reset_ai_prompt_instruction', 'qcopd_sld_reset_ai_prompt_instruction');

/**
 * AJAX Handler for generating list items using OpenAI or Gemini
 */
function qcopd_sld_ai_generate_list_items()
{
  if (!current_user_can('edit_posts')) {
    wp_send_json_error(array('message' => esc_html('Unauthorized user.', 'simple-link-directory')));
  }

  $prompt = isset($_POST['prompt']) ? sanitize_text_field(wp_unslash($_POST['prompt'])) : '';
  $count = isset($_POST['count']) ? intval($_POST['count']) : 5;

  if (empty($prompt)) {
    wp_send_json_error(array('message' => esc_html('Prompt is empty.', 'simple-link-directory')));
  }

  $provider = get_option('sld_enable_ai_provider', 'none');

  if ($provider === 'none') {
    wp_send_json_error(array('message' => esc_html('No AI provider is enabled in settings.', 'simple-link-directory')));
  }

  $default_prompt = "Generate a JSON list of exactly {count} items about '{prompt}'.\nThe output MUST be a valid JSON array of objects. Do not include any markdown format, backticks, or explanation.\nEach object must contain:\n1. 'title': The name of the website or resource.\n2. 'link': A valid URL of the resource starting with http:// or https://.\n3. 'subtitle': A brief description or subtitle (max 10-15 words).\n\nJSON Schema example:\n[\n  {\n    \"title\": \"Example Site\",\n    \"link\": \"https://example.com\",\n    \"subtitle\": \"This is a short description.\"\n  }\n]";
  $prompt_template = get_option('sld_ai_prompt_instruction', $default_prompt);
  $prompt_instruction = str_replace(array('{count}', '{prompt}'), array($count, $prompt), $prompt_template);

  $json_content = '';

  if ($provider === 'openai') {
    $api_key = get_option('sld_openai_api_key');
    $model = get_option('sld_openai_model', 'gpt-4o');

    if (empty($api_key)) {
      wp_send_json_error(array('message' => esc_html('OpenAI API key is missing.', 'simple-link-directory')));
    }

    $url = 'https://api.openai.com/v1/chat/completions';
    $headers = array(
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $api_key,
    );
    $payload = array(
      'model' => $model,
      'messages' => array(
        array(
          'role' => 'system',
          'content' => 'You are a helpful assistant that only outputs valid JSON arrays. Do not include markdown code block formatting (like ```json ... ```) or explanations. Output a raw JSON array only.',
        ),
        array(
          'role' => 'user',
          'content' => $prompt_instruction,
        ),
      ),
    );

    if (strpos($model, 'o1') !== 0 && strpos($model, 'o3') !== 0) {
      $openai_temp = get_option('sld_openai_temperature', '1.0');
      $payload['temperature'] = floatval($openai_temp);
    }

    $response = wp_remote_post($url, array(
      'headers' => $headers,
      'body' => json_encode($payload),
      'timeout' => 30,
    ));

    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['choices'][0]['message']['content'])) {
      $json_content = $data['choices'][0]['message']['content'];
    } else {
      $error_message = isset($data['error']['message']) ? $data['error']['message'] : esc_html('OpenAI API error.', 'simple-link-directory');
      wp_send_json_error(array('message' => $error_message));
    }

  } elseif ($provider === 'gemini') {
    $api_key = get_option('sld_gemini_api_key');
    $model = get_option('sld_gemini_model', 'gemini-1.5-flash');

    if (empty($api_key)) {
      wp_send_json_error(array('message' => esc_html('Gemini API key is missing.', 'simple-link-directory')));
    }

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;
    $headers = array(
      'Content-Type' => 'application/json',
    );
    $gemini_temp = get_option('sld_gemini_temperature', '1.0');
    $payload = array(
      'contents' => array(
        array(
          'parts' => array(
            array(
              'text' => 'You are a helpful assistant that only outputs valid JSON arrays. Do not include markdown code block formatting (like ```json ... ```) or explanations. Output a raw JSON array only. ' . $prompt_instruction,
            ),
          ),
        ),
      ),
      'generationConfig' => array(
        'responseMimeType' => 'application/json',
        'temperature' => floatval($gemini_temp),
      ),
    );

    $response = wp_remote_post($url, array(
      'headers' => $headers,
      'body' => json_encode($payload),
      'timeout' => 30,
    ));

    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['candidates'][0]['content']['parts'][0]['text'])) {
      $json_content = $data['candidates'][0]['content']['parts'][0]['text'];
    } else {
      $error_message = isset($data['error']['message']) ? $data['error']['message'] : esc_html('Gemini API error.', 'simple-link-directory');
      wp_send_json_error(array('message' => $error_message));
    }
  } elseif ($provider === 'openrouter') {
    $api_key = get_option('sld_openrouter_api_key');
    $model = get_option('sld_openrouter_model', 'google/gemini-2.5-flash');

    if (empty($api_key)) {
      wp_send_json_error(array('message' => esc_html('OpenRouter API key is missing.', 'simple-link-directory')));
    }

    $url = 'https://openrouter.ai/api/v1/chat/completions';
    $headers = array(
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $api_key,
      'HTTP-Referer' => home_url(),
      'X-Title' => 'Simple Link Directory',
    );
    $openrouter_temp = get_option('sld_openrouter_temperature', '1.0');
    $payload = array(
      'model' => $model,
      'messages' => array(
        array(
          'role' => 'system',
          'content' => 'You are a helpful assistant that only outputs valid JSON arrays. Do not include markdown code block formatting (like ```json ... ```) or explanations. Output a raw JSON array only.',
        ),
        array(
          'role' => 'user',
          'content' => $prompt_instruction,
        ),
      ),
      'temperature' => floatval($openrouter_temp),
    );

    $response = wp_remote_post($url, array(
      'headers' => $headers,
      'body' => json_encode($payload),
      'timeout' => 30,
    ));

    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($code === 200 && !empty($data['choices'][0]['message']['content'])) {
      $json_content = $data['choices'][0]['message']['content'];
    } else {
      $error_message = isset($data['error']['message']) ? $data['error']['message'] : (isset($data['error']) && is_string($data['error']) ? $data['error'] : esc_html('OpenRouter API error.', 'simple-link-directory'));
      wp_send_json_error(array('message' => $error_message));
    }
  }

  $json_content = trim($json_content);
  if (strpos($json_content, '```') === 0) {
    $json_content = preg_replace('/^```(?:json)?/i', '', $json_content);
    $json_content = preg_replace('/```$/', '', $json_content);
    $json_content = trim($json_content);
  }

  $items = json_decode($json_content, true);

  if (empty($items) || !is_array($items)) {
    wp_send_json_error(array(
      'message' => esc_html('Failed to parse AI output. Raw content: ', 'simple-link-directory') . substr($json_content, 0, 100),
    ));
  }

  wp_send_json_success(array(
    'message' => sprintf(esc_html('Generated %d items successfully!', 'simple-link-directory'), count($items)),
    'items' => $items,
  ));
}
add_action('wp_ajax_qcopd_sld_ai_generate_list_items', 'qcopd_sld_ai_generate_list_items');