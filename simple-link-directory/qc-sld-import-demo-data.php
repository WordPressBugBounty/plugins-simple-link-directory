<?php
defined('ABSPATH') or die("No direct script access!");


// 5. Handle the AJAX request (PHP side)
function qcld_sld_handle_csv_import() {
    check_ajax_referer( 'quantum_ajax_validation_18', 'security' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized user.' );
    }

    if ( ! file_exists( SLD_CSV_FILE_PATH ) ) {
        wp_send_json_error( 'CSV file not found in plugin folder.' );
    }

	//First check if the uploaded file is valid
	$valid = true;
	
	$file_info = wp_check_filetype( SLD_CSV_FILE_PATH );

	if ( 'csv' !== $file_info['ext'] ) {
	    wp_send_json_error( 'The file at the specified path is not a CSV.' );
	}

    
    // 6. Create a new page automatically using PHP
    $page_title 	= esc_html('SLD Demo Data Imported');
    $page_content 	= '[qcopd-directory mode="all" style="simple" column="3" upvote="on" search="true" item_count="on" orderby="date" filterorderby="date" order="ASC" filterorder="ASC" paginate_items="false" favorite="enable" tooltip="false" list_title_font_size="" item_orderby="" list_title_line_height="" title_font_size="" enable_tag_filter="true"  subtitle_font_size="" title_line_height="" subtitle_line_height="" filter_area="normal" topspacing=""  main_click="popup"]';
    $page_slug 		= 'sld-demo-data';

    $existing_page = get_page_by_path( $page_slug );

    if ( ! $existing_page ) {
        $new_page = array(
            'post_title'    => $page_title,
            'post_content'  => $page_content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => $page_slug,
        );
        wp_insert_post( $new_page );
    }else{

	    wp_send_json_success( array(
	        'message' 		=> esc_html('Already CSV data imported and page created.'),
	        'redirect_url' 	=> get_permalink( get_page_by_path( $page_slug ) ), // URL for redirection
	    ) );
    }

	// Check if the file exists and is not empty before opening
	if ( file_exists( SLD_CSV_FILE_PATH ) && filesize( SLD_CSV_FILE_PATH ) > 0 ) {
	    
	    // Open the file directly using the path constant
	    $file = fopen( SLD_CSV_FILE_PATH, "r" );
	    
	    if ( $file !== false ) {
	        $flag = true;

			$baseData = array();

			$count = 0;

			$laps = 1;
			
			//Read fields from CSV file and dump in $baseData
			while(($data = fgetcsv($file)) !== FALSE) 
			{
				
				if ($flag) {
					$flag = false;
					continue;
				}
				
				$baseData[$data[0]][] = array(
					'list_id' 					=> trim($data[0]),
					'list_title' 				=> isset($data[1]) 	? sanitize_text_field((trim($data[1]))) : '',
					'qcopd_item_title' 			=> isset($data[2]) 	? sanitize_text_field((trim($data[2]))) : '',
					'qcopd_item_link' 			=> isset($data[3]) 	? trim($data[3]) : '',
					'qcopd_item_nofollow' 		=> isset($data[4]) 	? trim($data[4]) : 0,
					'qcopd_item_ugc' 			=> isset($data[5]) 	? trim($data[5]) : '',
					'qcopd_item_newtab' 		=> isset($data[6]) 	? trim($data[6]) : 0,
					'qcopd_item_subtitle' 		=> isset($data[7]) 	? sanitize_text_field((trim($data[7]))) : '',
					'qcopd_fa_icon' 			=> isset($data[8]) 	? sanitize_text_field((trim($data[8]))) : '',
					'qcopd_use_favicon' 		=> isset($data[9]) 	? trim($data[9]) : '',
					'qcopd_item_img' 			=> isset($data[10]) ? trim($data[10]) : '',
					'qcopd_item_img_title' 		=> isset($data[11]) ? trim($data[11]) : '',
					'qcopd_item_img_link' 		=> isset($data[12]) ? trim($data[12]) : '',
					'qcopd_upvote_count' 		=> isset($data[13]) ? trim($data[13]) : 0,
					'list_item_bg_color' 		=> isset($data[14]) ? trim($data[14]) : '',
					'attached_terms' 			=> isset($data[15]) ? trim($data[15]) : '',
					'qcopd_entry_time' 			=> date("Y-m-d H:i:s"),
					'qcopd_timelaps' 			=> $laps,
					'qcopd_description' 		=> isset($data[31]) ? trim($data[31]) : '',
					'qcopd_tags' 				=> isset($data[32]) ? trim($data[32]) : '',
					'qcopd_new' 				=> isset($data[33]) ? trim($data[33]) : '',
					'qcopd_featured' 			=> isset($data[34]) ? trim($data[34]) : '',
					'qcopd_image_from_link' 	=> isset($data[35]) ? trim($data[35]) : '',
					'qcopd_generate_title' 		=> isset($data[36]) ? trim($data[36]) : '',
					'list_border_color' 		=> isset($data[16]) ? trim($data[16]) : '',
					'list_bg_color' 			=> isset($data[17]) ? trim($data[17]) : '',
					'list_bg_color_hov' 		=> isset($data[18]) ? trim($data[18]) : '',
					'list_txt_color' 			=> isset($data[19]) ? trim($data[19]) : '',
					'list_txt_color_hov' 		=> isset($data[20]) ? trim($data[20]) : '',
					'list_subtxt_color' 		=> isset($data[21]) ? trim($data[21]) : '',
					'list_subtxt_color_hov' 	=> isset($data[22]) ? trim($data[22]) : '',
					'item_bdr_color' 			=> isset($data[23]) ? trim($data[23]) : '',
					'item_bdr_color_hov' 		=> isset($data[24]) ? trim($data[24]) : '',
					'list_title_color'			=> isset($data[25]) ? trim($data[25]) : '',
					'filter_background_color'	=> isset($data[26]) ? trim($data[26]) : '',
					'filter_text_color'			=> isset($data[27]) ? trim($data[27]) : '',
					'add_block_text' 			=> isset($data[28]) ? sanitize_text_field((trim($data[28]))) : '',
					'menu_order' 				=> isset($data[29]) ? trim($data[29]) : '',
					'post_status' 				=> isset($data[30]) ? trim($data[30]) : '',
				);

				$count++;
				$laps++;

			}
			
			fclose($file);
			//print_r($baseData);exit;
			//Inserting Data from our built array
			
			$keyCounter = 0;
			$metaCounter = 0;
			
			global $wpdb;

			//Sort $baseData numerically
			ksort($baseData, SORT_NUMERIC);
			
			//Parse $baseData and insert in the database
			foreach( $baseData as $key => $data ){
			
				
				//Check menu order for current SLD post, set 0 if empty
				$menu_order_val = isset($data[0]['menu_order']) ? $data[0]['menu_order'] : 0;

				$post_id = (isset($data[0]['list_id']) && $data[0]['list_id'] != "" ) ? $data[0]['list_id'] : '';

				//Grab current LIST title
				$post_title = (isset($data[0]['list_title']) && $data[0]['list_title'] != "" ) ? $data[0]['list_title'] : '';

				//Grab current LIST status, set 'publish' if empty
				$post_status = (isset($data[0]['post_status']) && $data[0]['post_status'] != "" ) ? $data[0]['post_status'] : 'publish';

				if( !empty($post_id) ){
					$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'sld' AND post_status = 'publish' AND ID = $post_id ORDER BY ID DESC LIMIT 1");

					if( empty($post_id) && !empty($post_title) ){
						$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'sld' AND post_status = 'publish' AND post_title LIKE '%$post_title%' ORDER BY ID DESC LIMIT 1");
					}
				}

				//If $post_title is empty, then go for next iteration
				if( $post_title == '' ){
					continue;
				}


				$existing_list = false;

				//Existing post array
				if(!empty(get_post($post_id))){
					$newest_post_id = $post_id;
					$existing_list = true;
				}else{
					//Build post array and insert as new POST
					$post_arr = array(
						'post_title' 	=> trim($post_title),
						'post_status' 	=> $post_status,
						'post_author' 	=> get_current_user_id(),
						'post_type' 	=> 'sld',
						'menu_order' 	=> $menu_order_val,
					);

					wp_insert_post($post_arr);

					//Get the newest post ID, that we just inserted
					$newest_post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'sld' ORDER BY ID DESC LIMIT 1");
				}

				$attachedTerms = '';

				$innerListCounter = 0;

				$configArray = array();
				$addBlockArray = array();

				//Add list meta fields. i.e. list items and configs
				foreach( $data as $k => $item ){

					if( $innerListCounter == 0 && $existing_list===false ){

						$attachedTerms 							= isset($item['attached_terms']) 	? $item['attached_terms'] 		: '';
						$configArray['list_border_color'] 		= isset($item['list_border_color']) ? $item['list_border_color'] 	: '';
						$configArray['list_bg_color'] 			= isset($item['list_bg_color']) 	? $item['list_bg_color'] 		: '';
						$configArray['list_bg_color_hov'] 		= isset($item['list_bg_color_hov']) ? $item['list_bg_color_hov'] 	: '';
						$configArray['list_txt_color'] 			= isset($item['list_txt_color']) 	? $item['list_txt_color'] 		: '';
						$configArray['list_txt_color_hov'] 		= isset($item['list_txt_color_hov']) ? $item['list_txt_color_hov'] 	: '';
						$configArray['list_subtxt_color'] 		= isset($item['list_subtxt_color']) ? $item['list_subtxt_color'] 	: '';
						$configArray['list_subtxt_color_hov'] 	= isset($item['list_subtxt_color_hov']) ? $item['list_subtxt_color_hov']: '';
						$configArray['item_bdr_color'] 			= isset($item['item_bdr_color']) 	? $item['item_bdr_color'] 		: '';
						$configArray['item_bdr_color_hov'] 		= isset($item['item_bdr_color_hov']) ? $item['item_bdr_color_hov'] 	: '';
						$addBlockArray['add_block_text'] 		= isset($item['add_block_text']) 	? $item['add_block_text'] 		: '';

						add_post_meta(
							$newest_post_id, 
							'qcopd_list_conf', array(
								'list_border_color' 		=> 	isset($item['list_border_color']) 		? $item['list_border_color'] : '',
								'list_bg_color' 			=> 	isset($item['list_bg_color']) 			? $item['list_bg_color'] : '',
								'list_bg_color_hov' 		=> 	isset($item['list_bg_color_hov']) 		? $item['list_bg_color_hov'] : '',
								'list_txt_color' 			=> 	isset($item['list_txt_color']) 			? $item['list_txt_color'] : '',
								'list_txt_color_hov' 		=> 	isset($item['list_txt_color_hov']) 		? $item['list_txt_color_hov']: '',
								'list_subtxt_color' 		=> 	isset($item['list_subtxt_color']) 		? $item['list_subtxt_color'] : '',
								'list_subtxt_color_hov' 	=> 	isset($item['list_subtxt_color_hov'])	? $item['list_subtxt_color_hov']: '',
								'item_bdr_color' 			=> 	isset($item['item_bdr_color']) 			? $item['item_bdr_color'] : '',
								'item_bdr_color_hov' 		=>  isset($item['item_bdr_color_hov']) 		? $item['item_bdr_color_hov']: '',
								'list_title_color' 			=>  isset($item['list_title_color']) 		? $item['list_title_color'] : '',
								'filter_background_color' 	=>  isset($item['filter_background_color']) ? $item['filter_background_color']: '',
								'filter_text_color' 		=>  isset($item['filter_text_color']) 		? $item['filter_text_color']: '',
							)
						);

						add_post_meta(
							$newest_post_id, 
							'sld_add_block', array(
								'add_block_text' =>  $item['add_block_text'],
							)
						);

						$innerListCounter++;
					}


					$qcopd_item_title = ( isset($item['qcopd_item_title']) && !empty($item['qcopd_item_title']) ) ? iconv(mb_detect_encoding($item['qcopd_item_title']), "UTF-8", $item['qcopd_item_title']) : '';
					$qcopd_item_subtitle = ( isset($item['qcopd_item_subtitle']) && !empty($item['qcopd_item_subtitle']) ) ? iconv(mb_detect_encoding($item['qcopd_item_subtitle']), "UTF-8", $item['qcopd_item_subtitle']) : '';

					$attachment_id = "";
					$attachmentId = isset($item['qcopd_item_img']) ? $item['qcopd_item_img'] : '';
					$qcopd_image_from_link = isset($item['qcopd_image_from_link']) ? $item['qcopd_image_from_link'] : '';

					$externalImageLinks 	= ( isset($item['qcopd_item_img_link'])  && !empty($item['qcopd_item_img_link'])  ) ? trim($item['qcopd_item_img_link']) : '';

					$sld_direct_link_img_upload_for_list_item = get_option('sld_direct_link_img_upload_for_list_item');

					if( isset($qcopd_image_from_link) && ( $qcopd_image_from_link == 1 ) && isset($qcopd_item_link) && !empty($qcopd_item_link) ){

						$qcld_image_filename = ( isset($qcopd_item_title) && !empty($qcopd_item_title) ) ? $qcopd_item_title : 'sldwebsite';

						$attachment_id = apply_filters('sld_auto_generate_image_from_website_link_filter', $qcopd_item_link, $qcld_image_filename, $attachmentId );

					}else if( !empty( $attachmentId ) && is_numeric($attachmentId) && wp_get_attachment_url( $attachmentId ) ){
					  $attachment_id = $attachmentId;
					}else if( !empty( $attachmentId ) ){

						$image_attachment_id = attachment_url_to_postid( $attachmentId );

						if( isset($image_attachment_id) && !empty($image_attachment_id) ){
							$attachment_id = $image_attachment_id;
						}else{

							require_once ABSPATH . 'wp-admin/includes/media.php';
							require_once ABSPATH . 'wp-admin/includes/file.php';
							require_once ABSPATH . 'wp-admin/includes/image.php';
							if (filter_var($attachmentId, FILTER_VALIDATE_URL)) {
						        // Get the file type
						        $file_type = wp_check_filetype(basename($attachmentId), null);

						        // Prepare an array of data for the attachment
						        $attachment = array(
						            'post_title'     => sanitize_file_name(basename($attachmentId)),
						            'post_mime_type' => $file_type['type'],
						        );

						        // Try to upload the image
						        $attachment_id = media_sideload_image($attachmentId, 0, null, 'id');
						    }

						}


					}else if( !empty( $externalImageLinks ) && ( isset( $sld_direct_link_img_upload_for_list_item ) && ( $sld_direct_link_img_upload_for_list_item == 'on' ) ) ){

						require_once ABSPATH . 'wp-admin/includes/media.php';
						require_once ABSPATH . 'wp-admin/includes/file.php';
						require_once ABSPATH . 'wp-admin/includes/image.php';

						if (filter_var($externalImageLinks, FILTER_VALIDATE_URL)) {

							$array              = explode('/', getimagesize($externalImageLinks)['mime']);
			                $imagetype          = end($array);
			                
			                $qcld_article_text 	= ( isset($item['qcopd_item_title'])  && !empty($item['qcopd_item_title'])  ) ? trim($item['qcopd_item_title']) : '';

			                $uniq_name          = preg_replace( '%\s*[-_\s]+\s*%', ' ',  substr($qcld_article_text, 0, 50) );
			                $uniq_name          = str_replace( ' ', '-',  $uniq_name );
			                $uniq_name          = strtolower( $uniq_name );
			                $uniq_name          = preg_replace('/[^a-zA-Z0-9_ -]/s', '',$uniq_name);
			                $filename           = $uniq_name .'-'. uniqid() . '.' . $imagetype;

			                $uploaddir          = wp_upload_dir();
			                $target_file_name   = $uploaddir['path'] . '/' . $filename;

			                $contents           = file_get_contents( $externalImageLinks );

			                if(!empty($contents)){

				                $savefile           = fopen($target_file_name, 'w');
				                fwrite($savefile, $contents);
				                fclose($savefile);

				                /* add the image title */
				                $image_title        = ucwords( $uniq_name );

				                unset($externalImageLinks);

				                /* insert the attachment */
				                $wp_filetype = wp_check_filetype(basename($target_file_name), null);
				                $attachment  = array(
				                    'guid'              => $uploaddir['url'] . '/' . basename($target_file_name),
				                    'post_mime_type'    => $wp_filetype['type'],
				                    'post_title'        => $image_title,
				                    'post_status'       => 'inherit'
				                );
				                $post_id     = isset($_REQUEST['post_id']) ? absint( sanitize_text_field( $_REQUEST['post_id'])): '';
				                $attachment_id   = wp_insert_attachment($attachment, $target_file_name, $post_id);

					       
					    	}
					    	
					    }


					}
					
					add_post_meta(
						$newest_post_id, 
						'qcopd_list_item01', array(
							'qcopd_item_title' 		=> 	$qcopd_item_title,
							'qcopd_item_link' 		=> 	isset($item['qcopd_item_link']) 		? $item['qcopd_item_link']		: '',
							'qcopd_item_subtitle' 	=> 	$qcopd_item_subtitle,
							'qcopd_item_nofollow' 	=> 	isset($item['qcopd_item_nofollow']) 	? $item['qcopd_item_nofollow']	: '',
							'qcopd_item_ugc' 		=> 	isset($item['qcopd_item_ugc']) 			? $item['qcopd_item_ugc']		: '',
							'qcopd_item_newtab' 	=> 	isset($item['qcopd_item_newtab']) 		? $item['qcopd_item_newtab']	: '',
							'qcopd_fa_icon' 		=> 	isset($item['qcopd_fa_icon']) 			? $item['qcopd_fa_icon']		: '',
							'qcopd_use_favicon' 	=> 	isset($item['qcopd_use_favicon']) 		? $item['qcopd_use_favicon']	: '',
							'qcopd_item_img' 		=> 	$attachment_id,
							'qcopd_upvote_count' 	=> 	isset($item['qcopd_upvote_count']) 		? $item['qcopd_upvote_count']	: 0,
							'list_item_bg_color' 	=> 	isset($item['list_item_bg_color']) 		? $item['list_item_bg_color']	: '',
							'qcopd_entry_time' 		=>  isset($item['qcopd_entry_time']) 		? $item['qcopd_entry_time']		: '',
							'qcopd_timelaps' 		=>  isset($item['qcopd_timelaps']) 			? $item['qcopd_timelaps']		: '',
							'qcopd_item_img_link' 	=>  isset($item['qcopd_item_img_link']) 	? $item['qcopd_item_img_link']	: '',
							'qcopd_description' 	=>  isset($item['qcopd_description']) 		? iconv(mb_detect_encoding($item['qcopd_description']), "UTF-8", $item['qcopd_description'])	: '',
							'qcopd_featured' 		=>  isset($item['qcopd_featured']) 			? $item['qcopd_featured']		: '',
							'qcopd_image_from_link' =>  isset($item['qcopd_image_from_link']) 	? $item['qcopd_image_from_link'] : '',
							'qcopd_generate_title'  =>  isset($item['qcopd_generate_title']) 	? $item['qcopd_generate_title'] : '',
							'qcopd_new' 			=>  isset($item['qcopd_new']) 				? $item['qcopd_new']			: '',
							'qcopd_tags' 			=>  isset($item['qcopd_tags']) 				? $item['qcopd_tags']			: '',
						)
					);
					
					$metaCounter++;
					
				} //end of inner-foreach
				
				$keyCounter++;

				//Relate terms, if exists
				if( !empty($attachedTerms) ){
					
					$termIds = array();

					$postTerms = explode(',', $attachedTerms);

					foreach ($postTerms as $term ) {

						$termId = intval(trim($term));

						$term_name = trim($term);

						if( term_exists($termId, 'sld_cat') ) {

							array_push($termIds, $termId);
							
						}else if(!empty($term_name)) {

							if( term_exists( $term_name, 'sld_cat' ) ){

                        		$term_id 	= term_exists( $term_name, 'sld_cat' );
                        		$term_id 	= isset($term_id['term_id']) ? intval($term_id['term_id']) : intval($term_id);
                        		array_push($termIds, $term_id);

                        	}else{
					       	
					       		$term_id 	= wp_insert_term(
					           					$term_name,
					           					'sld_cat',
									           	array(
									             	'description' => ''
									           	)
					       					);
					       		
					       		$term_id 	= isset($term_id['term_id']) ? intval($term_id['term_id']) : intval($term_id);
                        		array_push($termIds, $term_id);

                        	}


						}

					}

					wp_set_post_terms( $newest_post_id, $termIds, 'sld_cat' );

				}
			
			} //end of outer-foreach
	        
	    }

	} else {
	    wp_send_json_error( 'CSV file is missing or empty.' );
	}

    wp_send_json_success( array(
        'message' 		=> 'CSV data imported and page created successfully.',
        'redirect_url' 	=> get_permalink( get_page_by_path( $page_slug ) ), // URL for redirection
    ) );
}
add_action( 'wp_ajax_qcld_sld_import_csv_from_folder', 'qcld_sld_handle_csv_import' );
add_action('wp_ajax_nopriv_qcld_sld_import_csv_from_folder', 'qcld_sld_handle_csv_import'); // ajax for not logged in users


