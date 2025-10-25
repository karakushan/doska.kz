<?php 
//directorypress_renderMessages();
global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS;
$layoutpost_id = directorypress_global_get_post_id();

$layout = get_post_meta($layoutpost_id, '_layout', true );
$padding = get_post_meta($layoutpost_id, '_padding', true );
global $post, $directorypress_dynamic_styles;
$directorypress_styles = '';
$id = uniqid();
$field_ids = $wpdb->get_results('SELECT id, type, slug, group_id FROM '.$wpdb->prefix.'directorypress_fields');
?>	

<div class="single-listing-wrapper">
	<?php if ($public_handler->listings):
		while ($public_handler->query->have_posts()):
		
			
			$public_handler->query->the_post();
			$listing = $public_handler->listings[get_the_ID()];
			$GLOBALS['listing_id'] = $public_handler->listings[get_the_ID()];
			$authorID = get_the_author_meta( 'ID' );
			$GLOBALS['authorID2'] = $authorID;
			$GLOBALS['hash'] = $public_handler;
			$single_style_class = 'directory-style';
			$custom_layout = 0;
			?>
			<?php if($custom_layout): ?>
				<?php 
				do_action('directorypress_vc_css_fix', 3722);
				echo do_shortcode(get_post_field('post_content', 3722)); 
				
				?>
			<?php else: ?>
			<?php do_action('single-listing-directory-head-section', $listing, $public_handler); ?>
			<div class="container">
			<div class="row">
			<div class="single-listing directorypress-content-wrap col-lg-8 col-md-9 col-sm-12">
			<div id="<?php echo esc_attr($listing->post->post_name); ?>" itemscope itemtype="http://schema.org/LocalBusiness">
				<article id="post-<?php the_ID(); ?>" class="directorypress-listing directorypress-single-content-area <?php echo esc_attr($single_style_class); ?>">
					
					<?php //do_action('single-listing-slider', $listing, true); ?>
					<div class="directorypress-single-listing-text-content-wrap">
						<?php do_action('directorypress_listing_pre_content_html', $listing); ?>
						
							<?php //$listing->display_content_fields(true); ?>
							<?php 
								foreach( $field_ids as $field_id ) {
									if($field_id->type == 'content'){
										$singlefield_id = $field_id->id;	
										if (isset($listing->fields[$singlefield_id])){
											echo '<div class="single-filed-wrapper clearfix">';
												$listing->display_content_field($singlefield_id);
											echo '</div>';
										}
									}
								}
							?>
							<?php
								foreach( $field_ids as $field_id ) {
									if($field_id->group_id == 0 && ($field_id->type == 'checkbox' || $field_id->type == 'color')){
										$singlefield_id = $field_id->id;	
										if (isset($listing->fields[$singlefield_id]) && $listing->fields[$singlefield_id]->is_field_not_empty($listing)){
											echo '<div class="single-filed-wrapper clearfix">';
												$listing->display_content_field($singlefield_id);
											echo '</div>';
										}
									}
								}
							?>
							<?php do_action('single-listing-gallery', $listing, 1); ?>
							<?php
								$single_fileds = '';
								foreach( $field_ids as $field_id ) {
										if($field_id->group_id == 0 && $field_id->type != 'checkbox' && $field_id->type != 'color' && $field_id->type != 'content' && $field_id->type != 'summary' && $field_id->type != 'hours'){
											$singlefield_id = $field_id->id;	
											if (isset($listing->fields[$singlefield_id]) && $listing->fields[$singlefield_id]->on_listing_page && $listing->fields[$singlefield_id]->is_field_not_empty($listing)){
												$single_fileds = 1;	
											}
										}
								}
								if($single_fileds == 1){
									echo '<div class="single-filed-wrapper clearfix">';
										foreach( $field_ids as $field_id ) {
											if($field_id->group_id == 0 && $field_id->type != 'checkbox' && $field_id->type != 'color' && $field_id->type != 'content' && $field_id->type != 'summary' && $field_id->type != 'hours'){
												$singlefield_id = $field_id->id;
												if(isset($listing->fields[$singlefield_id]) && $listing->fields[$singlefield_id]->on_listing_page){
													$listing->display_content_field($singlefield_id);
												}
											}
										}
									echo '</div>';
								}
								
							?>
						
						<?php $listing->display_content_fields_ingroup(true); ?>
						<?php do_action('directorypress_listing_post_content_html', $listing); ?>
					</div>
					<?php $hash = $public_handler->hash; ?>
					<?php do_action('single-listing-tabs', $listing, $hash); ?>
					<?php do_action('single-listing-video-gallery', $listing); ?>
					<?php do_action('single-listing-faqs', $listing); ?>
					<?php do_action('single-listing-map', $listing, $hash); ?>					
					<?php do_action('single-listing-review-form', $listing); ?>	
					</article>
					
				</div>
				<?php endif; ?>
				<?php do_action('single-listing-similar', $listing); ?>
				<?php 
					echo '<div class="directorypress-custom-popup" data-popup="single_shedule_form">';
						echo '<div class="directorypress-custom-popup-inner single-shedule">';
							echo '<div class="directorypress-popup-title">'.esc_html__('Shedule a Test Drive', 'classiadspro').'<a class="directorypress-custom-popup-close" data-popup-close="single_shedule_form" href="#"><i class="far fa-times-circle"></i></a></div>';
							echo '<div class="directorypress-popup-content">';
								echo do_shortcode('[dhvc_form id="2578"]');
							echo'</div>';
						echo'</div>';
					echo'</div>';
					
					echo '<div class="directorypress-custom-popup" data-popup="single_tradein_form">';
						echo '<div class="directorypress-custom-popup-inner single-tradein">';
							echo '<div class="directorypress-popup-title">'.esc_html__('Apply For TradeIn With Us', 'classiadspro').'<a class="directorypress-custom-popup-close" data-popup-close="single_tradein_form" href="#"><i class="far fa-times-circle"></i></a></div>';
							echo '<div class="directorypress-popup-content">';
								echo do_shortcode('[dhvc_form id="2578"]');
							echo'</div>';
						echo'</div>';
					echo'</div>';
				?>
				<?php do_action('single_listing_bidding', $listing); ?>
				<?php do_action('single_listing_contact', $listing); ?>
			<?php endwhile; wp_reset_query(); endif; ?>
			</div>
				<div class="col-lg-4 col-md-3 col-sm-12">
				<?php dynamic_sidebar('sidebar-10'); ?>
				</div>
			</div>
			</div>
</div>
	