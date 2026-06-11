<?php

defined('ABSPATH') or die("No direct script access!");

//Registering custom post for Team
if ( ! function_exists( 'qcopd_register_cpt_sld' ) ) {
	function qcopd_register_cpt_sld() {
		
		$qc_list_labels = array(
			'name'               => esc_html( 'Manage Lists', 'simple-link-directory' ),
			'singular_name'      => esc_html( 'Manage List Item', 'simple-link-directory' ),
			'add_new'            => esc_html( 'New List', 'simple-link-directory' ),
			'add_new_item'       => esc_html( 'Add New List', 'simple-link-directory' ),
			'edit_item'          => esc_html( 'Edit List Item', 'simple-link-directory' ),
			'new_item'           => esc_html( 'New List Item', 'simple-link-directory' ),
			'all_items'          => esc_html( 'Manage Lists', 'simple-link-directory' ),
			'view_item'          => esc_html( 'View List Item', 'simple-link-directory' ),
			'search_items'       => esc_html( 'Search List Item', 'simple-link-directory' ),
			'not_found'          => esc_html( 'No List Item found', 'simple-link-directory' ),
			'not_found_in_trash' => esc_html( 'No List Item found in the Trash', 'simple-link-directory' ), 
			'parent_item_colon'  => '',
			'menu_name'          => esc_html( 'Simple Link Directory', 'simple-link-directory' )
		);
		
		$qc_list_args = array(
			'labels'        		=> $qc_list_labels,
			'description'   		=> esc_html('This post type holds all posts for your directory items.', 'simple-link-directory'),
			'public'        		=> true,
			'publicly_queryable' 	=> false,
			'menu_position' 		=> 25,
			'exclude_from_search' 	=> true,
			'show_in_nav_menus' 	=> false,
			'supports'      		=> array( 'title' ),
			'has_archive'   		=> true,
			'menu_icon' 			=> QCOPD_IMG_URL . '/menu_icon.png',
		);
		
		register_post_type( 'sld', $qc_list_args );	
		
		//Register New Taxonomy for Our New Post Type
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => esc_html( 'List Categories', 'List Categories', 'simple-link-directory' ),
			'singular_name'     => esc_html( 'Category', 'taxonomy singular name', 'simple-link-directory' ),
			'search_items'      => esc_html( 'Search List Categories', 'simple-link-directory' ),
			'all_items'         => esc_html( 'All List Categories', 'simple-link-directory' ),
			'parent_item'       => esc_html( 'Parent List Categories', 'simple-link-directory' ),
			'parent_item_colon' => esc_html( 'Parent List Category:', 'simple-link-directory' ),
			'edit_item'         => esc_html( 'Edit List Category', 'simple-link-directory' ),
			'update_item'       => esc_html( 'Update List Category', 'simple-link-directory' ),
			'add_new_item'      => esc_html( 'Add New List Category', 'simple-link-directory' ),
			'new_item_name'     => esc_html( 'New List Category Name', 'simple-link-directory' ),
			'menu_name'         => esc_html( 'List Categories', 'simple-link-directory' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sld_cat' ),
		);

		register_taxonomy( 'sld_cat', array( 'sld' ), $args );
		
	}
}

add_action( 'init', 'qcopd_register_cpt_sld' );
add_action( 'init', 'qcopd_load_cmb' );
if ( ! function_exists( 'qcopd_load_cmb' ) ) {
	function qcopd_load_cmb(){
		$post_type = '';
		if(isset($_GET['post']) && $_GET['post']!=''){
			$post = get_post(sanitize_text_field($_GET['post']));
			$post_type = $post->post_type;
		}elseif(isset($_GET['post_type']) && $_GET['post_type']=='sld'){
			$post_type = 'sld';
		}elseif(isset($_POST['post_type']) && $_POST['post_type']=='sld'){
			$post_type = 'sld';
		}
			
		if ( ! class_exists( 'CMB_Meta_Box' ) ){

			if ( is_admin() && $post_type == 'sld') {
				require_once QCOPD_INC_DIR . '/cmb/custom-meta-boxes.php';
			}
		}
	}
}


//Metabox Fields
if ( ! function_exists( 'cmb_qcopd_dir_fields' ) ) {
	function cmb_qcopd_dir_fields( array $meta_boxes ) {
		
		//Repeatable Fields
		$qcopd_item_fields = array(
			array( 'id' => 'qcopd_item_title',  'name' => 'Item Title', 'type' => 'text', 'cols' => 4, 'desc' => 'Title of the list item' ),
			array( 'id' => 'qcopd_item_link',  'name' => 'Item Link', 'type' => 'text', 'cols' => 4, 'desc' => 'With http://, Example: http://www.google.com' ),
			array( 'id' => 'qcopd_upvote_count',  'name' => 'Upvote Count', 'type' => 'text', 'cols' => 4, 'default' => '0', 'desc' => 'Total upvote for this element' ),
			array( 'id' => 'qcopd_item_img', 'name' => 'List Image', 'type' => 'image', 'repeatable' => false, 'show_size' => false, 'cols' => 3, 'desc' => 'Preferred Size: 100X100px'  ),
			array( 'id' => 'qcopd_item_nofollow',  'name' => 'No Follow', 'type' => 'checkbox', 'cols' => 3, 'default' => 0 ),
			
			
			
			array( 'id' => 'qcopd_entry_time',  'name' => 'Entry Time', 'type' => 'text', 'cols' => 4, 'default' => ''.date("Y-m-d H:i:s").'' ),	
			array( 'id' => 'qcopd_timelaps',  'name' => 'Time Laps', 'type' => 'text', 'cols' => 4, 'default' => '' ),	
			
			array( 'id' => 'qcopd_item_newtab',  'name' => 'Open Link in a New Tab', 'type' => 'checkbox', 'cols' => 3, 'default' => 0 ),
			array( 'id' => 'qcopd_featured',  'name' => 'Mark Item as Featured', 'type' => 'checkbox', 'cols' => 3, 'default' => 0, 'desc' => '' ),
			array( 'id' => 'qcopd_item_subtitle',  'name' => 'Item Subtitle', 'type' => 'text', 'cols' => 7 ),
			array( 'id' => 'list_item_bg_color',  'name' => 'Item Background Color', 'type' => 'colorpicker', 'cols' => 2, 'default' => '0' ),
			
		);
	    

		$meta_boxes[] = array(
			'title' 		=> esc_html('List Elements'),
			'pages' 		=> 'sld',
			'fields' 		=> array(
				array(
					'id' 			=> 'qcopd_list_item01',
					'name' 			=> esc_html('Create List Elements'),
					'type' 			=> 'group',
					'repeatable' 	=> true,
					'sortable' 		=> true,
					'fields' 		=> $qcopd_item_fields,
					'desc' 			=> esc_html('Please add your list items here. Ideally, each List should have 10-30 links. Create multiple Lists on different topics and show them all with a shortcode.', 'simple-link-directory') . ' <br><br><i style="color:indianred;font-size:14px !important; font-weight:bold;">'.esc_html('If you are unable to save a long List, please increase the value of max_input_vars to 15000 on your server.', 'simple-link-directory').'</i>'
				)
			)
		);

		return $meta_boxes;

	}
}

add_filter( 'cmb_meta_boxes', 'cmb_qcopd_dir_fields', null );

//Custom Columns for Directory Listing
if ( ! function_exists( 'qcopd_list_columns_head' ) ) {
	function qcopd_list_columns_head($defaults) {

	    $new_columns['cb'] 					= '<input type="checkbox" />';
	    $new_columns['title'] 				= esc_html('Title', 'simple-link-directory');
	    $new_columns['qcopd_item_count'] 	= esc_html('Number of Elements', 'simple-link-directory');
	    $new_columns['shortcode_col'] 		= esc_html('Shortcode', 'simple-link-directory');
	    $new_columns['date'] 				= esc_html('Date', 'simple-link-directory');

	    return $new_columns;
	}
}
 
//Custom Columns Data for Backend Listing
if ( ! function_exists( 'qcopd_list_columns_content' ) ) {
	function qcopd_list_columns_content( $column_name, $post_ID ) {
	    
	    if ($column_name == 'qcopd_item_count') {
	        $items = get_post_meta( $post_ID, 'qcopd_list_item01' );
	        $count = is_array($items) ? count($items) : 0;
	        echo '<span class="sld-item-count-badge">' . esc_html($count) . ' ' . _n('element', 'elements', $count, 'simple-link-directory') . '</span>';
	    }

	    if ($column_name == 'shortcode_col') {
            $single_shortcode = '[qcopd-directory mode="one" style="simple" list_id="'.$post_ID.'"]';
            $all_shortcode = '[qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"]';
            ?>
            <div class="sld-shortcode-col-wrap">
                <div class="sld-shortcode-item">
                    <span class="sld-shortcode-label"><?php esc_html_e("Single List:", 'simple-link-directory'); ?></span>
                    <div class="sld-shortcode-container">
                        <code class="sld-shortcode-code"><?php echo esc_html($single_shortcode); ?></code>
                        <button type="button" class="sld-copy-btn" data-shortcode="<?php echo esc_attr($single_shortcode); ?>" title="<?php esc_attr_e("Copy Shortcode", 'simple-link-directory'); ?>">
                            <span class="dashicons dashicons-admin-page"></span>
                            <span class="sld-copy-tooltip"><?php esc_html_e("Copied!", 'simple-link-directory'); ?></span>
                        </button>
                    </div>
                </div>
                <div class="sld-shortcode-item">
                    <span class="sld-shortcode-label"><?php esc_html_e("All Lists:", 'simple-link-directory'); ?></span>
                    <div class="sld-shortcode-container">
                        <code class="sld-shortcode-code"><?php echo esc_html($all_shortcode); ?></code>
                        <button type="button" class="sld-copy-btn" data-shortcode="<?php echo esc_attr($all_shortcode); ?>" title="<?php esc_attr_e("Copy Shortcode", 'simple-link-directory'); ?>">
                            <span class="dashicons dashicons-admin-page"></span>
                            <span class="sld-copy-tooltip"><?php esc_html_e("Copied!", 'simple-link-directory'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <?php
	    }
	}
}

add_filter('manage_sld_posts_columns', 'qcopd_list_columns_head');
add_action('manage_sld_posts_custom_column', 'qcopd_list_columns_content', 10, 2);


