<?php wp_enqueue_style('sld-css-simple' ); 


	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'on':'';

	if($sld_enable_rtl =='on'){
		$css = '.qcopd-list-wrapper .simple .upvote-section  {left: 10px; right:initial;}';
		wp_add_inline_style( 'sld-css-simple', $css );

	}

?>

<?php
global $wpdb;
// The Loop
if ( $list_query->have_posts() ) 
{
	
	if(get_option('sld_enable_top_part')=='on' || get_option('sld_enable_search')=='on') :
		
	 do_action('qcsld_attach_embed_btn', $shortcodeAtts);
	
	endif;

	//Directory Wrap or Container

	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'dir="rtl"':'';

	echo '<div class="qcld-main-container-style-simple"><div class="qcopd-list-wrapper" '.$sld_enable_rtl.'>
	<div id="opd-list-holder" class="qc-grid qcopd-list-holder">';

	$listId = 1;

	while ( $list_query->have_posts() ) 
	{
		$list_query->the_post();

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

		?>

		<?php if( $style == "simple" ): 
			$customcss = '';
			$customcss .= '#list-item-'.$listId .'-'. get_the_ID().'.simple ul li a{';
			if($title_font_size!=''){
				$customcss .= 'font-size:'.$title_font_size.';';
			}
			if($title_line_height!=''){
				$customcss .= 'line-height:'.$title_line_height.';';
			}
			$customcss .= '}';
			wp_add_inline_style( 'sld-css-simple', $customcss );
		?>

		<!-- Individual List Item -->
		<div id="list-item-<?php echo esc_attr($listId) .'-'. esc_attr(get_the_ID()); ?>" class="qc-grid-item qcopd-list-column opd-column-<?php echo esc_attr($column); echo " " . esc_attr($style);?> <?php echo "opd-list-id-" . esc_attr(get_the_ID()); ?>">
			<div class="qcopd-single-list">
				
				<h2>
					<?php echo esc_html(get_the_title()); ?>
				</h2>
				<ul>
					<?php 

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

						$count = 1;
						foreach( $lists as $list ) : 
					?>

					<li id="item-<?php echo get_the_ID() ."-". $count; ?>" style="<?php echo ( isset($list['list_item_bg_color']) && !empty($list['list_item_bg_color']) ) ? 'background:'. esc_attr($list['list_item_bg_color']) : ''; ?>">
						<?php 
							$item_url 	= isset( $list['qcopd_item_link'] ) ? esc_url($list['qcopd_item_link']) : '';
							$masked_url = isset( $list['qcopd_item_link'] ) ? esc_url($list['qcopd_item_link']) : '';
						?>
						<!-- List Anchor -->
						<a <?php if( $mask_url == 'on') { echo 'onclick="document.location.href = \''.$item_url.'\'; return false;"'; } ?> <?php echo (isset($list['qcopd_item_nofollow']) && $list['qcopd_item_nofollow'] == 1) ? 'rel="nofollow"' : ''; ?> href="<?php echo esc_url($masked_url); ?>"
							<?php echo (isset($list['qcopd_item_newtab']) && $list['qcopd_item_newtab'] == 1) ? 'target="_blank"' : ''; ?>>

							<!-- Image, If Present -->
							<?php if( ($list_img == "true") && isset($list['qcopd_item_img'])  && $list['qcopd_item_img'] != "" ) : ?>
								<span class="list-img">
									<?php 
										$img = wp_get_attachment_image_src($list['qcopd_item_img']);
									?>
									<img src="<?php echo ( isset($img[0]) ? esc_url($img[0]) : '' ); ?>" alt="<?php echo ( isset($list['qcopd_item_title']) ? esc_html($list['qcopd_item_title']) : '' ); ?>">
								</span>
							<?php else : ?>
								<span class="list-img">
									<img src="<?php echo esc_url( QCOPD_IMG_URL ); ?>/list-image-placeholder.png" alt="">
								</span>
							<?php endif; ?>

							<!-- Link Text -->
							<?php 
								echo ( isset($list['qcopd_item_title']) ? esc_html(trim($list['qcopd_item_title'])) : '' ); 
							?>

						</a>
						<?php if( $upvote == 'on' ) : ?>

							<!-- upvote section -->
							<div class="upvote-section">
								<span data-post-id="<?php echo esc_attr(get_the_ID()); ?>" data-item-title="<?php echo ( isset($list['qcopd_item_title']) ? esc_html(trim($list['qcopd_item_title'])) : '' ); ?>" data-item-link="<?php echo  ( isset($list['qcopd_item_link']) ? esc_url($list['qcopd_item_link']) : '' ); ?>" class="upvote-btn upvote-on">
									<i class="fa fa-thumbs-up"></i>
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
						<?php if(isset($list['qcopd_featured']) and $list['qcopd_featured']==1):?>
							<!-- featured section -->
							<div class="featured-section">
								<i class="fa fa-bolt"></i>
							</div>
							<!-- /featured section -->
						<?php endif; ?>

					</li>
					<?php $count++; endforeach; ?>
				</ul>

			</div>

		</div>
		<!-- /Individual List Item -->

		<?php endif; ?>

		<?php

		$listId++;
	}

	echo '<div class="sld-clearfix"></div>
			</div>
		<div class="sld-clearfix"></div>
	</div></div>';

}
