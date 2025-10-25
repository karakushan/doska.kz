<?php 

/**
 * Template name: Listing Author Template
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/partials/templates
 * @author     DirectoryPress <team@directorypress.co>
*/

global $post, $DIRECTORYPRESS_ADIMN_SETTINGS, $wp_query;
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$authorID = $author->ID;

get_header();

	if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_social_links']){
			$author_fb = get_the_author_meta('author_fb', $authorID);
			$author_tw = get_the_author_meta('author_tw', $authorID);
			$author_ytube = get_the_author_meta('author_ytube', $authorID);
			$author_vimeo = get_the_author_meta('author_vimeo', $authorID);
			$author_flickr = get_the_author_meta('author_flickr', $authorID);
			$author_linkedin = get_the_author_meta('author_linkedin', $authorID);
			$author_gplus = get_the_author_meta('author_gplus', $authorID);
			$author_instagram = get_the_author_meta('author_instagram', $authorID);
			$author_behance = get_the_author_meta('author_behance', $authorID);
			$author_dribbble = get_the_author_meta('author_dribbble', $authorID);
	}
	
	$author_name = get_the_author_meta('display_name', $authorID);
	$author_email = get_the_author_meta('email', $authorID);
	$registered = date_i18n( get_option( 'date_format' ), strtotime( get_the_author_meta( 'user_registered', $authorID ) ) );

	$author_website = get_the_author_meta('user_url', $authorID);
	$phone_number = get_the_author_meta('user_phone', $authorID);
	$whatsapp_number = get_the_author_meta('user_whatsapp_number', $authorID);
	$author_address = get_the_author_meta('user_address', $authorID);
	
	$avatar_id = get_user_meta( $authorID, 'avatar_id', true );
	$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
	$image_src_array = (!empty($avatar_id) && is_numeric($avatar_id))? $author_avatar_url[0]:'';
			
		
	$hide_contact_from_anonymous = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['author_contact_hide_from_anonymous']))? $DIRECTORYPRESS_ADIMN_SETTINGS['author_contact_hide_from_anonymous']: 0;
	$email_verification_status = get_user_meta($authorID, 'email_verification_status', true );
	
	
	echo '<div id="directorypress-auhtor-page">';
		echo '<div class="container">';
			echo '<div class="row clearfix">';
				echo '<div class="col-12">';
					echo '<div class="directorypress-author-content-top">';
						echo '<div class="author-detail-section">';
							echo '<div class="author-thumbnail">';
								if(!empty($image_src_array)) {
									$params = array( 'width' => 278, 'height' => 298, 'crop' => true );
										echo  '<img src="'. bfi_thumb( $image_src_array, $params ).'" alt="'.$author_name.'" />';
									} else { 
										$avatar_url = get_avatar_url($authorID, ['size' => '270']);
										echo '<img src="'.$avatar_url.'" alt="author" />';
									}
							echo '</div>';
							echo '<div class="author-content-section">';
								if ( directorypress_is_user_online($authorID) ){
									echo '<div class="author-status">'. esc_html__('online', 'classiadspro') .'</div>';
								}else{
									echo '<div class="author-status offline">'. esc_html__('offline', 'classiadspro') .'</div>';
								}
								echo '<div class="author-title">'.$author_name .'</div>';
								echo '<p class="author-reg-date">'. esc_html__('Member since', 'classiadspro').' '.$registered.'</p>';
								echo '<div class="author-details-items-section">';
									if($hide_contact_from_anonymous && !is_user_logged_in()){
										echo '<div class="alert alert-info">'. esc_html__('Login to access contact details', 'classiadspro') .'</div>';
									}else{
									
										if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_phone'] && $phone_number){ 
											echo '<p class="clearfix">';
												echo '<span class="author-info-title">';
													echo '<i class="pacz-fic5-telephone"></i>';
													echo '<span class="title-label">';
														echo esc_html__('Phone', 'classiadspro');
													echo '</span>';
													echo '<span class="author-info-content">';
														echo '<a href="tel:'. esc_url($phone_number) .'">'. esc_html($phone_number) .'</a>';
														if(directorypress_is_directorypress_twilio_active()){
															$phone_verification_status = get_user_meta($authorID, 'phone_verification_status', true );
															if($phone_verification_status == 'verified'){
																echo '<span class="user-verified-tag"><i class="dicode-material-icons dicode-material-icons-check"></i>'.esc_html__('verified', 'classiadspro').'</span>';
															}else{
																echo '<span class="user-unverified-tag"><i class="dicode-material-icons dicode-material-icons-close-box-outline"></i>'.esc_html__('unverified', 'classiadspro').'</span>';
															}
														}
													echo '</span>';
												echo '</span>';
											echo '</p>'; 
										}
										if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_phone'] && $whatsapp_number){ 
											echo '<p class="clearfix">';
												echo '<span class="author-info-title">';
													echo '<i class="pacz-theme-icon-whatsapp"></i>';
													echo '<span class="title-label">';
														echo esc_html__('Whatsapp', 'classiadspro');
													echo '</span>';
												echo '</span>';
												echo '<span class="author-info-content"><a href="https://api.whatsapp.com/send?phone='. esc_url($whatsapp_number) .'">'. esc_html($whatsapp_number) .'</a></span>';
											echo '</p>'; 
										}
										if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_website'] && $author_website){
											echo '<p class="clearfix">';
												echo '<span class="author-info-title">';
													echo '<i class="pacz-fic2-earth-globe"></i>';
													echo '<span class="title-label">';
														echo esc_html__('Website', 'classiadspro');
													echo '</span>';	
												echo '</span>';
												echo '<span class="author-info-content"><a href="'. esc_url($author_website) .'" target="_blank">'. esc_html($author_website) .'</a></span>';
											echo '</p>';
										}
										if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_email']){ 
											echo '<p class="clearfix">';
												echo '<span class="author-info-title">';
													echo '<i class="pacz-theme-icon-email"></i>';
													echo '<span class="title-label">';
														echo esc_html__('Email', 'classiadspro');
													echo '</span>';
												echo '</span>';
												echo '<span class="author-info-content">';
													echo '<a href="'. esc_url('mailto:'. $author_email) .'">'. esc_html($author_email) .'</a>';
													if($email_verification_status == 'verified'){
														echo '<span class="user-verified-tag"><i class="dicode-material-icons dicode-material-icons-check"></i>'.esc_html__('verified', 'classiadspro').'</span>';
													}else{
														echo '<span class="user-unverified-tag"><i class="dicode-material-icons dicode-material-icons-close-box-outline"></i>'.esc_html__('unverified', 'classiadspro').'</span>';
													}
												echo '</span>';
											echo '</p>';
										}
										if($author_address){
											echo '<p class="clearfix">';
												echo '<span class="author-info-title">';
													echo '<i class="pacz-fic3-pin-1"></i>';
													echo '<span class="title-label">';
														echo esc_html__('Address', 'classiadspro');
													echo '</span>';
												echo '</span>';
												echo '<span class="author-info-content"><a href="'. esc_url($author_address) .'" target="_blank">'. esc_html($author_address) .'</a></span>';
											echo '</p>';
										}
										if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_social_links']){
											if(!empty($author_fb) || !empty($author_tw) || !empty($author_linkedin) || !empty($author_ytube) || !empty($author_vimeo) || !empty($author_instagram) || !empty($author_flickr) || !empty($author_behance) || !empty($author_dribbble)){
												echo '<div class="author-social-network clearfix">';
													echo '<span class="author-info-title">';
														echo '<i class="pacz-fic1-networking"></i>';
														echo '<span class="title-label">';
															echo esc_html__('Social Profiles', 'classiadspro');
														echo '</span>';
													echo '</span>';
													echo '<span class="author-info-content author-social-follow">';
														echo '<ul class="author-social-follow-ul">';
															if(!empty($author_fb)){
																echo'<li><a class="facebook" href="'.$author_fb.'" target_blank><i class="fab fa-facebook-f"></i></a></li>';
															}
															if(!empty($author_tw)){
																echo'<li><a class="twitter" href="'.$author_tw.'" target_blank><i class="fab fa-twitter"></i></a></li>';
															}
															if(!empty($author_linkedin)){
																echo'<li><a class="linkedin" href="'.$author_linkedin.'" target_blank><i class="fab fa-linkedin-in"></i></a></li>';
															}
															if(!empty($author_ytube)){
																echo'<li><a class="youtube" href="'.$author_ytube.'" target_blank><i class="fab fa-youtube"></i></a></li>';
															}
															if(!empty($author_vimeo)){
																echo'<li><a class="vimeo" href="'.$author_vimeo.'" target_blank><i class="fab fa-vimeo-v"></i></a></li>';
															}
															if(!empty($author_instagram)){
																echo'<li><a class="instagram" href="'.$author_instagram.'" target_blank><i class="fab fa-instagram"></i></a></li>';
															}
															if(!empty($author_flickr)){
																echo'<li><a class="flikr" href="'.$author_flickr.'" target_blank><i class="fab fa-flickr"></i></a></li>';
															}
															if(!empty($author_behance)){
																echo'<li><a class="behance" href="'.$author_behance.'" target_blank><i class="fab fa-behance"></i></a></li>';
															}
															if(!empty($author_dribbble)){
																echo'<li><a class="dribbble" href="'.$author_dribbble.'" target_blank><i class="fab fa-dribbble"></i></a></li>';
															}		
														echo '</ul>';
													echo '</span>';
												echo '</div>';
											}
										}	
									
									}
								echo '</div>';								
							echo '</div>';
									//contact section
										echo '<div class="contact-section">';	
											if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_phone'] && $phone_number){ 
													echo '<p class="tel-phone clearfix">';
														echo '<span class="author-info-title">';
															echo '<i class="pacz-fic5-telephone"></i>';
															echo '<span class="title-label">';
																echo '<span class="author-info-content"><a href="tel:'. esc_attr($phone_number) .'">'. esc_html__('Call Now', 'classiadspro') .'</a></span>';
															echo '</span>';
														echo '</span>';
													echo '</p>'; 
												}
												if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_phone'] && $whatsapp_number){ 
													echo '<p class="tel-whatsapp clearfix">';
														echo '<span class="author-info-title">';
															echo '<i class="pacz-theme-icon-whatsapp"></i>';
															echo '<span class="title-label">';
																echo '<span class="author-info-content"><a href="https://api.whatsapp.com/send?phone='. esc_attr($whatsapp_number) .'">'. esc_html__('Whatsapp', 'classiadspro') .'</a></span>';
															echo '</span>';
														echo '</span>';
													echo '</p>'; 
												}
												if($DIRECTORYPRESS_ADIMN_SETTINGS['frontend_panel_user_email']){ 
													echo '<p class="to-email clearfix">';
														echo '<span class="author-info-title">';
															echo '<i class="pacz-theme-icon-email"></i>';
															echo '<span class="title-label">';
																echo '<span class="author-info-content"><a href="'. esc_url('mailto:'. $author_email) .'">'. esc_html__('Send Email', 'classiadspro') .'</a></span>';
															echo '</span>';
														echo '</span>';
													echo '</p>';
												}
										echo '</div>';	
									// description
									echo '<p class="clearfix">';
										echo '<div class="author-description-section">';
											if(get_the_author_meta('description', $authorID)){
											echo '<div class="pacz-post-single-author-bio">';
												echo '<h6 class="about-author">'. esc_html__('About', 'classiadspro').' '.$author_name.'</h6>';
												echo'<p>'. get_the_author_meta('description', $authorID) .'</p>';
											echo '</div>';
											}
										echo '</div>';
									echo '</p>';
									
						echo '</div>';
									

						
						/* Run the blog loop shortcode to output the posts. */
						//$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
							$authorID = $author->ID;
							$listing_number = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_ads_limit']))? $DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_ads_limit'] : 4;
							$listing_column = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_grid_col']))? $DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_grid_col'] : 2;
							$listing_view_type = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_view_type']))? $DIRECTORYPRESS_ADIMN_SETTINGS['authorpage_view_type'] : 'grid';
							$directorypress_listing_post_style = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']))? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style'] : 10; 
							$listing_order = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['author_page_listing_order']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['author_page_listing_order']))? $DIRECTORYPRESS_ADIMN_SETTINGS['author_page_listing_order'] : 'DESC';
						echo '<div class="author-listings">';
						echo '<h3 class="listings-heading">'. esc_html__('Listings By - ', 'classiadspro').' '.$author_name.'</h3>';		
						echo do_shortcode( '[directorypress-listings listing_post_style="'.$directorypress_listing_post_style.'" author="'.$authorID.'" listing_has_featured_tag_style="'.$directorypress_listing_post_style.'" masonry_layout="1" perpage="'.$listing_number.'" hide_paginator="" hide_order="1" hide_count="1" show_views_switcher="0" listings_view_type="'.$listing_view_type.'" order="'. $listing_order .'" listings_view_grid_columns="'.$listing_column.'"]' );
						echo '</div>';
					echo '</div>';
				echo '</div>';
				//echo '<div class="col-12">';
					//get_sidebar();
				//echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	get_footer();	