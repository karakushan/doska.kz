<?php
global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS, $current_user, $post, $directorypress_dynamic_styles;
$directorypress_styles = '';
$id = uniqid();
$field_ids = $wpdb->get_results('SELECT id, type, slug, group_id FROM '.$wpdb->prefix.'directorypress_fields');

?>	

<div class="single-listing directorypress-content-wrap">
	<?php if ($public_handler->listings):
		while ($public_handler->query->have_posts()):
		
			
			$public_handler->query->the_post();
			$listing = $public_handler->listings[get_the_ID()];
			$GLOBALS['listing_id'] = $public_handler->listings[get_the_ID()];
			$authorID = get_the_author_meta( 'ID' );
			$GLOBALS['authorID2'] = $authorID;
			$GLOBALS['hash'] = $public_handler;
			$single_style_class = 'default';
			$custom_layout = 0;
			?>
			<?php if($custom_layout): ?>
				<?php 
				do_action('directorypress_vc_css_fix', 3722);
				echo do_shortcode(get_post_field('post_content', 3722)); 
				
				?>
			<?php else: ?>
			<div id="<?php echo esc_attr($listing->post->post_name); ?>" itemscope itemtype="http://schema.org/LocalBusiness">
				<article id="post-<?php the_ID(); ?>" class="directorypress-listing directorypress-single-content-area <?php echo esc_attr($single_style_class); ?>">
					<div class="mobile-single-listing-slider clearfix">
						<a class="mobile-single-listing-back-link" href="<?php echo esc_url(home_url('/')); ?>"><i class="dicode-material-icons dicode-material-icons-arrow-left"></i></a>
						<div class="dropdown show">
										<span role="button" id="dropdownMenuLink" class="dicode-material-icons dicode-material-icons-dots-vertical" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
											<ul>
											<?php
												if ( is_user_logged_in() && $current_user->ID == $listing->post->post_author){
													echo '<li>';
														do_action('directorypress-edit-listing-button', $listing->post->ID, true, 0);
													echo '</li>';
												}
												do_action('directorypress_listing_buttons_list_pre', $listing->post->ID, true, 0);
												if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_report_button']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_report_button']){
													echo '<li>';
														do_action('single-listing-report', $listing, true, 0);
													echo '</li>';
												}
												if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_pdf_button']){
													echo '<li>';
														do_action('single-listing-pdf', $listing, true, 0);
													echo '</li>';
												}
												if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_print_button']){
													echo '<li>';
														do_action('single-listing-print', $listing, true, 0);
													echo '</li>';
												}
												do_action('directorypress_listing_buttons_list_post', $listing->post->ID, true, 0);	
											?>
											</ul>
										</div>
						</div>
						<?php do_action('single-listing-slider', $listing, false); ?>
						<div class="mobile-single-listing-tags">
							<?php if ($listing->package->has_featured): ?>
								<span class="mobile-listing-single-featured-tag"><?php echo esc_html__('Featured', 'classiadspro'); ?></span>
							<?php endif; ?>
							<?php
								foreach( $field_ids as $field_id ) {
									$singlefield_id = $field_id->id;
										
										if($field_id->type == 'status'){			
											$listing->display_content_field($singlefield_id);
										}
									
								}
							?>
						</div>
						<div class="mobile-single-listing-btns clearfix">
							<ul>
								<?php
									if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_favourites_list']){
										echo '<li>';
											do_action('single-listing-bookmark', $listing, false, 0);
										echo '</li>';
									}
									if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']['enabled']){
										echo '<li>';
											do_action('single-listing-share', $listing, false, 0);
										echo '</li>';
									}
								?>
							</ul>
						</div>
					</div>
						<?php do_action('directorypress-breadcrumb', $listing, $public_handler); ?>
						<div class="listing-main-content clearfix">
							<div class="price">
								<?php
								foreach( $field_ids as $field_id ) {
									$singlefield_id = $field_id->id;
									if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price')){				
										
										$listing->display_content_field($singlefield_id);
										
									}				
								}	
								?>
							</div>
							<?php do_action('single-listing-title', $listing); ?>
							<div class="mobile-single-listing-location">
								<?php do_action('location_for_grid_and_list', $listing, true); ?>
							</div>
							<div class="listing-metas-single clearfix">	
								<?php do_action('single-listing-date-published', $listing); ?>		
								<?php do_action('single-listing-views', $listing); ?>
								<?php do_action('single-listing-id', $listing); ?>
							</div>
						</div>	
						<div class="directorypress-single-mobile-content-container">
							<div class="directorypress-single-listing-text-content-wrap">
								<?php do_action('directorypress_listing_pre_content_html', $listing); ?>
								<?php
									$has_field = 0;
									foreach( $field_ids as $field_id ) {
										if($field_id->group_id == 0){
											$singlefield_id = $field_id->id;	
											if (isset($listing->fields[$singlefield_id]) && $listing->fields[$singlefield_id]->is_field_not_empty($listing)){
												$has_field = 1;
											}
										}
									}
									if($has_field){
										echo '<div class="single-filed-wrapper clearfix">';
											$listing->display_content_fields(true);
										echo '</div>';
									}
								?>
								<?php $listing->display_content_fields_ingroup(true); ?>
								<?php do_action('directorypress_listing_post_content_html', $listing); ?>
							</div>
							<?php $hash = $public_handler->hash; ?>
							<?php do_action('single-listing-tabs', $listing, $hash); ?>
							<?php do_action('single-listing-videos', $listing); ?>
							<?php do_action('single-listing-map', $listing, $hash); ?>					
							<?php do_action('single-listing-review-form', $listing); ?>
							<?php //do_shortcode('[dpar_list]'); ?>
							<?php //do_action('dpar_review_form', $listing); ?>
						</div>
					</article>
					
				</div>
				<?php endif; ?>
				<?php do_action('single-listing-similar', $listing); ?>
				<?php do_action('single_listing_bidding', $listing); ?>
				<?php do_action('single_listing_contact', $listing); ?>
			<?php endwhile; endif; ?>
</div>