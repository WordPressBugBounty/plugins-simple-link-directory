<?php


global $woocommerce, $wp_scripts;
$suffix = defined('sld_SCRIPT_DEBUG') && sld_SCRIPT_DEBUG ? '' : '.min';

wp_register_style('qlcd-sld-admin-style', sld_addon_url . 'css/admin-style.css', '', '', 'screen');
wp_enqueue_style('qlcd-sld-admin-style');

wp_enqueue_script('jquery');

wp_register_style('qcld-sld-bootcampqc-css', sld_addon_url . 'css/bootstrap.min.css', '', '', 'screen');
wp_enqueue_style('qcld-sld-bootcampqc-css');



?>
<div class="wrap sld-dashboard-wrap">
    <h1 class="wpbot_header_h1" style="color: #fff !important;">
        <?php echo esc_html('Simple Link Directory', 'simple-link-directory'); ?>
    </h1>

    <div class="sld-row">
        <div class="sld-col-md-9 sld-main-column">
            <div class="wp-chatbot-wrap  ">
                <div class="wpbot_dashboard_header">
                    <h3 style="color: #333 !important;"><?php echo esc_html('Simple Link Directory', 'simple-link-directory'); ?>
                    </h3>
                </div>

                <div class="sld-container">
                    <div class="wpbot_addons_section">
                        <div class="wpbot_single_addon_wrapper qc-display-flex qc-justify-center qc-flex-wrap kbx_pb_0">
                            <h2 class="wpbot_single_addon_title"><a
                                    href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>"
                                    target="_blank"
                                    rel="nofollow"><?php echo esc_html('Simple Link Directory Pro', 'simple-link-directory'); ?></a>
                                <?php echo esc_html(' Addons', 'simple-link-directory'); ?></h2>

                            <div class="wpbot_single_addon kbx-center-addon">
                                <div class="wpbot_single_content">
                                    <div class="wpbot_addon_image">
                                        <img src="<?php echo esc_url(sld_addon_url . 'images/multi-page-addon.png'); ?>"
                                            title="" />
                                    </div>
                                    <div class="wpbot_addon_content">
                                        <div class="wpbot_addon_title">
                                            <?php echo esc_html('MultiPage Advanced Addon', 'simple-link-directory'); ?>
                                        </div>
                                        <div class="wpbot_addon_details">

                                            <?php
                                            if (is_plugin_active('multi-page-advanced-addon/multi-page-advanced-addon.php')) {
                                                echo '<span class="wp_addon_installed">Installed</span>';
                                            } else {
                                                echo '<span class="wp_addon_notinstalled">Not Installed</span>';
                                            }
                                            ?>
                                            <p><?php esc_html_e('Global Search, Pagination, Category view with Lists on home page.', 'simple-link-directory'); ?>
                                            </p>
                                            <a class="button button-secondary"
                                                href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory-addons/'); ?>"
                                                target="_blank"><?php echo esc_html('Get It Now', 'simple-link-directory'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wpbot_single_addon kbx-center-addon">
                                <div class="wpbot_single_content">
                                    <div class="wpbot_addon_image">
                                        <img src="<?php echo esc_url(sld_addon_url . 'images/link-checker.png'); ?>"
                                            title="" />
                                    </div>
                                    <div class="wpbot_addon_content">
                                        <div class="wpbot_addon_title">
                                            <?php echo esc_html('Directory Broken Link Checker', 'simple-link-directory'); ?>
                                        </div>
                                        <div class="wpbot_addon_details">
                                            <?php
                                            if (is_plugin_active('qc-broken-link-checker/qc-directory-broken-link-checker.php')) {
                                                echo '<span class="wp_addon_installed">Installed</span>';
                                            } else {
                                                echo '<span class="wp_addon_notinstalled">Not Installed</span>';
                                            }
                                            ?>
                                            <p><?php esc_html_e('Check Broken Links for SLD and SBD and other Post Types Links', 'simple-link-directory'); ?>
                                            </p>
                                            <a class="button button-secondary"
                                                href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory-addons/'); ?>"
                                                target="_blank"><?php echo esc_html('Get It Now', 'simple-link-directory'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wpbot_single_addon kbx-center-addon">
                                <div class="wpbot_single_content">
                                    <div class="wpbot_addon_image">
                                        <img src="<?php echo esc_url(sld_addon_url . 'images/rating-addon-logo.png'); ?>"
                                            title="" />
                                    </div>
                                    <div class="wpbot_addon_content">
                                        <div class="wpbot_addon_title">
                                            <?php echo esc_html('Review, Rating for SLD Pro', 'simple-link-directory'); ?>
                                        </div>
                                        <div class="wpbot_addon_details">
                                            <?php
                                            if (is_plugin_active('sld-rating-review-addon/sld-rating-review.php')) {
                                                echo '<span class="wp_addon_installed">Installed</span>';
                                            } else {
                                                echo '<span class="wp_addon_notinstalled">Not Installed</span>';
                                            }
                                            ?>
                                            <p><?php esc_html_e('Allow your site users to leave a review comment and rate the link listings.', 'simple-link-directory'); ?>
                                            </p>
                                            <a class="button button-secondary"
                                                href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory-addons/'); ?>"
                                                target="_blank"><?php echo esc_html('Get It Now', 'simple-link-directory'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wpbot_single_addon kbx-center-addon">
                                <div class="wpbot_single_content">
                                    <div class="wpbot_addon_image">
                                        <img src="<?php echo esc_url(sld_addon_url . 'images/link-exchange.png'); ?>"
                                            title="" />
                                    </div>
                                    <div class="wpbot_addon_content">
                                        <div class="wpbot_addon_title">
                                            <?php echo esc_html('Link Exchange AddOn for SLD Pro', 'simple-link-directory'); ?>
                                        </div>
                                        <div class="wpbot_addon_details">
                                            <?php
                                            if (is_plugin_active('link-exchange-addon/qcld-link-exchange-main.php')) {
                                                echo '<span class="wp_addon_installed">Installed</span>';
                                            } else {
                                                echo '<span class="wp_addon_notinstalled">Not Installed</span>';
                                            }
                                            ?>
                                            <p><?php esc_html_e('Allow your site users Exchange Links with Other Websites', 'simple-link-directory'); ?>
                                            </p>
                                            <a class="button button-secondary"
                                                href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory-addons/'); ?>"
                                                target="_blank"><?php echo esc_html('Get It Now', 'simple-link-directory'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>


                        <div class="wpbot_single_addon_wrapper">
                            <h2 class="wpbot_single_addon_title"><?php echo esc_html('Themes', 'simple-link-directory'); ?></h2>
                            <div class="wpbot_single_addon kbx-center-addon qc_addon_page_full_addon">
                                <div class="wpbot_single_content">
                                    <div class="wpbot_addon_image qc_addon_page_full_img">
                                        <img src="<?php echo esc_url(sld_addon_url . 'images/theme.jpg'); ?>"
                                            title="" />
                                    </div>
                                    <div class="wpbot_addon_content">
                                        <div class="wpbot_addon_title">
                                            <?php esc_html_e('Simple Link Directory Theme', 'simple-link-directory'); ?>
                                        </div>
                                        <div class="wpbot_addon_details">
                                            <span
                                                class="wp_addon_installed"><?php esc_html_e('Not Installed', 'simple-link-directory'); ?></span>
                                            <p><?php esc_html_e('Crafted carefully to make the best out of the popular Simple Link Directory plugin. One Click Install, Demo Data, Compatible with the Elementor and the Gutenberg Page Builder!', 'simple-link-directory'); ?>
                                            </p>
                                            <a class="button button-secondary"
                                                href="https://www.quantumcloud.com/products/themes/simple-link-directory/"
                                                target="_blank"><?php esc_html_e('Get It Now', 'simple-link-directory'); ?></a>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div style="clear:both"></div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
        <?php if (function_exists('qcopd_help_render_sidebar')) {
            qcopd_help_render_sidebar();
        } ?>
    </div>
</div>