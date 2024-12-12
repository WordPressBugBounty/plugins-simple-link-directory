<?php 
defined('ABSPATH') or die("No direct script access!");

wp_head();

$order = isset($_GET['order']) ? sanitize_text_field($_GET['order']): 'ASC';
$mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']): 'all';
$column = isset($_GET['column']) ? sanitize_text_field($_GET['column']): '3';
$style = isset($_GET['style']) ? sanitize_text_field($_GET['style']): 'simple';
$search = '';
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']): '';
$upvote = '';
$list_id = isset($_GET['list_id']) ? sanitize_text_field($_GET['list_id']): '';

//$item_count = sanitize_text_field(isset($_GET['item_count'])?$_GET['item_count']:'');

echo '<div class="clear">';

echo do_shortcode('[qcopd-directory mode="' . esc_attr($mode) .  '" list_id="' . esc_attr($list_id) . '" style="' . esc_attr($style) . '" column="' . esc_attr($column) . '" search="' . esc_attr($search) . '" category="' . esc_attr($category) . '" upvote="' . esc_attr($upvote) . '" item_count="on" orderby="date" order="' . esc_attr($order) . '"][/qcopd-directory]'); 

echo '</div>';


wp_footer();
?>





