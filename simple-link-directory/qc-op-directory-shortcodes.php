<?php

defined('ABSPATH') or die("No direct script access!");

/*Custom Item Sort Logic*/
if ( ! function_exists( 'sld_custom_sort_by_tpl_title' ) ) {
	function sld_custom_sort_by_tpl_title($a, $b) {
	    //return $a['qcopd_item_title'] > $b['qcopd_item_title'];
		if( isset($a['qcopd_item_title']) && isset($b['qcopd_item_title']) ){
			return strnatcasecmp(trim($a['qcopd_item_title']), trim($b['qcopd_item_title']));
		}

	}
}

if ( ! function_exists( 'sld_custom_sort_by_tpl_upvotes' ) ) {
	function sld_custom_sort_by_tpl_upvotes($a, $b) {
	   // return @($a['qcopd_upvote_count'] * 1 < $b['qcopd_upvote_count'] * 1);

		$a_qcopd_upvote_count = isset($a['qcopd_upvote_count']) && !empty( $a['qcopd_upvote_count'] ) ? (int)$a['qcopd_upvote_count'] : 0;
		$b_qcopd_upvote_count = isset($b['qcopd_upvote_count']) && !empty( $b['qcopd_upvote_count'] ) ? (int)$b['qcopd_upvote_count'] : 0;

		if( $a_qcopd_upvote_count === $b_qcopd_upvote_count ){
			return 0;
		}

		return $a_qcopd_upvote_count < $b_qcopd_upvote_count  ? 1 : -1;


	}
}

if ( ! function_exists( 'sld_custom_sort_by_tpl_timestamp' ) ) {
	function sld_custom_sort_by_tpl_timestamp($a, $b) {
		if( isset($a['qcopd_timelaps']) && isset($b['qcopd_timelaps']) ){

			// $aTime = (int)$a['qcopd_timelaps'];
			// $bTime = (int)$b['qcopd_timelaps'];
			// return $aTime < $bTime;

			$aTime = isset($a['qcopd_timelaps']) && !empty( $a['qcopd_timelaps'] ) ? (int)$a['qcopd_timelaps'] : 0;
			$bTime = isset($b['qcopd_timelaps']) && !empty( $b['qcopd_timelaps'] ) ? (int)$b['qcopd_timelaps'] : 0;

			if( $aTime === $bTime ){
				return 0;
			}

			return $aTime < $bTime  ? 1 : -1;



		}
	}
}

//For all list elements
add_shortcode('qcopd-directory', 'qcopd_directory_full_shortcode');
if ( ! function_exists( 'qcopd_directory_full_shortcode' ) ) {
	function qcopd_directory_full_shortcode( $atts = array() ){

		ob_start();
	    show_qcopd_full_list( $atts );
	    $content = ob_get_clean();
	    return $content;
	}
}

if ( ! function_exists( 'show_qcopd_full_list' ) ) {
function show_qcopd_full_list( $atts = array() )
{

	// if (is_admin()) {
 //        return;
 //    }


	wp_enqueue_script('sld-packery-script');
	wp_enqueue_script('qcopd-custom-script');
	wp_enqueue_script('qcopd-embed-form-script');
	wp_enqueue_style('qcsld-fa-css');
	wp_enqueue_style('qcopd-custom-css');
	wp_enqueue_style('qcopd-custom-rwd-css');
	wp_enqueue_style('qcopd-embed-form-css');
	
	$template_code = "";

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' 				=> 'menu_order',
			'order' 				=> 'ASC',
			'mode' 					=> 'all',
			'list_id' 				=> '',
			'column' 				=> '1',
			'style' 				=> 'simple',
			'min_width' 			=> '',
			'list_img' 				=> 'true',
			'search' 				=> '',
			'category' 				=> "",
			'upvote' 				=> "off",
			'item_count' 			=> "on",
			'top_area' 				=> "on",
			'item_orderby' 			=> "",
			'item_order' 			=> "",
			'mask_url' 				=> "off",
			'enable_embedding' 		=> '',
			'title_font_size' 		=> '',
			'subtitle_font_size' 	=> '',
			'title_line_height' 	=> '',
			'subtitle_line_height' 	=> '',
			'enable_image' 			=> '',
			'dark_mode' 			=> get_option('sld_enable_dark_mode')
		), $atts
	));

	// check style if empty. default simple.
	$style = ( isset( $atts["style"] ) && get_style_for_template( $atts["style"] ) ) ? get_style_for_template( $atts["style"] ) : $style;

	//ShortCode Atts
	$shortcodeAtts = array(
		'orderby' 				=> $orderby,
		'order' 				=> $order,
		'mode' 					=> $mode,
		'list_id' 				=> $list_id,
		'column' 				=> $column,
		'style' 				=> $style,
		'min_width' 			=> $min_width,
		'list_img' 				=> $list_img,
		'search' 				=> $search,
		'category' 				=> $category,
		'upvote' 				=> $upvote,
		'item_count' 			=> $item_count,
		'top_area' 				=> $top_area,
		'item_orderby' 			=> $item_orderby,
		'item_order' 			=> $item_order,
		'mask_url' 				=> $mask_url,
		'enable_embedding' 		=> $enable_embedding,
		'title_font_size' 		=> $title_font_size,
		'subtitle_font_size' 	=> $subtitle_font_size,
		'title_line_height' 	=> $title_line_height,
		'subtitle_line_height' 	=> $subtitle_line_height,
		'enable_image' 			=> $enable_image,
		'dark_mode' 			=> $dark_mode,
	);
	
	$limit = -1;


	if( isset($min_width) && !empty($min_width) ){
		$css = '.qcopd-list-wrapper {min-width: '.$min_width.' }';
		wp_add_inline_style( 'qcopd-custom-css', $css );
	}

	$lan_enable_dark_mode 	= get_option('sld_lan_enable_dark_mode') ? get_option('sld_lan_enable_dark_mode') : 'Enable Dark Mode';
	$lan_dark_mode_on 		= get_option('sld_lan_dark_mode_on') ? get_option('sld_lan_dark_mode_on') : 'Dark Mode On';
	$lan_light_mode_on 		= get_option('sld_lan_light_mode_on') ? get_option('sld_lan_light_mode_on') : 'Light Mode On';

	$custom_js = 'jQuery(document).ready(function($) {

			function sld_dark_light_mode($toggle, e) {
			    
			    if (e && e.stopImmediatePropagation) {
			        e.stopImmediatePropagation();
			    }
			    const $targetWrapper = $toggle.closest(".sld-sld-theme-switch-wrapper").parent().parent();
			    const $targetWrappers = $targetWrapper.find(".qcopd-list-wrapper ul li, .filter-area, .sld-tag-filter-area, .sld-top-area");
			    const $allItems = $targetWrappers.find("div, a, p, h1, h2, h3, h4, h5, h6, span");
			    const $targetElements = $targetWrappers.add($allItems);

			    $targetElements.each(function() {
		            const $el = $(this);
		            
		            // Store previous style only if not already stored
		            if (!$el.data("previous-style")) {
		                $el.data("previous-style", $el.attr("style") || "");
		            }

		            $el.css({
		                "background-color": "#121212",
		                "color": "#ffffff",
		                "border-color": "#333"
		            });

		            // Ensure text headers and links are white
		            if ($el.is("a,h3 span, h1, h2, h3, h4, h5, h6")) {
		                $el.css("color", "#ffffff");
		            }
		        });
			}

			function sld_remove_dark_mode($toggle, e) {
			    if (e && e.stopImmediatePropagation) {
			        e.stopImmediatePropagation();
			    }
	        	const $targetWrapper 	= $toggle.closest(".sld-sld-theme-switch-wrapper").parent().parent();
	        	const $targetWrappers 	= $targetWrapper.find(".qcopd-list-wrapper ul li, .qcopd-list-wrapper ul li *, .sld-top-area, .sld-top-area *, .filter-area, .filter-area *, .sld-tag-filter-area, .sld-tag-filter-area *");
			    $targetWrappers.each(function() {
			        const $el = $(this);
			        const oldStyle = $el.data("previous-style");
			        
			        if (oldStyle !== undefined) {
			            if (oldStyle === "") {
			                $el.removeAttr("style");
			            } else {
			                $el.attr("style", oldStyle);
			            }
			        }
			    });
			}

			$(".qcopd-list-wrapper").each(function(index) {
			    const $list = $(this);
			    const uniqueId = "sld-theme-checkbox-" + index;
			    if (!$list.prev(".sld-sld-theme-switch-wrapper").length) {
			        $list.before(`
			            <div class="sld-sld-theme-switch-wrapper">
			                <label class="sld-theme-switch" for="${uniqueId}">
			                    <input type="checkbox" id="${uniqueId}" class="sld-theme-checkbox-input" />
			                    <div class="sld-theme-slider sld-theme-round">
			                        <span class="sld-theme-icon sun">☀️</span>
			                        <span class="sld-theme-icon moon">🌙</span>
			                    </div>
			                </label>
			                <em id="sld-theme-status-${index}">'.$lan_enable_dark_mode.'</em>
			            </div>
			        `);
			    }
			});

            const $toggle = $("#sld-theme-checkbox-0");
            const $qcld_sld_tab = $(".qcld_sld_tab").length;
            const $body = $(".sld-main-style-simple");
            const $statusText = $("#sld-theme-status-0");
            const storageKey = "user-theme-pref";
            const currentTheme = localStorage.getItem(storageKey);


            if (!$qcld_sld_tab) {

	            if (currentTheme) {
	                $body.addClass(currentTheme);
	                if (currentTheme === "dark-mode") {
	                    $toggle.prop("checked", true);
	                    sld_dark_light_mode($toggle);
	                    $statusText.text("'.$lan_dark_mode_on.'");
	                }
	            } else {
	                if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
	                    $body.addClass("dark-mode");
	                    $toggle.prop("checked", true);
	                    sld_remove_dark_mode($toggle);
	                    $statusText.text("'.$lan_light_mode_on.'");
	                }
	            }
            }

			$(document).on("change", ".sld-theme-checkbox-input", function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
			    const $thisToggle 	= $(this);
			    const isDarkMode 	= $thisToggle.is(":checked");
			    const $targetWraps = $toggle.closest(".sld-sld-theme-switch-wrapper");

			    if (isDarkMode) {
			        // APPLY DARK MODE
                    $body.addClass("dark-mode");
                    localStorage.setItem(storageKey, "dark-mode");
			        sld_dark_light_mode($thisToggle);
                    $targetWraps.find("em").text("'.$lan_dark_mode_on.'");
			    } else {
			        // RESTORE ORIGINAL STYLE
                    $body.removeClass("dark-mode");
                    localStorage.setItem(storageKey, "light-mode");
			        sld_remove_dark_mode($thisToggle);
                    $targetWraps.find("em").text("'.$lan_light_mode_on.'");
			    }
			});


            
        });';

    if( isset($dark_mode) && ($dark_mode == 'on' || $dark_mode == 'show' || $dark_mode == 'yes' ) ){

		wp_add_inline_script( 'qcopd-custom-script', $custom_js, 'after' );
    }

	if( $mode == 'one' )
	{
		$limit = 1;	
	}

	if($orderby=='menu_order'){
		$orderby = $orderby.' title';
	}
	
	//Query Parameters
	$list_args = array(
		'post_type' 		=> 'sld',
		'post_status' 		=> 'publish',
		'has_password' 		=> false,
		'posts_per_page' 	=> $limit,
	);
	if($orderby!='none' or $order!='none'){
		$list_args['orderby'] 	= $orderby;
		$list_args['order'] 	= $order;
	}
	

	if( $list_id != "" && $mode == 'one' )
	{
		$list_args = array_merge($list_args, array( 'p' => $list_id ));
	}
	
	if( $category != "" )
	{
		$taxArray = array(
			array(
				'taxonomy' => 'sld_cat',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
		
		$list_args = array_merge($list_args, array( 'tax_query' => $taxArray ));
		
	}
	
	if(get_option('sld_enable_upvote')=='on'){
		$upvote = 'on';
	}
	// The Query
	$list_query = new WP_Query( $list_args );
	
    /*
    if ( isset($atts["style"]) && $atts["style"] )
        $template_code = $atts["style"];

    if (!$template_code)
        $template_code = "simple";
    */

    // check style if empty. default simple.
    $template_code = ( isset( $atts["style"] ) && get_style_for_template( $atts["style"] ) ) ? get_style_for_template( $atts["style"] ) : $style;

    if( $mode == 'one' ){
    	$column = '1';
    }

?>

<?php if(get_option('sld_enable_scroll_to_top')=='on'): 
	$scrolltotop = ".sld_scrollToTop{
		width: 30px;
		height: 30px;
		padding: 10px !important;
		text-align: center;
		font-weight: bold;
		color: #444;
		text-decoration: none;
		position: fixed;
		top: 88%;
		right: 29px;
		display: none;
		background-size: 20px 20px;
		text-indent: -99999999px;
		background-color: #ddd;
		border-radius: 3px;
		z-index:9999999999;
		box-sizing: border-box;
		background: url('". esc_url( QCOPD_IMG_URL )."/up-arrow.ico') no-repeat 8px 7px;
		background-size: 50%;
	}";
	wp_add_inline_style( 'qcopd-custom-css', $scrolltotop );
?>

	<a href="#"class="sld_scrollToTop">Scroll To Top</a>

<?php 
	$scrolljs = "jQuery(document).ready(function($){
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.sld_scrollToTop').fadeIn();
		} else {
			$('.sld_scrollToTop').fadeOut();
		}
	});

	//Click event to scroll to top
	$('.sld_scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});


	})";
	wp_add_inline_script( 'qcopd-custom-script', ($scrolljs) );

	endif;

	wp_enqueue_style('sld-css-style-1' );
	
	echo '<!--  Starting Simple Link Directory Plugin Output -->';
	if ( file_exists( QCOPD_DIR ."/templates/".$template_code."/template.php" ) ) {
		$tempath = QCOPD_DIR ."/templates/".$template_code."/template.php";
	    require ( $tempath );
	}
	wp_reset_query();

	
	
}
}

//Add Custom Scripts and Styles to footer
if ( ! function_exists( 'qc_opd_custom_styles_scripts' ) ) {
	function qc_opd_custom_styles_scripts(){	

		$customCss = get_option( 'sld_custom_style' );
		if( trim($customCss) != "" ) :
			$css = trim($customCss);
			wp_add_inline_style( 'qcopd-custom-css', $css );
		endif; 

		$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'on':'';

		if($sld_enable_rtl =='on'){

			$customscript = "jQuery(window).on('load',function()
			{
				jQuery('.qc-grid').packery({
				  itemSelector: '.qc-grid-item',
				  gutter: 10,
				  originLeft: false
				});
			});";
			wp_add_inline_script( 'qcopd-custom-script', ($customscript) );
		}else{
			$customscript = "jQuery(window).on('load',function()
			{
				jQuery('.qc-grid').packery({
				  itemSelector: '.qc-grid-item',
				  gutter: 10
				});
			});";
			wp_add_inline_script( 'qcopd-custom-script', ($customscript) );
		}

		$customjs = get_option( 'sld_custom_js' );
		if(trim($customjs)!=''){
			
			$customjs = trim($customjs);
			wp_add_inline_script( 'qcopd-custom-script', ($customjs) );
		}
	}
}
add_action('wp_footer', 'qc_opd_custom_styles_scripts');


if ( ! function_exists( 'get_style_for_template' ) ) {
	function get_style_for_template($style){

		if($style == 'simple'){
			return $style;
		}else if($style == 'style-1'){
			return $style;
		}else if($style == 'style-2'){
			return $style;
		}else if($style == 'style-3'){
			return $style;
		}else if($style == 'style-4'){
			return $style;
		}else if($style == 'style-5'){
			return $style;
		}else if($style == 'style-16'){
			return $style;
		}

		return 'simple';

	}
}