<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style('sld-css-style-4' ); 
	
	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'on':'';

	if($sld_enable_rtl =='on'){
		$css = '.qcopd-list-wrapper .style-4 .ca-menu li {float:none;}';
		wp_add_inline_style( 'sld-css-style-4', $css );
	}

?>


<?php
global $wpdb;
// The Loop
if ( $list_query->have_posts() ) 
{
	
	echo '<div class="qcld-sld-style-4-page">';

	if(get_option('sld_enable_top_part')=='on' || get_option('sld_enable_search')=='on') :
		
	 do_action('qcopd_sld_attach_embed_btn', $shortcodeAtts);
	
	endif;

	$sld_featured = qcopd_get_featured_section_data( $list_query );

	if ( $sld_featured ) :
		$sld_featured_icons = $sld_featured['items'];
		$sld_feat_bg        = $sld_featured['bg'];
		$sld_feat_count     = 1;
		$sld_feat_total     = $sld_featured['total'];
		?>
		<div class="qcld-main-container-style-4 sld-style-4-featured-strip" style="background: <?php echo esc_attr( $sld_feat_bg ); ?>;">
			<div class="sld-style-4-heading">
				<div class="sld-style-4-heading-left">
					<h2><?php esc_html_e( 'Featured', 'simple-link-directory' ); ?></h2>
					<span class="sld-style-4-featured-pill"><?php esc_html_e( 'Featured', 'simple-link-directory' ); ?></span>
				</div>
				<span class="sld-style-4-heading-count">
					<?php
					/* translators: %s: Number of items */
					echo esc_html( sprintf( _n( '%s item', '%s items', $sld_feat_total, 'simple-link-directory' ), number_format_i18n( $sld_feat_total ) ) );
					?> →
				</span>
			</div>
			<div class="qcopd-list-column style-4 opd-column-<?php echo esc_attr( $column ); ?>">
				<ul class="ca-menu">
				<?php foreach ( $sld_featured_icons as $sld_ficon ) :
					$list = $sld_ficon['list'];
					$feat_post_id = $sld_ficon['post_id'];

					$canContentClass = "subtitle-present";
					if ( ! isset( $list['qcopd_item_subtitle'] ) || $list['qcopd_item_subtitle'] == "" ) {
						$canContentClass = "subtitle-absent";
					}

					$li_classes = array( 'sld-item-featured' );
					if ( $upvote == 'on' ) {
						$li_classes[] = 'sld-has-upvote';
					}

					$item_bg = '';
					if ( isset( $list['list_item_bg_color'] ) && ! empty( $list['list_item_bg_color'] ) && $list['list_item_bg_color'] !== '0' ) {
						$item_bg = trim( $list['list_item_bg_color'] );
					}
					if ( $item_bg !== '' ) {
						$li_classes[] = 'sld-has-custom-bg';
						$hex = ltrim( $item_bg, '#' );
						if ( strlen( $hex ) === 3 ) {
							$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
						}
						if ( strlen( $hex ) === 6 && ctype_xdigit( $hex ) ) {
							$r = hexdec( substr( $hex, 0, 2 ) );
							$g = hexdec( substr( $hex, 2, 2 ) );
							$b = hexdec( substr( $hex, 4, 2 ) );
							if ( ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) > 150 ) {
								$li_classes[] = 'sld-bg-light';
							}
						}
					}
					$item_url   = isset( $list['qcopd_item_link'] ) ? esc_url( $list['qcopd_item_link'] ) : '';
					$masked_url = $item_url;
					?>
					<li id="item-feat-<?php echo esc_attr( $feat_post_id ) . '-' . esc_attr( $sld_feat_count ); ?>" class="<?php echo esc_attr( implode( ' ', $li_classes ) ); ?>" style="<?php echo ( $item_bg !== '' ) ? 'background:' . esc_attr( $item_bg ) . ';' : ''; ?>">
						<a <?php echo ( isset( $list['qcopd_item_nofollow'] ) && $list['qcopd_item_nofollow'] == 1 ) ? 'rel="nofollow"' : ''; ?> href="<?php echo esc_url( $masked_url ); ?>" <?php echo ( isset( $list['qcopd_item_newtab'] ) && $list['qcopd_item_newtab'] == 1 ) ? 'target="_blank"' : ''; ?>>
							<?php if ( ( $list_img == "true" ) && isset( $list['qcopd_item_img'] ) && $list['qcopd_item_img'] != "" ) : ?>
								<span class="ca-icon list-img-1">
									<?php $img = wp_get_attachment_image_src( $list['qcopd_item_img'] ); ?>
									<img src="<?php echo ( isset( $img[0] ) ? esc_url( $img[0] ) : '' ); ?>" alt="<?php echo ( isset( $list['qcopd_item_title'] ) ? esc_html( trim( $list['qcopd_item_title'] ) ) : '' ); ?>">
								</span>
							<?php else : ?>
								<span class="ca-icon list-img-1">
									<img src="<?php echo esc_url( SLD_QCOPD_IMG_URL ); ?>/list-image-placeholder.png" alt="">
								</span>
							<?php endif; ?>
							<div class="ca-content">
								<h3 class="ca-main <?php echo esc_attr( $canContentClass ); ?>">
									<span class="ca-main-text">
										<?php echo ( isset( $list['qcopd_item_title'] ) ? esc_html( trim( $list['qcopd_item_title'] ) ) : '' ); ?>
									</span>
									<span class="sld-style-4-featured-icon" data-tooltip="<?php echo esc_attr__( 'Featured', 'simple-link-directory' ); ?>" aria-label="<?php echo esc_attr__( 'Featured', 'simple-link-directory' ); ?>">
										<i class="fa fa-star" aria-hidden="true"></i>
									</span>
								</h3>
								<?php if ( isset( $list['qcopd_item_subtitle'] ) ) : ?>
									<p class="ca-sub"><?php echo esc_html( trim( $list['qcopd_item_subtitle'] ) ); ?></p>
								<?php endif; ?>
							</div>
						</a>
						<?php if ( $upvote == 'on' ) : ?>
							<div class="upvote-section style-4-upvote-section">
								<span data-post-id="<?php echo esc_attr( $feat_post_id ); ?>" data-item-title="<?php echo ( isset( $list['qcopd_item_title'] ) ? esc_attr( trim( $list['qcopd_item_title'] ) ) : '' ); ?>" data-item-link="<?php echo ( isset( $list['qcopd_item_link'] ) ? esc_url( $list['qcopd_item_link'] ) : '' ); ?>" class="upvote-btn upvote-on">
									<i class="fa fa-heart-o"></i>
								</span>
								<span class="upvote-count">
									<?php
									if ( isset( $list['qcopd_upvote_count'] ) && (int) $list['qcopd_upvote_count'] > 0 ) {
										echo (int) $list['qcopd_upvote_count'];
									}
									?>
								</span>
							</div>
						<?php endif; ?>
						<div class="featured-section">
							<span><?php esc_html_e( 'Featured', 'simple-link-directory' ); ?></span>
						</div>
					</li>
					<?php $sld_feat_count++; endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	endif;


	//Directory Wrap or Container
	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'dir="rtl"':'';

	echo '<div class="qcld-main-container-style-4"><div class="qcopd-list-wrapper" '.wp_kses_post($sld_enable_rtl).'><div id="opd-list-holder" class="qc-grid qcopd-list-holder">';

	$listId = 1;

	while ( $list_query->have_posts() ) 
	{
		$list_query->the_post();

		//$lists = get_post_meta( get_the_ID(), 'qcopd_list_item01' );
		
		$lists = array();
		//$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = ".get_the_ID()." AND meta_key = 'qcopd_list_item01' order by `meta_id` ASC");
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = 'qcopd_list_item01' order by `meta_id` ASC", get_the_ID() ) );
		if(!empty($results)){
			foreach($results as $result){
				$unserialize = maybe_unserialize($result->meta_value);
				$lists[] = $unserialize;
			}
		}

		$conf = get_post_meta( get_the_ID(), 'qcopd_list_conf', true );
		
		

		if( $item_orderby == 'title' )
		{
			usort($lists, "sld_custom_sort_by_tpl_title");
		}
		if( $item_orderby == 'upvotes' )
		{
			usort($lists, "sld_custom_sort_by_tpl_upvotes");
		}
		if( $item_orderby == 'timestamp' )
		{
			usort($lists, "sld_custom_sort_by_tpl_timestamp");
		}

		
		$customcss = '';
		$customcss .= '#qcopd-list-'.$listId .'-'. get_the_ID().'.style-4 .ca-menu li .ca-main {';
		if($title_font_size!=''){
			$customcss .= 'font-size:'.$title_font_size.' !important;';
		}
		if($title_line_height!=''){
			$customcss .= 'line-height:'.$title_line_height.' !important;';
		}
		$customcss .= '}';
		$customcss .= '#qcopd-list-'. $listId .'-'. get_the_ID().'.style-4 .ca-menu li .ca-sub {';
		if($subtitle_font_size!=''){
			$customcss .= 'font-size:'. $subtitle_font_size.' !important;';
		}
		if($subtitle_line_height!=''){
			$customcss .= 'line-height:'. $subtitle_line_height.'!important;';
		}
		$customcss .= '}';
		wp_add_inline_style( 'sld-css-style-4', $customcss );

		$sld_list_item_count = is_array( $lists ) ? count( $lists ) : 0;
		$sld_list_cats = get_the_terms( get_the_ID(), 'sld_cat' );
		$sld_list_cat_label = '';
		if ( ! empty( $sld_list_cats ) && ! is_wp_error( $sld_list_cats ) ) {
			$sld_list_cat_label = implode( ' · ', wp_list_pluck( $sld_list_cats, 'name' ) );
		}
		?>
        
		<div class="list-and-add qc-grid-item <?php echo "opd-list-id-" . esc_attr(get_the_ID()); ?>">

		<div id="qcopd-list-<?php echo esc_attr($listId) .'-'. esc_attr(get_the_ID()); ?>" class="qcopd-list-column <?php echo esc_attr($style); ?> opd-column-<?php echo esc_attr($column); ?>">

			<div class="qcopd-single-list-1">
				<div class="sld-style-4-heading">
					<div class="sld-style-4-heading-left">
						<h2>
							<?php echo esc_html(get_the_title()); ?>
						</h2>
						<?php if ( $sld_list_cat_label !== '' ) : ?>
							<span class="sld-style-4-heading-sub"><?php echo esc_html( $sld_list_cat_label ); ?></span>
						<?php endif; ?>
					</div>
					<span class="sld-style-4-heading-count">
						<?php
						/* translators: %s: Number of items */
						echo esc_html( sprintf( _n( '%s item', '%s items', $sld_list_item_count, 'simple-link-directory' ), number_format_i18n( $sld_list_item_count ) ) );
						?> →
					</span>
				</div>
				<ul class="ca-menu">
					<?php $count = 1; 
					
					?>
					<?php foreach( $lists as $list ) : ?>
					<?php 
						$canContentClass = "subtitle-present";

						if( !isset($list['qcopd_item_subtitle']) || $list['qcopd_item_subtitle'] == "" )
						{
							$canContentClass = "subtitle-absent";
						}

						$is_featured = ( isset($list['qcopd_featured']) && $list['qcopd_featured'] == 1 );
						$li_classes = array();
						if ( $is_featured ) {
							$li_classes[] = 'sld-item-featured';
						}
						if ( $upvote == 'on' ) {
							$li_classes[] = 'sld-has-upvote';
						}

						$item_bg = '';
						if ( isset($list['list_item_bg_color']) && ! empty($list['list_item_bg_color']) && $list['list_item_bg_color'] !== '0' ) {
							$item_bg = trim( $list['list_item_bg_color'] );
						}
						if ( $item_bg !== '' ) {
							$li_classes[] = 'sld-has-custom-bg';
							$hex = ltrim( $item_bg, '#' );
							if ( strlen( $hex ) === 3 ) {
								$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
							}
							if ( strlen( $hex ) === 6 && ctype_xdigit( $hex ) ) {
								$r = hexdec( substr( $hex, 0, 2 ) );
								$g = hexdec( substr( $hex, 2, 2 ) );
								$b = hexdec( substr( $hex, 4, 2 ) );
								if ( ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) > 150 ) {
									$li_classes[] = 'sld-bg-light';
								}
							}
						}
					?>
					<li id="item-<?php echo esc_attr(get_the_ID()) ."-". esc_attr($count); ?>" class="<?php echo esc_attr( implode( ' ', $li_classes ) ); ?>" style="<?php echo ( $item_bg !== '' ) ? 'background:'. esc_attr( $item_bg ) .';' : ''; ?>">
						<?php 
							$item_url 	= isset( $list['qcopd_item_link'] ) ? esc_url($list['qcopd_item_link']) : '';
							$masked_url = isset( $list['qcopd_item_link'] ) ? esc_url($list['qcopd_item_link']) : '';
						?>
						<!-- List Anchor -->
						<a <?php echo (isset($list['qcopd_item_nofollow']) && $list['qcopd_item_nofollow'] == 1) ? 'rel="nofollow"' : ''; ?> href="<?php echo esc_url($masked_url); ?>" <?php echo (isset($list['qcopd_item_newtab']) && $list['qcopd_item_newtab'] == 1) ? 'target="_blank"' : ''; ?>>

							<!-- Image, If Present -->
							<?php if( ($list_img == "true") && isset($list['qcopd_item_img'])  && $list['qcopd_item_img'] != "" ) : ?>
								<span class="ca-icon list-img-1">
									<?php 
										$img = wp_get_attachment_image_src($list['qcopd_item_img']);
									?>
									<img src="<?php echo ( isset($img[0]) ? esc_url($img[0]) : '' ); ?>" alt="<?php echo ( isset($list['qcopd_item_title']) ? esc_html(trim($list['qcopd_item_title'])) : '' ); ?>">
								</span>
							<?php else : ?>
								<span class="ca-icon list-img-1">
									<img src="<?php echo esc_url( SLD_QCOPD_IMG_URL ); ?>/list-image-placeholder.png" alt="">
								</span>
							<?php endif; ?>

							<!-- Link Text -->
							<div class="ca-content">
                                <h3 class="ca-main <?php echo esc_attr($canContentClass); ?>">
								<span class="ca-main-text">
								<?php 
									echo ( isset($list['qcopd_item_title']) ? esc_html(trim($list['qcopd_item_title'])) : '' ); 
								?>
								</span>
								<?php if ( $is_featured ) : ?>
									<span class="sld-style-4-featured-icon" data-tooltip="<?php echo esc_attr__( 'Featured', 'simple-link-directory' ); ?>" aria-label="<?php echo esc_attr__( 'Featured', 'simple-link-directory' ); ?>">
										<i class="fa fa-star" aria-hidden="true"></i>
									</span>
								<?php endif; ?>
                                </h3>
                                <?php if( isset($list['qcopd_item_subtitle']) ) : ?>
	                                <p class="ca-sub">
	                                <?php 
										echo esc_html(trim($list['qcopd_item_subtitle'])); 
									?>
	                                </p>
	                            <?php endif; ?>

                            </div>

						</a>
						<?php if( $upvote == 'on' ) : ?>

							<!-- upvote section -->
							<div class="upvote-section style-4-upvote-section">
								<span data-post-id="<?php echo esc_attr(get_the_ID()); ?>" data-item-title="<?php echo ( isset($list['qcopd_item_title']) ? esc_attr(trim($list['qcopd_item_title'])) : '' ); ?>" data-item-link="<?php echo ( isset($list['qcopd_item_link']) ? esc_url($list['qcopd_item_link']) : '' ); ?>" class="upvote-btn upvote-on">
									<i class="fa fa-heart-o"></i>
								</span>
								<span class="upvote-count">
									<?php
									  if( isset($list['qcopd_upvote_count']) && (int)$list['qcopd_upvote_count'] > 0 ){
									  	echo (int)$list['qcopd_upvote_count'];
									  }
									?>
								</span>
							</div>
							<!-- /upvote section -->

						<?php endif; ?>
						
						<?php if($is_featured):?>
							<!-- featured section -->
							<div class="featured-section">
								<span><?php esc_html_e('Featured', 'simple-link-directory'); ?></span>
							</div>
							<!-- /featured section -->
						<?php endif; ?>

					</li>
					<?php $count++; endforeach; ?>
				</ul>
				
			</div>
		</div>

		</div>

		<?php

		$listId++;
	}

	echo '<div class="sld-clearfix"></div>
			</div>
		<div class="sld-clearfix"></div>
	</div></div></div>';

}
