<?php
defined('ABSPATH') or die("No direct script access!");

class Qcopd_BulkImportFree
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'qcopd_info_menu'));
    }

    public $post_id;

    function qcopd_info_menu()
    {

        add_submenu_page(
            'edit.php?post_type=sld',
            esc_html('Bulk Import', 'simple-link-directory'),
            esc_html('Import', 'simple-link-directory'),
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
        <div class="wrap  sld-dashboard-wrap">
            <div class="sld-row">
                <div class="sld-col-md-9 sld-main-column">

                    <div id="post-body-content">

                        <div class="sld-container">


                            <div class="qcld-importstylebox">
                                
                                <h3><?php esc_html_e('Bulk Import', 'simple-link-directory'); ?></h3>
                                <hr>
                                <!-- Alert Warning for development note -->
                                <div class="sld-alert-warning">
                                    <strong><?php esc_html_e('Please Note:', 'simple-link-directory'); ?></strong>
                                    <?php esc_html_e('The import feature is still under development. Right now it only allows importing and creating new Lists. Existing Lists will not get updated. Also, export feature is not available in free version.', 'simple-link-directory'); ?>
                                </div>

                                <!-- Guidelines and Info Card -->
                                <div class="sld-import-card">
                                    <p>
                                        <strong><?php esc_html_e('Sample CSV File:', 'simple-link-directory'); ?></strong>
                                        <a href="<?php echo esc_url(SLD_QCOPD_ASSETS_URL . '/file/sample-csv-file.csv'); ?>"
                                            target="_blank" class="sld-btn-primary" style="padding: 5px 15px; font-size: 12px; margin-left: 10px;">
                                            <?php esc_html_e('Download Sample', 'simple-link-directory'); ?>
                                        </a>
                                    </p>

                                    <h4><?php esc_html_e('PROCESS:', 'simple-link-directory'); ?></h4>
                                    <ol>
                                        <li><?php esc_html_e('First download the above CSV file.', 'simple-link-directory'); ?></li>
                                        <li><?php esc_html_e('Add/Edit rows on the top of it, by maintaing proper provided format/fields.', 'simple-link-directory'); ?></li>
                                        <li><?php esc_html_e('Finally, upload file in the below form.', 'simple-link-directory'); ?></li>
                                    </ol>

                                    <h4><?php esc_html_e('NOTES:', 'simple-link-directory'); ?></h4>
                                    <ol>
                                        <li><?php esc_html_e('It should be a simple CSV file.', 'simple-link-directory'); ?></li>
                                        <li><?php esc_html_e('File encoding should be in UTF-8', 'simple-link-directory'); ?></li>
                                        <li><?php esc_html_e('File must be prepared as per provided sample CSV file.', 'simple-link-directory'); ?></li>
                                    </ol>
                                </div>

                                <!-- Handle CSV Upload -->
                                <?php
                                $randomNum = substr(sha1(wp_rand() . microtime()), wp_rand(0, 35), 5);

                                if (!empty($_POST) && isset($_POST['upload_csv'])) {

                                    check_admin_referer('qcopd_sld_import_nonce');

                                    if (function_exists('is_user_logged_in') && is_user_logged_in() && current_user_can('manage_options') && isset($_FILES['csv_upload'])) {

                                        if (!function_exists('wp_handle_upload')) {
                                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                                        }

                                        $uploadedfile = array(
                                            'name'     => isset($_FILES['csv_upload']['name']) ? sanitize_file_name(wp_unslash($_FILES['csv_upload']['name'])) : '',
                                            'type'     => isset($_FILES['csv_upload']['type']) ? sanitize_mime_type(wp_unslash($_FILES['csv_upload']['type'])) : '',
                                            'tmp_name' => isset($_FILES['csv_upload']['tmp_name']) ? sanitize_text_field(wp_unslash($_FILES['csv_upload']['tmp_name'])) : '',
                                            'error'    => isset($_FILES['csv_upload']['error']) ? intval($_FILES['csv_upload']['error']) : 4,
                                            'size'     => isset($_FILES['csv_upload']['size']) ? intval($_FILES['csv_upload']['size']) : 0,
                                        );

                                        $upload_overrides = array(
                                            'mimes' => array(
                                                'csv' => 'text/csv',
                                            ),
                                            'test_form' => false,
                                        );

                                        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                                        if ($movefile && !isset($movefile['error'])) {

                                            global $wp_filesystem;
                                            if (empty($wp_filesystem)) {
                                                require_once ABSPATH . '/wp-admin/includes/file.php';
                                                WP_Filesystem();
                                            }

                                            $tmpName = $movefile['file'];
                                            $baseData = array();
                                            $count = 0;

                                            if ($wp_filesystem && $wp_filesystem->exists($tmpName)) {
                                                $file_content = $wp_filesystem->get_contents($tmpName);
                                                $csv_rows = qcopd_parse_csv_string($file_content);
                                                $flag = true;

                                                foreach ($csv_rows as $data) {
                                                    if (empty($data) || !isset($data[0])) {
                                                        continue;
                                                    }
                                                    if ($flag) {
                                                        $flag = false;
                                                        continue;
                                                    }

                                                    $data0 = isset($data[0]) ? $data[0] : '';
                                                    $data1 = isset($data[1]) ? $data[1] : '';
                                                    $data2 = isset($data[2]) ? $data[2] : '';
                                                    $data3 = isset($data[3]) ? trim($data[3]) : 0;
                                                    $data4 = isset($data[4]) ? trim($data[4]) : 0;
                                                    $data5 = isset($data[5]) ? trim($data[5]) : '';
                                                    $data6 = isset($data[6]) ? trim($data[6]) : '';

                                                    $enc1 = mb_detect_encoding($data1) ? mb_detect_encoding($data1) : 'UTF-8';
                                                    $enc2 = mb_detect_encoding($data2) ? mb_detect_encoding($data2) : 'UTF-8';

                                                    $baseData[$data0][] = array(
                                                        'list_title' => sanitize_text_field(iconv($enc1, 'UTF-8//IGNORE', $data0)),
                                                        'qcopd_item_title' => sanitize_text_field(iconv($enc1, 'UTF-8//IGNORE', $data1)),
                                                        'qcopd_item_link' => esc_url_raw(iconv($enc2, 'UTF-8//IGNORE', $data2)),
                                                        'qcopd_item_img' => '',
                                                        'qcopd_item_nofollow' => sanitize_text_field($data3),
                                                        'qcopd_item_newtab' => sanitize_text_field($data4),
                                                        'qcopd_item_subtitle' => sanitize_text_field($data5),
                                                        'list_item_bg_color' => sanitize_text_field($data6)
                                                    );

                                                    $count++;
                                                }
                                            }
                                            
                                            $keyCounter = 0;
                                            $metaCounter = 0;

                                            global $wpdb;

                                            foreach ($baseData as $key => $data) {

                                                $post_arr = array(
                                                    'post_title' => trim($key),
                                                    'post_status' => 'publish',
                                                    'post_author' => get_current_user_id(),
                                                    'post_type' => 'sld',
                                                );

                                                wp_insert_post($post_arr);

                                                $newest_post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'sld' ORDER BY ID DESC LIMIT 1");

                                                foreach ($data as $k => $item) {
                                                    add_post_meta(
                                                        $newest_post_id,
                                                        'qcopd_list_item01',
                                                        array(
                                                            'qcopd_item_title' => isset($item['qcopd_item_title']) ? $item['qcopd_item_title'] : '',
                                                            'qcopd_item_link' => isset($item['qcopd_item_link']) ? $item['qcopd_item_link'] : '',
                                                            'qcopd_item_img' => '',
                                                            'qcopd_item_nofollow' => isset($item['qcopd_item_nofollow']) ? $item['qcopd_item_nofollow'] : 0,
                                                            'qcopd_item_newtab' => isset($item['qcopd_item_newtab']) ? $item['qcopd_item_newtab'] : 0,
                                                            'qcopd_item_subtitle' => isset($item['qcopd_item_subtitle']) ? $item['qcopd_item_subtitle'] : '',
                                                            'list_item_bg_color' => isset($item['list_item_bg_color']) ? $item['list_item_bg_color'] : ''
                                                        )
                                                    );

                                                    $metaCounter++;
                                                }
                            
                                                $keyCounter++;
                                            }
                            
                                            if ((isset($keyCounter) && $keyCounter > 0) && (isset($metaCounter) && $metaCounter > 0)) {
                                                echo '<div class="sld-alert-warning" style="background: #ecfdf5; border-color: #10b981; color: #065f46;"><strong>' . esc_html('RESULT:', 'simple-link-directory') . '</strong> ' . esc_attr($keyCounter) . ' ' . esc_html('entry with', 'simple-link-directory') . ' <strong>' . esc_attr($metaCounter) . '</strong> ' . esc_html('element(s) was made successfully.', 'simple-link-directory') . '</div>';
                                            }
                                            if (file_exists($movefile['file'])) {
                                                wp_delete_file($movefile['file']);
                                            }
                                        }
                                    }
                                }
                                ?>

                                <!-- Upload Form Card -->
                                <div class="sld-upload-area">
                                    <p><?php echo esc_html('Upload CSV File to Import'); ?></p>

                                    <form name="uploadfile" id="uploadfile_form" method="POST"
                                        enctype="multipart/form-data" action="" accept-charset="utf-8">

                                        <input type="file" name="csv_upload" id="csv_upload" size="35" class="uploadfiles" />
                                        
                                        <p style="color: #ef4444; font-size: 13px; font-weight: normal; margin-top: 5px; margin-bottom: 20px;">
                                            <?php echo esc_html('** CSV File & Characters must be saved with UTF-8 encoding **'); ?>
                                        </p>
                                        
                                        <input class="sld-btn-primary" type="submit" name="upload_csv" id=""
                                            value="<?php echo esc_html('Upload & Process') ?>" />
                                        
                                        <?php wp_nonce_field('qcopd_sld_import_nonce'); ?>
                                    </form>
                                </div>

                                <!-- Modernized Footer -->
                                <div class="sld-import-footer">
                                    <?php esc_html_e('Crafted By:', 'simple-link-directory'); ?> 
                                    <a href="<?php echo esc_url('http://www.quantumcloud.net'); ?>" target="_blank" rel="nofollow">
                                        <?php esc_html_e('Web Design Company', 'simple-link-directory'); ?>
                                    </a> 
                                    <?php esc_html_e('- QuantumCloud', 'simple-link-directory'); ?>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- /post-body-content -->
                </div>

            

                <?php if (function_exists('qcopd_help_render_sidebar')) {
                    qcopd_help_render_sidebar();
                } ?>

            </div>
        </div>
        <!-- /wrap -->





        <?php
    }
}

new Qcopd_BulkImportFree;
