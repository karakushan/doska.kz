<?php global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object, $wpdb, $table_prefix, $sitepress;
echo '<div class="table-responsive clearfix">';
	echo '<table class="directorypress-dashbaord-listings table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th></th>';
				echo '<th>'. esc_html__('Title', 'directorypress-frontend') .'</th>';
				do_action('directorypress-dashboard-listing-after-title-label');
				echo '<th>'. esc_html__('Expiry', 'directorypress-frontend') .'</th>';
				do_action('directorypress-dashboard-listing-after-expiry-label');
				echo '<th>'. esc_html__('Status', 'directorypress-frontend') .'</th>';
				do_action('directorypress-dashboard-listing-after-status-label');
				echo '<th>'. esc_html__('Listing ID', 'directorypress-frontend') .'</th>';
				do_action('directorypress-dashboard-listing-after-id-label');
				echo '<th>'. esc_html__('Settings', 'directorypress-frontend') .'</th>';
				do_action('directorypress-dashboard-listing-after-setting-label');
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			while ($public_handler->query->have_posts()){
				$public_handler->query->the_post();
				$listing = $public_handler->listings[get_the_ID()]; 
								
				if(isset($listing->logo_image) && !empty($listing->logo_image)){
					$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
					$image_src = $image_src_array[0];
				}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
					$image_src_array = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
					$image_src = $image_src_array;
				}else{
					$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
				}
								
				$params = array( 'width' => 80, 'height' => 80, 'crop' => true );
				$params_mobile = array( 'width' => 480, 'height' => 250, 'crop' => false );
				$param = array(
					'width' => 80,
					'height' => 80,
					'crop' => true
				);
				
				echo '<tr class="dashboard-listing-wrapper">';
					echo '<td>';
						echo '<div class="dashboard-listings-thumb desktop">';
							echo '<img src="'. esc_url(bfi_thumb($image_src, $params)) .'" alt="listing logo" width="80" height="80"/>';
						echo '</div>';
					echo '</td>';
					echo '<td>';
						$parent_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => 0) );
						$parent_term = (is_array($parent_terms) || is_object($parent_terms))? array_pop($parent_terms): '';
						$parent_term_link = (!empty($parent_term))? '<a class="listing-cat" href="'.get_term_link($parent_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'.$parent_term->name.'</a>': '';
						$child_term_link = '';
						$seperator = '';
						if($parent_term){
							$child_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => $parent_term->term_id ) );
							$child_term = (is_array($child_terms) || is_object($child_terms))? array_pop($child_terms): '';
							$child_term_link = (!empty($child_term))? '<a class="listing-cat" href="'.get_term_link($child_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'.$child_term->name.'</a>': '';
											
							$seperator = (!empty($child_term))? '<span class="cat-seperator fas fa-angle-right"></span>': '';
						}
						echo '<div class="cat-wrapper">';
							echo wp_kses_post($parent_term_link . $seperator . $child_term_link);
						echo '</div>';
						
						echo '<div class="dashboard-listings-title">';
							echo '<h5>';
								if (directorypress_user_permission_to_edit_listing($listing->post->ID)){
									echo '<a href="' . esc_url(directorypress_edit_post_url($listing->post->ID)) . '">' . esc_html($listing->title()) . '</a>';
								}else{
									echo esc_html($listing->title());
								}
							echo '</h5>';	
						echo '</div>';	
						echo '<div class="grid-fields-wrapper block-fields clearfix">';
							$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
							foreach( $field_ids as $field_id ) {
								$singlefield_id = $field_id->id;
								if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price')){				
									$listing->display_content_field($singlefield_id);
								}				
							}
						echo '</div>';
						do_action('directorypress_dashboard_listing_title', $listing);
					echo '</td>';
					do_action('directorypress-dashboard-listing-after-title-html', $listing);
					echo '<td>';
						echo '<div class="dashboard-listings-expiry">';
							echo '<div class="expiry-value">';
								if ($listing->package->package_no_expiry){
									_e('No Expiry', 'directorypress-frontend');
								}elseif ($listing->expiration_date > time()){
									echo human_time_diff(time(), $listing->expiration_date) . '&nbsp;' . esc_html__('left', 'directorypress-frontend');
								}elseif ($listing->expiration_date < time()){
									_e('Expired', 'directorypress-frontend');
								}
							echo '</div>';
						echo '</div>';
					echo '</td>';
					do_action('directorypress-dashboard-listing-after-expiry-html', $listing);
					echo '<td>';
						echo '<div class="dashboard-listings-status">';	
							if ($listing->post->post_status == 'pending'){
								echo '<span class="label label-default">' . esc_html__('Pending Approval', 'directorypress-frontend').'</span><br>';
							}
							if ($listing->status == 'active'){
								echo '<span class="label label-success">' . esc_html__('Active', 'directorypress-frontend') . '</span><br>';
							}elseif ($listing->status == 'expired'){
								echo '<span class="label label-danger">' . esc_html__('Expired', 'directorypress-frontend') . '</span><br>';
							}elseif ($listing->status == 'unpaid'){
								$listing_id = $listing->post->ID;
								$items = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}woocommerce_order_itemmeta` WHERE (`meta_key`, `meta_value`) = ('_directorypress_listing_id' ,'{$listing->post->ID}')" );
								if($items){
									$order_id = wc_get_order_id_by_order_item_id($items[0]->order_item_id);
									$order = wc_get_order( $order_id );
									if(is_object($order)){
										echo '<span class="label label-warning">' . esc_html__('Unpaid', 'directorypress-frontend') .' &#124; <a href="'. esc_url($order->get_checkout_payment_url()) .'">'. esc_html__('Pay now', 'directorypress-frontend') . '</a></span><br>';
									}
								}
							}elseif ($listing->status == 'stopped'){
								echo '<span class="label label-danger">' . esc_html__('Stopped', 'directorypress-frontend') . '</span>';
							}
							do_action('directorypress_listing_status_option', $listing);
						echo '</div>';
					echo '</td>';
					echo '<td>';
						echo '<div class="dashboard-listings-id">';
							echo '<div class="listing-id">'.esc_html($listing->post->ID) .'</div>';
						echo '</div>';
					echo '</td>';
					do_action('directorypress-dashboard-listing-after-id-html', $listing);
					echo '<td>';
						echo '<div class="dashboard-listing-settings clearfix">';
							echo '<div class="listing-setting">'; 
								if (directorypress_user_permission_to_edit_listing($listing->post->ID)){
									echo '<div class="dropdown show">';
										echo '<a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
											echo esc_html__('Configure', 'directorypress-frontend');
											echo '<span class="dicode-material-icons dicode-material-icons-chevron-down"></span>';
										echo '</a>';
										echo '<div class="dropdown-menu">';
											echo '<a href="'. esc_url(directorypress_edit_post_url($listing->post->ID)) .'">';
												echo '<span class="dicode-material-icons dicode-material-icons-square-edit-outline"></span>';
												esc_html_e('Edit Listing', 'directorypress-frontend');
											echo '</a>';
											if ($listing->status == 'expired') {
													echo '<a class="listing_setting_action_link" data-modal-button-text="'. esc_attr__('Renew', 'directorypress-frontend') .'" data-modal-class="listing_renew_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal" href="#"><span class="fas fa-sync"></span>' . esc_html__('Renew', 'directorypress-frontend') . '</a>';
												}
											if ($listing->package->is_upgradable()){
												echo '<a href="#" class="listing_setting_action_link" data-modal-button-text="'.esc_attr__('Change', 'directorypress-frontend').'" data-modal-title="'.esc_attr__('Change Listing Package', 'directorypress-frontend').'" data-modal-class="listing_change_package_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal"><span class="dicode-material-icons dicode-material-icons-progress-upload"></span>'. esc_html__('Upgrade', 'directorypress-frontend').'</a>';
											}
											if ($listing->package->can_be_bumpup && $listing->status == 'active' && $listing->post->post_status == 'publish') {
												echo '<a class="listing_setting_action_link" data-modal-button-text="'.esc_attr__('Boost', 'directorypress-frontend').'" data-modal-title="'.esc_attr__('Boost Your Listing', 'directorypress-frontend').'" data-modal-class="listing_bumpup_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal" href="#"><span class="dicode-material-icons dicode-material-icons-rocket-outline"></span>' . esc_html__('BumpUp To Top', 'directorypress-frontend') . '</a>';
											}
											if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_stats']) {
												echo '<a href="#" class="listing_setting_action_link" data-modal-button-text="'.esc_attr__('Change', 'directorypress-frontend').'" data-modal-title="'.esc_attr__('Listing Performance Status', 'directorypress-frontend').'" data-modal-class="listing_performance_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal"><span class="dicode-material-icons dicode-material-icons-signal-cellular-outline"></span>' . esc_html__('Performance', 'directorypress-frontend') . '</a>';
											}
											if ($listing->status == 'active' && $listing->post->post_status == 'publish') {
												echo '<a href="' . esc_url(get_permalink($listing->post->ID)) . '"><span class="fas fa-eye"></span>' . esc_html__('Preview', 'directorypress-frontend') . '</a>';
											}
											$status_btn = (get_post_status ($listing->post->ID) == 'publish')? esc_html__('Make Private', 'directorypress-frontend'): esc_html__('Publish', 'directorypress-frontend');
											echo '<a href="#" class="listing_setting_action_link" data-modal-button-text="'. esc_attr($status_btn) .'" data-modal-title="'.esc_attr__('Make Listing Publish or Private', 'directorypress-frontend').'" data-modal-class="change_listing_status_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal"><span class="dicode-material-icons dicode-material-icons-eye-off-outline"></span>'. esc_html__('Publish/Private', 'directorypress-frontend').'</a>';
											if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_note_to_admin']) {
												echo '<a href="#" class="listing_setting_action_link" data-modal-button-text="'.esc_attr__('Send', 'directorypress-frontend').'" data-modal-title="'.esc_attr__('Send A Note To Admin', 'directorypress-frontend').'" data-modal-class="listing_admin_notice_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal"><span class="dicode-material-icons dicode-material-icons-clipboard-text-outline"></span>'. esc_html__('Note To Admin', 'directorypress-frontend').'</a>';
											}
											if (function_exists('wpml_object_id_filter') && $sitepress && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_frontend_translations'] && ($languages = $sitepress->get_active_languages()) && count($languages) > 1){
												echo '<a href="#" data-modal-button-text="'.esc_attr__('Change', 'directorypress-frontend').'" data-modal-title="'.esc_attr__('Translation', 'directorypress-frontend').'" class="listing_setting_action_link" data-modal-class="listing_translation_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal"></i><span class="dicode-material-icons dicode-material-icons-translate"></span>'. esc_html__('Translation', 'directorypress-frontend').'</a>';
											}
											do_action('directorypress_dashboard_listing_options', $listing);
											echo '<a href="#" class="listing_setting_action_link" data-modal-button-text="'. esc_attr__('Delete', 'directorypress-frontend') .'" data-modal-title="'. esc_attr__('Are You Sure?', 'directorypress-frontend') .'" data-modal-class="listing_delete_modal" data-listing-id="'. esc_attr($listing->post->ID) .'" data-bs-toggle="modal" data-bs-target="#listing_action_modal">';
												echo '<span class="dicode-material-icons dicode-material-icons-trash-can-outline"></span>';
												esc_html_e('Delete', 'directorypress-frontend');
											echo '</a>';
										echo '</div>';
									echo '</div>';
								}
							echo '</div>';
						echo '</div>';
					echo '</td>';
				echo '</tr>';
				do_action('directorypress-dashboard-listing-after-configure-html', $listing);
			}
		echo '</tbody>';
	echo '</table>';
echo '</div>';