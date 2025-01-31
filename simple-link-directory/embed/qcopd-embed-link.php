<?php 
defined('ABSPATH') or die("No direct script access!");

wp_head();

$order      = isset($_GET['order']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['order'])))) : esc_attr('ASC');
$mode      	= isset($_GET['mode']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['mode'])))) : esc_attr('all');
$column     = isset($_GET['column']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['column'])))) : esc_attr('3');
$style     = isset($_GET['style']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['style'])))) : esc_attr('simple');
$category     = isset($_GET['category']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['category'])))) : '';

$list_id     = isset($_GET['list_id']) ? preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags(sanitize_text_field(wp_unslash($_GET['list_id'])))) : '';
$search = '';
$upvote = '';

echo '<div class="clear">';

echo do_shortcode('[qcopd-directory mode="' . esc_attr($mode) .  '" list_id="' . esc_attr($list_id) . '" style="' . esc_attr($style) . '" column="' . esc_attr($column) . '" search="' . esc_attr($search) . '" category="' . esc_attr($category) . '" upvote="' . esc_attr($upvote) . '" item_count="on" orderby="date" order="' . esc_attr($order) . '"][/qcopd-directory]'); 

echo '</div>';


wp_footer();
?>





