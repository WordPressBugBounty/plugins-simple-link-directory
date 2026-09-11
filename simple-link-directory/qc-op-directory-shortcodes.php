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

			$aTime = isset($a['qcopd_timelaps']) && !empty( $a['qcopd_timelaps'] ) ? (int)$a['qcopd_timelaps'] : 0;
			$bTime = isset($b['qcopd_timelaps']) && !empty( $b['qcopd_timelaps'] ) ? (int)$b['qcopd_timelaps'] : 0;

			if( $aTime === $bTime ){
				return 0;
			}

			return $aTime < $bTime  ? 1 : -1;

		}
	}
}

if ( ! function_exists( 'qcopd_featured_bg_is_light' ) ) {
	function qcopd_featured_bg_is_light( $hex ) {
		$hex = ltrim( (string) $hex, '#' );
		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		if ( strlen( $hex ) !== 6 || ! ctype_xdigit( $hex ) ) {
			return false;
		}
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		return ( ( 0.299 * $r ) + ( 0.587 * $g ) + ( 0.114 * $b ) ) > 150;
	}
}

if ( ! function_exists( 'qcopd_collect_featured_items' ) ) {
	function qcopd_collect_featured_items( $list_query ) {
		if ( get_option( 'sld_enable_featured_section' ) !== 'on' ) {
			return array();
		}
		if ( empty( $list_query ) || ! $list_query->have_posts() ) {
			return array();
		}

		global $wpdb;
		$featured = array();
		$seen     = array();

		while ( $list_query->have_posts() ) {
			$list_query->the_post();
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = 'qcopd_list_item01' order by `meta_id` ASC", get_the_ID() ) );
			if ( empty( $results ) ) {
				continue;
			}
			foreach ( $results as $result ) {
				$item = maybe_unserialize( $result->meta_value );
				if ( empty( $item['qcopd_featured'] ) || (int) $item['qcopd_featured'] !== 1 ) {
					continue;
				}
				$title = isset( $item['qcopd_item_title'] ) ? trim( $item['qcopd_item_title'] ) : '';
				$link  = isset( $item['qcopd_item_link'] ) ? $item['qcopd_item_link'] : '';
				$key   = $link . '|' . $title;
				if ( $key === '|' || isset( $seen[ $key ] ) ) {
					continue;
				}
				$seen[ $key ] = true;
				$featured[]   = array(
					'list'    => $item,
					'post_id' => get_the_ID(),
				);
			}
		}
		$list_query->rewind_posts();

		$limit = function_exists( 'qcopd_sanitize_featured_item_count' )
			? qcopd_sanitize_featured_item_count( get_option( 'sld_featured_item_count', 9 ) )
			: 9;
		$limit = apply_filters( 'qcopd_featured_item_count_limit', $limit, $list_query );

		$featured_items = array_slice( $featured, 0, $limit );
		return apply_filters( 'qcopd_collected_featured_items', $featured_items, $list_query, $featured );
	}
}

if ( ! function_exists( 'qcopd_get_featured_section_data' ) ) {
	function qcopd_get_featured_section_data( $list_query ) {
		$items = qcopd_collect_featured_items( $list_query );
		$items = apply_filters( 'qcopd_featured_section_items', $items, $list_query );

		if ( empty( $items ) ) {
			return apply_filters( 'qcopd_get_featured_section_data', false, $list_query );
		}
		$bg = function_exists( 'qcopd_sanitize_featured_bg_color' )
			? qcopd_sanitize_featured_bg_color( get_option( 'sld_featured_bg_color', '#0b0c0d' ) )
			: '#0b0c0d';
		$bg = apply_filters( 'qcopd_featured_section_bg_color', $bg, $items, $list_query );

		$data = array(
			'items'    => $items,
			'bg'       => $bg,
			'bg_class' => qcopd_featured_bg_is_light( $bg ) ? 'sld-featured-strip-light' : 'sld-featured-strip-dark',
			'total'    => count( $items ),
		);

		return apply_filters( 'qcopd_get_featured_section_data', $data, $list_query, $items );
	}
}

if ( ! function_exists( 'qcopd_render_featured_heading' ) ) {
	function qcopd_render_featured_heading( $total ) {
		/* translators: %s: Number of items */
		$singular = apply_filters( 'qcopd_featured_heading_singular', __( '%s item', 'simple-link-directory' ), $total );
		/* translators: %s: Number of items */
		$plural   = apply_filters( 'qcopd_featured_heading_plural', __( '%s items', 'simple-link-directory' ), $total );
		$title    = apply_filters( 'qcopd_featured_heading_title', __( 'Featured', 'simple-link-directory' ), $total );
		$pill     = apply_filters( 'qcopd_featured_heading_pill', __( 'Featured', 'simple-link-directory' ), $total );
		?>
		<div class="sld-featured-heading">
			<div class="sld-featured-heading-left">
				<h2><?php echo esc_html( $title ); ?></h2>
				<span class="sld-featured-pill"><?php echo esc_html( $pill ); ?></span>
			</div>
			<span class="sld-featured-heading-count" data-singular="<?php echo esc_attr( $singular ); ?>" data-plural="<?php echo esc_attr( $plural ); ?>" data-total="<?php echo esc_attr( $total ); ?>">
				<?php
				/* translators: %s: Number of items */
				echo esc_html( sprintf( _n( '%s item', '%s items', $total, 'simple-link-directory' ), number_format_i18n( $total ) ) );
				?> →
			</span>
		</div>
		<?php
	}
}

//For all list elements
add_shortcode('qcopd-directory', 'SLD_QCOPD_DIRectory_full_shortcode');
if ( ! function_exists( 'SLD_QCOPD_DIRectory_full_shortcode' ) ) {
	function SLD_QCOPD_DIRectory_full_shortcode( $atts = array() ){

		ob_start();
	    qcopd_show_qcopd_full_list( $atts );
	    $content = ob_get_clean();
	    return $content;
	}
}

if ( ! function_exists( 'qcopd_show_qcopd_full_list' ) ) {
function qcopd_show_qcopd_full_list( $atts = array() )
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

	$title_font_size = preg_replace('/[^a-zA-Z0-9._%\-]/', '', $title_font_size);
	$subtitle_font_size = preg_replace('/[^a-zA-Z0-9._%\-]/', '', $subtitle_font_size);
	$title_line_height = preg_replace('/[^a-zA-Z0-9._%\-]/', '', $title_line_height);
	$subtitle_line_height = preg_replace('/[^a-zA-Z0-9._%\-]/', '', $subtitle_line_height);
	$min_width = preg_replace('/[^a-zA-Z0-9._%\-]/', '', $min_width);


	// check style if empty. default simple.
	$style = ( isset( $atts["style"] ) && qcopd_get_style_for_template( $atts["style"] ) ) ? qcopd_get_style_for_template( $atts["style"] ) : $style;

	//ShortCode Atts
	$shortcodeAtts = array(
		'orderby' 				=> esc_attr($orderby),
		'order' 				=> esc_attr($order),
		'mode' 					=> esc_attr($mode),
		'list_id' 				=> esc_attr($list_id),
		'column' 				=> esc_attr($column),
		'style' 				=> esc_attr($style),
		'min_width' 			=> esc_attr($min_width),
		'list_img' 				=> esc_attr($list_img),
		'search' 				=> esc_attr($search),
		'category' 				=> esc_attr($category),
		'upvote' 				=> esc_attr($upvote),
		'item_count' 			=> esc_attr($item_count),
		'top_area' 				=> esc_attr($top_area),
		'item_orderby' 			=> esc_attr($item_orderby),
		'item_order' 			=> esc_attr($item_order),
		'mask_url' 				=> esc_attr($mask_url),
		'enable_embedding' 		=> esc_attr($enable_embedding),
		'title_font_size' 		=> esc_attr($title_font_size),
		'subtitle_font_size' 	=> esc_attr($subtitle_font_size),
		'title_line_height' 	=> esc_attr($title_line_height),
		'subtitle_line_height' 	=> esc_attr($subtitle_line_height),
		'enable_image' 			=> esc_attr($enable_image),
		'dark_mode' 			=> esc_attr($dark_mode),
	);

	
	$limit = -1;


	if( isset($min_width) && !empty($min_width) ){
		$css = '.qcopd-list-wrapper {min-width: '.$min_width.' }';
		wp_add_inline_style( 'qcopd-custom-css', $css );
	}

	$lan_enable_dark_mode 	= get_option('sld_lan_enable_dark_mode') ? get_option('sld_lan_enable_dark_mode') : __('Enable Dark Mode', 'simple-link-directory');
	$lan_dark_mode_on 		= get_option('sld_lan_dark_mode_on') ? get_option('sld_lan_dark_mode_on') : __('Dark Mode On', 'simple-link-directory');
	$lan_light_mode_on 		= get_option('sld_lan_light_mode_on') ? get_option('sld_lan_light_mode_on') : __('Light Mode On', 'simple-link-directory');

	$custom_js = 'jQuery(document).ready(function($) {

			function sld_dark_light_mode($toggle, e) {
			    
			    if (e && e.stopImmediatePropagation) {
			        e.stopImmediatePropagation();
			    }
			    $("body").addClass("sld-dark-mode").removeClass("sld-light-mode");
			    $("html").addClass("sld-dark-mode").removeClass("sld-light-mode");
			    $(".qcopd-list-wrapper").addClass("sld-dark-mode dark-mode").removeClass("sld-light-mode light-mode");
			    $(".qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\'], .qcld-sld-style-4-page").addClass("sld-dark-mode dark-mode").removeClass("sld-light-mode light-mode");

			    const $switchWrap = $toggle.closest(".sld-sld-theme-switch-wrapper");
			    const $targetWrapper = $switchWrap.nextAll(".qcld-sld-style-4-page, .qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\'], .qcopd-list-wrapper").add($switchWrap.parent());
			    const $targetWrappers = $targetWrapper.find(".qcopd-list-wrapper ul li, .filter-area, .sld-tag-filter-area, .sld-top-area, .sld-featured-strip, .sld-style-4-featured-strip");
			    const $allItems = $targetWrappers.find("div, a, p, h1, h2, h3, h4, h5, h6, span");
			    const $targetElements = $targetWrappers.add($allItems);

			    $targetElements.each(function() {
		            const $el = $(this);
		            const el = this;
		            
		            // Store previous style only if not already stored
		            if (!$el.data("previous-style")) {
		                $el.data("previous-style", $el.attr("style") || "");
		            }

		            $el.css({
		                "background-color": "#121212",
		                "color": "#ffffff"
		            });

		            // Remove border on list items/links — transparent still shows page bg as a light ring
		            if ($el.is("li, a")) {
		                el.style.setProperty("border", "none", "important");
		                el.style.setProperty("border-width", "0", "important");
		                el.style.setProperty("border-color", "#121212", "important");
		                el.style.setProperty("box-shadow", "none", "important");
		                el.style.setProperty("outline", "none", "important");
		            }

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
			    $("body").removeClass("sld-dark-mode").addClass("sld-light-mode");
			    $("html").removeClass("sld-dark-mode").addClass("sld-light-mode");
			    $(".qcopd-list-wrapper").removeClass("sld-dark-mode dark-mode").addClass("sld-light-mode light-mode");
			    $(".qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\'], .qcld-sld-style-4-page").removeClass("sld-dark-mode dark-mode").addClass("sld-light-mode light-mode");

	        	const $switchWrap = $toggle.closest(".sld-sld-theme-switch-wrapper");
	        	const $targetWrapper = $switchWrap.nextAll(".qcld-sld-style-4-page, .qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\'], .qcopd-list-wrapper").add($switchWrap.parent());
	        	const $targetWrappers = $targetWrapper.find(".qcopd-list-wrapper ul li, .qcopd-list-wrapper ul li *, .sld-top-area, .sld-top-area *, .filter-area, .filter-area *, .sld-tag-filter-area, .sld-tag-filter-area *, .sld-featured-strip, .sld-featured-strip *, .sld-style-4-featured-strip, .sld-style-4-featured-strip *");
			    $targetWrappers.each(function() {
			        const $el = $(this);
			        const oldStyle = $el.data("previous-style");
			        
			        if (oldStyle !== undefined) {
			            if (oldStyle === "") {
			                $el.removeAttr("style");
			            } else {
			                $el.attr("style", oldStyle);
			            }
			            $el.removeData("previous-style");
		        }
			    });
			}

			$(".qcopd-list-wrapper").each(function(index) {
			    const $list = $(this);
			    const uniqueId = "sld-theme-checkbox-" + index;
			    let $pageWrap = $list.closest(".qcld-sld-style-4-page");
			    let $mainContainer = $list.closest(".qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\']");
			    let $target = $pageWrap.length ? $pageWrap : ($mainContainer.length ? $mainContainer.first() : $list);

			    const $prevFeatured = $target.prev(".sld-featured-strip, .sld-style-4-featured-strip");
			    if ($prevFeatured.length) {
			        $target = $prevFeatured;
			    }
			    const $prevTopArea = $target.prev(".sld-top-area, .qcopd_embed_container");
			    if ($prevTopArea.length) {
			        $target = $prevTopArea;
			    }

			    if (!$target.prev(".sld-sld-theme-switch-wrapper").length) {
			        $target.before(`
			            <div class="sld-sld-theme-switch-wrapper">
			                <label class="sld-theme-switch" for="${uniqueId}">
			                    <input type="checkbox" id="${uniqueId}" class="sld-theme-checkbox-input" />
			                    <div class="sld-theme-slider sld-theme-round">
			                        <span class="sld-theme-icon sun">☀️</span>
			                        <span class="sld-theme-icon moon">🌙</span>
			                    </div>
			                </label>
			                <em id="sld-theme-status-${index}">'.esc_html($lan_enable_dark_mode).'</em>
			            </div>
			        `);
			    }
			});

            const $toggle = $("#sld-theme-checkbox-0");
            const $qcld_sld_tab = $(".qcld_sld_tab").length;
            const $body = $(".qcld-main-container-style-simple, [class*=\'qcld-main-container-style-\'], .qcld-sld-style-4-page, .qcopd-list-wrapper");
            const $statusText = $("#sld-theme-status-0");
            const storageKey = "user-theme-pref";
            const currentTheme = localStorage.getItem(storageKey);


            if (!$qcld_sld_tab) {

	            if (currentTheme) {
	                if (currentTheme === "dark-mode") {
	                    $body.addClass("dark-mode").removeClass("light-mode");
	                    $toggle.prop("checked", true);
	                    sld_dark_light_mode($toggle);
	                    $statusText.text("'.esc_js($lan_dark_mode_on).'");
	                } else {
	                    $body.addClass("light-mode").removeClass("dark-mode");
	                    $toggle.prop("checked", false);
	                    sld_remove_dark_mode($toggle);
	                    $statusText.text("'.esc_js($lan_light_mode_on).'");
	                }
	            } else {
	                if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
	                    $body.addClass("dark-mode").removeClass("light-mode");
	                    $toggle.prop("checked", true);
	                    sld_dark_light_mode($toggle);
	                    $statusText.text("'.esc_js($lan_dark_mode_on).'");
	                } else {
	                    $body.addClass("light-mode").removeClass("dark-mode");
	                    $toggle.prop("checked", false);
	                    sld_remove_dark_mode($toggle);
	                    $statusText.text("'.esc_js($lan_light_mode_on).'");
	                }
	            }
            }

			$(document).on("change", ".sld-theme-checkbox-input", function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
			    const $thisToggle 	= $(this);
			    const isDarkMode 	= $thisToggle.is(":checked");
			    const $targetWraps  = $thisToggle.closest(".sld-sld-theme-switch-wrapper");

			    if (isDarkMode) {
			        // APPLY DARK MODE
                    $body.addClass("dark-mode").removeClass("light-mode");
                    localStorage.setItem(storageKey, "dark-mode");
			        sld_dark_light_mode($thisToggle);
                    $targetWraps.find("em").text("'.esc_js($lan_dark_mode_on).'");
			    } else {
			        // RESTORE ORIGINAL STYLE / LIGHT MODE
                    $body.removeClass("dark-mode").addClass("light-mode");
                    localStorage.setItem(storageKey, "light-mode");
			        sld_remove_dark_mode($thisToggle);
                    $targetWraps.find("em").text("'.esc_js($lan_light_mode_on).'");
			    }
			});


            
        });';

    if( isset($dark_mode) && ($dark_mode == 'on' || $dark_mode == 'show' || $dark_mode == 'yes' ) ){

		// Apply dark-mode class ASAP so CSS hides borders before jQuery ready
		$early_dark_js = '(function(){try{if(localStorage.getItem("user-theme-pref")==="dark-mode"){document.documentElement.classList.add("sld-dark-mode");}}catch(e){}})();';
		wp_add_inline_script( 'qcopd-custom-script', $early_dark_js, 'before' );
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

    // check style if empty. default simple.
    $template_code = ( isset( $atts["style"] ) && qcopd_get_style_for_template( $atts["style"] ) ) ? qcopd_get_style_for_template( $atts["style"] ) : $style;

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
		background: url('". esc_url( SLD_QCOPD_IMG_URL )."/up-arrow.ico') no-repeat 8px 7px;
		background-size: 50%;
	}";
	wp_add_inline_style( 'qcopd-custom-css', $scrolltotop );
?>

	<a href="#"class="sld_scrollToTop"><?php esc_html_e('Scroll To Top', 'simple-link-directory'); ?></a>

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
	if ( file_exists( SLD_QCOPD_DIR ."/templates/".$template_code."/template.php" ) ) {
		$tempath = SLD_QCOPD_DIR ."/templates/".$template_code."/template.php";
	    require ( $tempath );
	}
	wp_reset_postdata();

	
	
}
}

//Add Custom Scripts and Styles to footer
if ( ! function_exists( 'qcopd_custom_styles_scripts' ) ) {
	function qcopd_custom_styles_scripts(){	

		$customCss = get_option( 'sld_custom_style' );
		if( trim($customCss) != "" ) :
			$css = trim($customCss);
			wp_add_inline_style( 'qcopd-custom-css', $css );
		endif; 

		$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'on':'';

		if($sld_enable_rtl =='on'){

			$customscript = "jQuery(document).ready(function($)
			{
				var \$grid = $('.qc-grid');
				if (typeof $.fn.packery === 'function' && \$grid.length > 0) {
					\$grid.packery({
					  itemSelector: '.qc-grid-item',
					  gutter: 10,
					  percentPosition: true,
					  originLeft: false
					});
					if (typeof $.fn.imagesLoaded === 'function') {
						\$grid.imagesLoaded().progress(function() {
							\$grid.packery('layout');
						});
					}
					$(window).on('load resize', function(){
						\$grid.packery('reloadItems').packery('layout');
					});
				}
			});";
			wp_add_inline_script( 'qcopd-custom-script', ($customscript) );
		}else{
			$customscript = "jQuery(document).ready(function($)
			{
				var \$grid = $('.qc-grid');
				if (typeof $.fn.packery === 'function' && \$grid.length > 0) {
					\$grid.packery({
					  itemSelector: '.qc-grid-item',
					  gutter: 10,
					  percentPosition: true
					});
					if (typeof $.fn.imagesLoaded === 'function') {
						\$grid.imagesLoaded().progress(function() {
							\$grid.packery('layout');
						});
					}
					$(window).on('load resize', function(){
						\$grid.packery('reloadItems').packery('layout');
					});
				}
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
add_action('wp_footer', 'qcopd_custom_styles_scripts');


if ( ! function_exists( 'qcopd_get_style_for_template' ) ) {
	function qcopd_get_style_for_template($style){

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