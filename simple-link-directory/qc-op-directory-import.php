<?php
defined('ABSPATH') or die("No direct script access!");

class Qcopd_BulkImportFree
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'qcopd_info_menu'));
    }

    public $post_id;

    function qcopd_info_menu(){

        add_submenu_page(
            'edit.php?post_type=sld',
            esc_html__('Bulk Import', 'qc-opd'),
            esc_html__('Import', 'qc-opd'),
            'manage_options',
            'qcopd_bimport_page',
            array(
                $this,
                'qcopd_bimport_page_content'
            )
        );
        
    }

    function qcopd_bimport_page_content()
    {
        ?>
        <div class="wrap">

            <div id="poststuff">

                <div id="post-body" class="metabox-holder columns-3">

                    <div id="post-body-content" style="position: relative;">

                        
                            <h1><?php esc_html_e('Bulk Import', 'qc-opd'); ?></h1>
                        
                        <div class="qcld-importstylebox">
                        <div>
                            
                            <p>
								<strong><?php esc_html_e('Please Note:', 'qc-opd'); ?></strong> <?php esc_html_e('The import feature is still under development. Right now it only allows importing and creating new Lists. Existing Lists will not get updated. Also, export feature is not available in free version.', 'qc-opd'); ?>
							</p>
							
							<p>
                                <strong><?php esc_html_e('Sample CSV File:', 'qc-opd'); ?></strong>
                                <a href="<?php echo esc_url(QCOPD_ASSETS_URL.'/file/sample-csv-file.csv'); ?>" target="_blank">
                                    <?php esc_html_e('Download', 'qc-opd'); ?>
                                </a>
                            </p>

                            <p><strong><?php esc_html_e('PROCESS:', 'qc-opd'); ?></strong></p>

                            <p>
                                <ol>
                                    <li><?php esc_html_e('First download the above CSV file.', 'qc-opd'); ?></li>
                                    <li><?php esc_html_e('Add/Edit rows on the top of it, by maintaing proper provided format/fields.', 'qc-opd'); ?></li>
                                    <li><?php esc_html_e('Finally, upload file in the below form.', 'qc-opd'); ?></li>
                                </ol>
                            </p>



                            <p><strong><?php esc_html_e('NOTES:', 'qc-opd'); ?></strong></p>

                            <p>
                                <ol>
                                    <li><?php esc_html_e('It should be a simple CSV file.', 'qc-opd'); ?></li>
                                    <li><?php esc_html_e('File encoding should be in UTF-8', 'qc-opd'); ?></li>
                                    <li><?php esc_html_e('File must be prepared as per provided sample CSV file.', 'qc-opd'); ?></li>
                                </ol>
                            </p>
                            
                        </div>

                        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">

                        <!-- Handle CSV Upload -->

                        <?php

                        $randomNum = substr(sha1(mt_rand() . microtime()), mt_rand(0,35), 5);

                        if( !empty($_POST) && isset($_POST['upload_csv']) ) 
                        {

                            check_admin_referer( 'qcsld_import_nonce' );

                            if ( function_exists('is_user_logged_in') && is_user_logged_in() && current_user_can( 'manage_options' ) ) 
							{

								
								if ( ! function_exists( 'wp_handle_upload' ) ) {
									require_once( ABSPATH . 'wp-admin/includes/file.php' );
								}

								$uploadedfile = $_FILES['csv_upload'];

								//$upload_overrides = array( 'test_form' => false );
                                $upload_overrides = array(
                                    'mimes'     => array(
                                        'csv'  => 'text/csv',
                                    ),
                                    'test_form' => false,
                                );

								$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

								if ( $movefile && ! isset( $movefile['error'] ) ) {
									

									$tmpName = $movefile['file'];
									$file = fopen($tmpName, "r");
									$flag = true;
									
									//Reading file and building our array
									
									$baseData = array();

									$count = 0;

									while(($data = fgetcsv($file)) !== FALSE) 
									{
										if ($flag) {
											$flag = false;
											continue;
										}
										
										$baseData[$data[0]][] 		= array(
											'list_title' 			=> isset($data[0]) ? sanitize_text_field(iconv(mb_detect_encoding($data[1]), "UTF-8", $data[0])) : '',
											'qcopd_item_title' 		=> isset($data[1]) ? sanitize_text_field(iconv(mb_detect_encoding($data[1]), "UTF-8", $data[1])) : '',
											'qcopd_item_link' 		=> isset($data[2]) ? esc_url_raw(iconv(mb_detect_encoding($data[2]), "UTF-8", $data[2])) : '',
											'qcopd_item_img' 		=> '',
											'qcopd_item_nofollow' 	=> isset($data[3]) ? sanitize_text_field(trim($data[3])) : 0,
											'qcopd_item_newtab' 	=> isset($data[4]) ? sanitize_text_field(trim($data[4])) : 0,
											'qcopd_item_subtitle' 	=> isset($data[5]) ? sanitize_text_field(trim($data[5])) : '',
											'list_item_bg_color' 	=> isset($data[6]) ? sanitize_text_field(trim($data[6])) : ''
										);

										$count++;

									}
									
									fclose($file);
									//Inserting Data from our built array
									
									$keyCounter = 0;
									$metaCounter = 0;
									
									global $wpdb;
									
									foreach( $baseData as $key => $data ){
									
										$post_arr = array(
											'post_title' => trim($key),
											'post_status' => 'publish',
											'post_author' => get_current_user_id(),
											'post_type' => 'sld',
										);

										wp_insert_post($post_arr);

										$newest_post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'sld' ORDER BY ID DESC LIMIT 1");

										foreach( $data as $k => $item ){										
											add_post_meta(
												$newest_post_id, 
												'qcopd_list_item01', array(
													'qcopd_item_title' 		=> isset($item['qcopd_item_title']) 	? $item['qcopd_item_title'] 	: '',
													'qcopd_item_link' 		=> isset($item['qcopd_item_link'])  	? $item['qcopd_item_link']  	: '',
													'qcopd_item_img' 		=> '',
													'qcopd_item_nofollow' 	=> isset($item['qcopd_item_nofollow']) 	? $item['qcopd_item_nofollow']	: 0,
													'qcopd_item_newtab' 	=> isset($item['qcopd_item_newtab']) 	? $item['qcopd_item_newtab']	: 0,
													'qcopd_item_subtitle' 	=> isset($item['qcopd_item_subtitle']) 	? $item['qcopd_item_subtitle']  : '',
													'list_item_bg_color' 	=> isset($item['list_item_bg_color']) 	? $item['list_item_bg_color'] 	: ''
												)
											);
											
											$metaCounter++;
											
										} //end of inner-foreach
										
										$keyCounter++;
									
									} //end of outer-foreach

									if( ( isset($keyCounter) && $keyCounter > 0 ) && ( isset($metaCounter) && $metaCounter > 0 ) ) {

										echo  '<div><span style="color: red; font-weight: bold;">'.esc_html__('RESULT:', 'qc-opd').'</span> <strong>'.esc_attr( $keyCounter ).'</strong>  '.esc_html__('entry with', 'qc-opd').' <strong>'.esc_attr( $metaCounter ).'</strong> '.esc_html__('element(s) was made successfully.', 'qc-opd').'</div>';
										
									}
									if(file_exists($movefile['file'])){
										unlink($movefile['file']);
									}
									
								}
                            }

                        } 
                        else 
                        {
							//echo "Attached file is invalid!";
                        }

                        ?>
                            
                            <p>
                                <strong>
                                    <?php echo esc_html__('Upload csv file to import'); ?>
                                </strong>
                            </p>

                            <form name="uploadfile" id="uploadfile_form" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">

                                <p>
                                    <?php echo esc_html__('Select file to upload') ?>
                                    <input type="file" name="csv_upload" id="csv_upload" size="35" class="uploadfiles"/>
                                </p>
								<p style="color:red;"><?php echo esc_html__('**CSV File & Characters must be saved with UTF-8 encoding**'); ?></p>
                                <p>
                                    <input class="button-primary" type="submit" name="upload_csv" id="" value="<?php echo esc_html__('Upload & Process') ?>"/>
                                </p>
                                <?php wp_nonce_field('qcsld_import_nonce'); ?>

                            </form>

                        </div>


                        <div style="padding: 15px 10px; border: 1px solid #ccc; text-align: center; margin-top: 20px;background: #222; color: #fff;">
                            <?php esc_html_e('Crafted By:', 'qc-opd'); ?> <a href="<?php echo esc_url('http://www.quantumcloud.com'); ?>" target="_blank"><?php esc_html_e('Web Design Company', 'qc-opd'); ?></a> <?php esc_html_e('-QuantumCloud', 'qc-opd'); ?>
                        </div>

                    </div>
                    <!-- /post-body-content -->
                    </div>
                </div>
                <!-- /post-body-->

            </div>
            <!-- /poststuff -->


        </div>
        <!-- /wrap -->





        <?php
    }
}

new Qcopd_BulkImportFree;
