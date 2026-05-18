<?php 
$itab_id = uniqid();
if($id){directorypress_renderMessages();}
 ?>
<script>
	(function($) {
		"use strict";
	
		$(function() {
			var max_index = <?php echo ((count(array_keys($item->selection_items)) ? max(array_keys($item->selection_items)) : 1)); ?>;
			$("#add_selection_item").click(function() {
				max_index = max_index+1;
				$("#selection_items_wrapper").append('<div class="selection_item clearfix"><span class="far fa-trash-alt directorypress-delete-selection-item"></span><input name="selection_items['+max_index+']" type="text" class="regular-text" value="" placeholder="text"/><span class="fas fa-arrows-alt directorypress-move-label"></span></div>');
			});
			$(document).on("click", ".directorypress-delete-selection-item", function() {
				//$(this).parent().remove();
				 var targeted_popup_class1 = jQuery(this).attr('data-id');
        $('.id_'+ targeted_popup_class1+'').remove();
				//$('.id_'+data-attr['id']).remove();
			});
		
			$("#selection_items_wrapper").sortable({
				placeholder: "ui-sortable-placeholder",
				helper: function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				},
				start: function(e, ui){
					ui.placeholder.height(ui.item.height());
				}
	    	});
			//$('.selection_item').wrap('<div class="selection_item_main"></div>');
		});
	})(jQuery);
</script>
<div class="directorypress-modal-content wp-clearfix">
	<ul class="nav nav-tabs" id="tabContent">
		
		<li class="nav-item"><a class="nav-link active" href="#general-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('General', 'directorypress-payment-manager'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#expiry-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('Expiry', 'directorypress-payment-manager'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#features-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('Features', 'directorypress-payment-manager'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#resources-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('Resources', 'directorypress-payment-manager'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#custom-options-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('Custom Options', 'directorypress-payment-manager'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#dependency-<?php echo $itab_id ?>" data-bs-toggle="tab"><?php _e('Dependency', 'directorypress-payment-manager'); ?></a></li>
	</ul>
	<div class="tab-content">
		<script>
			(function($) {
							"use strict";

							$(function() {
								$("body").on("click", "#package_no_expiry", function() {
									if ($('#package_no_expiry').is(':checked')) {
										$('#package_duration').attr('disabled', true);
										$('#package_duration_unit').attr('disabled', true);
										$('#change_package_id').attr('disabled', true);
									} else {
										$('#package_duration').removeAttr('disabled');
										$('#package_duration_unit').removeAttr('disabled');
										$('#change_package_id').removeAttr('disabled');
									}
								});
							});
			})(jQuery);
		</script>
		<form class="add-edit" method="POST" action="">
			<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_packages_nonce');?>
			<div class="tab-pane fade active show" id="general-<?php echo $itab_id ?>">				
				<div class="row clearfix">
					<div class="col-md-12">
						<label><?php _e('Package  Title', 'directorypress-payment-manager'); ?><span class="directorypress-red-asterisk">*</span></label>
					</div>
					<div class="col-md-12">
						<input name="name" type="text" class="regular-text" value="<?php echo esc_attr($item->name); ?>" />
						<?php directorypress_wpml_translation_notification_string(); ?>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-12">
						<label><?php _e('Package Summary', 'directorypress-payment-manager'); ?></label>
					</div>
					<div class="col-md-12">
						<p class="description"><?php _e("You can right a Short description for this package", 'directorypress-payment-manager'); ?></p>
						<textarea name="description" cols="60" rows="4" ><?php echo esc_textarea($item->description); ?></textarea>
						<?php directorypress_wpml_translation_notification_string(); ?>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="expiry-<?php echo $itab_id ?>">
				<div class="row clearfix">
					<div class="col-md-12"><label><?php _e('Package Expiry And Listing Number', 'directorypress-payment-manager'); ?></label></div>
					<div class="col-md-12"><p><?php _e("Listing will remain active for activation period", 'directorypress-payment-manager'); ?></p></div>
					<div class="col-md-12">
					<div><label><?php _e('Not Expirable?', 'directorypress-payment-manager'); ?></label></div>
						<label class="switch">
							<input name="package_no_expiry" type="checkbox" value="1" id="package_no_expiry" <?php checked($item->package_no_expiry); ?> />
							<span class="slider"></span>
						</label>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Set Duration', 'directorypress-payment-manager'); ?></label></div>
						<input name="package_duration" type="text" value="<?php echo $item->package_duration; ?>" id="package_duration" <?php disabled($item->package_no_expiry); ?> />
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Select An interval', 'directorypress-payment-manager'); ?></label></div>
						<select name="package_duration_unit" id="package_duration_unit" <?php disabled($item->package_no_expiry); ?> >
							<option value="day" <?php if ($item->package_duration_unit == 'day') echo 'selected'; ?> ><?php _e("day(s)", "directorypress-payment-manager"); ?></option>
							<option value="week" <?php if ($item->package_duration_unit == 'week') echo 'selected'; ?> ><?php _e("week(s)", "directorypress-payment-manager"); ?></option>
							<option value="month" <?php if ($item->package_duration_unit == 'month') echo 'selected'; ?> ><?php _e("month(s)", "directorypress-payment-manager"); ?></option>
							<option value="year" <?php if ($item->package_duration_unit == 'year') echo 'selected'; ?> ><?php _e("year(s)", "directorypress-payment-manager"); ?></option>
						</select>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('After expiry (Select an action after expired)', 'directorypress-payment-manager'); ?></label></div>
						<select name="change_package_id" id="change_package_id" <?php disabled($item->package_no_expiry); ?> >
							<option value="0" <?php if ($item->change_package_id == 0) echo 'selected'; ?> >- <?php _e("Just suspend", "directorypress-payment-manager"); ?> -</option>
							<?php foreach ($directorypress_object->packages->packages_array AS $new_item): ?>
								<?php if ($item->id != $new_item->id): ?>
									<option value="<?php echo $new_item->id; ?>" <?php if ($item->change_package_id == $new_item->id) echo 'selected'; ?> ><?php echo $new_item->name; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Number of listings in package', 'directorypress-payment-manager'); ?></label></div>
						<input id="number_of_listings_in_package" name="number_of_listings_in_package" type="text" size="1" value="<?php echo $item->number_of_listings_in_package; ?>" />
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Number Package Renew Allowed', 'directorypress-payment-manager'); ?></label></div>
						<div><p><?php _e("Set package repurchase limit (1 means user would be able to purchase this package once only.), Leave empty to allow unlimited repurchases.", 'directorypress-payment-manager'); ?></p></div>
						<input id="number_of_package_renew_allowed" name="number_of_package_renew_allowed" type="text" size="1" value="<?php echo $item->number_of_package_renew_allowed; ?>" />
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="features-<?php echo $itab_id ?>">
				<div class="row clearfix">
					<div class="col-md-12"><label><?php _e('Package Features', 'directorypress-payment-manager'); ?></label></div>
					<div class="col-md-12"><p><?php _e("Allow Extra features for this package", 'directorypress-payment-manager'); ?></p></div>
					<div class="col-md-12">
						<div><label><?php _e('Enable BumpUp/RaiseUp ', 'directorypress-payment-manager'); ?></label></div>
						<label class="switch">
							<input name="can_be_bumpup" type="checkbox" value="1" id="can_be_bumpup" <?php checked($item->can_be_bumpup); ?> />
							<span class="slider"></span>
						</label>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Enable Featured Option', 'directorypress-payment-manager'); ?></label></div>
						<label class="switch">
							<input name="has_featured" type="checkbox" value="1" id="has_featured" <?php checked($item->has_featured); ?> />
							<span class="slider"></span>
						</label>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Enable Sticky Option', 'directorypress-payment-manager'); ?></label></div>
						<label class="switch">
							<input name="has_sticky" type="checkbox" value="1" id="has_sticky" <?php checked($item->has_sticky); ?> />
							<span class="slider"></span>
						</label>
					</div>
					<div class="col-md-12">
						<div><label><?php _e('Make This Package Featured ', 'directorypress-payment-manager'); ?></label></div>
						<label class="switch">
							<input name="featured_package" type="checkbox" value="1" id="featured_package" <?php checked($item->featured_package); ?> />
							<span class="slider"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="resources-<?php echo $itab_id ?>">
					<div class="row clearfix">
										<div class="col-md-12"><label><?php _e('Resources Available in This Package', 'directorypress-payment-manager'); ?></label></div>
										<div class="col-md-12"><p><?php _e("Allow number of categories,locations and fields", 'directorypress-payment-manager'); ?></p></div>
										<div class="col-md-12">
											<div><label><?php _e('Number of Images ', 'directorypress-payment-manager'); ?></label></div>
											<div>
												<input name="images_allowed" type="text" id="images_allowed" value="<?php echo esc_attr($item->images_allowed); ?>" />
											</div>
										</div>
										<div class="col-md-12">
											<div><label><?php _e('Video Attachment ', 'directorypress-payment-manager'); ?></label></div>
											<div>
												<input name="videos_allowed" type="text" id="videos_allowed" value="<?php echo esc_attr($item->videos_allowed); ?>" />
											</div>
										</div>
										<div class="col-md-12">
											<div><label><?php _e('Number of Address ', 'directorypress-payment-manager'); ?></label></div>
											<div>
												<input name="location_number_allowed" type="text" id="location_number_allowed" value="<?php echo esc_attr($item->location_number_allowed); ?>" />
											</div>
										</div>
										<div class="col-md-12">
											<div><label><?php _e('Number Of Categories', 'directorypress-payment-manager'); ?></label></div>
											<div><input name="category_number_allowed" id="category_number_allowed" type="text" size="1" value="<?php echo esc_attr($item->category_number_allowed); ?>" /></div>
										</div>
					</div>
					<?php do_action('directorypress_package_html', $item); ?>
			</div>
			
			<div class="tab-pane fade" id="dependency-<?php echo $itab_id ?>">
										<div class="row">
											<div class="col-md-12">
												<label><?php _e('Assigned user role', 'directorypress-payment-manager'); ?></label>
												<?php echo directorypress_wpml_supported_settings_description(); ?>
												<div>
													<p class="description"><?php _e('Assign specific user role to this package (optional)', 'directorypress-payment-manager'); ?></p>
													<select multiple="multiple" name="who_can_submit[]" class="directorypress-form-control directorypress-form-group directorypress-select2">
														<option value="" <?php echo ((!$item->who_can_submit) ? 'selected' : ''); ?>><?php esc_html_e("Any users", "DIRECTORYPRESS"); ?></option>
														<?php foreach (get_editable_roles() as $role_name => $role_info): ?>
														<option value="<?php echo $role_name; ?>" <?php echo ((in_array($role_name, $item->who_can_submit)) ? 'selected' : ''); ?>><?php echo $role_info['name']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<div class="col-md-12">
												<label><?php _e('Assigned categories', 'directorypress-payment-manager'); ?></label>
												<?php echo directorypress_wpml_supported_settings_description(); ?>
												<div>
													<p class="description"><?php _e('Assign specific Categories to this package (optional)', 'directorypress-payment-manager'); ?></p>
													<?php directorypress_termsSelectList('selected_categories', DIRECTORYPRESS_CATEGORIES_TAX, $item->selected_categories); ?>
												</div>
											</div>
											<div class="col-md-12">
												<div>
													<label><?php _e('Assigned locations', 'directorypress-payment-manager'); ?></label>
													<?php echo directorypress_wpml_supported_settings_description(); ?>
												</div>
												<div>
													<p class="description"><?php _e('Assign specific Locations to this package (optional)', 'directorypress-payment-manager'); ?></p>
													<?php directorypress_termsSelectList('selected_locations', DIRECTORYPRESS_LOCATIONS_TAX, $item->selected_locations); ?>
												</div>
											</div>
											<div class="col-md-12">
												<div>
													<label><?php _e('Assigned content fields', 'directorypress-payment-manager'); ?></label>
												</div>
												<div>
													<p class="description"><?php _e('Assign specific Fields to this package (optional)', 'directorypress-payment-manager'); ?></p>
													<select multiple="multiple" name="fields[]" class="selected_terms_list row form-group directorypress-select2" style="height: 300px">
													<option value="" <?php echo (!$item->fields) ? 'selected' : ''; ?>><?php _e('- Select All -', 'directorypress-payment-manager'); ?></option>
													<option value="0" <?php echo ($item->fields == array(0)) ? 'selected' : ''; ?>><?php _e('- No fields -', 'directorypress-payment-manager'); ?></option>
													<?php foreach ($fields AS $field): ?>
													<?php if (!$field->is_core_field): ?>
													<option value="<?php echo $field->id; ?>" <?php echo (in_array($field->id, $item->fields)) ? 'selected' : ''; ?>><?php echo $field->name; ?></option>

													<?php endif; ?>
													<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>
			</div>
			<div class="tab-pane fade" id="custom-options-<?php echo $itab_id ?>">				
				<div class="form-table">
			<?php 
				//do_action('directorypress_select_field_configuration_html', $field); 
				//if($field->type == 'checkbox'){$field->add_configuration_options($field); }
			?>
			<p><label><?php _e('Selection items:', 'DIRECTORYPRESS'); ?></label></p>
					
			<div id="selection_items_wrapper" class="field-holder">
				<?php
					//var_dump($item->options['selection_items']);
				if (count($item->selection_items)): ?>
					<?php foreach ($item->selection_items AS $key=>$item): ?>
						<div id ="main_<?php echo esc_attr($key); ?>" class="selection_item id_<?php echo esc_attr($key); ?> clearfix">
							<span class="far fa-trash-alt directorypress-delete-selection-item" data-id="<?php echo esc_attr($key); ?>"></span>
							<input name="selection_items[<?php echo esc_attr($key); ?>]" type="text" class="regular-text" value="<?php echo esc_attr($item); ?>" />
							<span class="fas fa-arrows-alt directorypress-move-label"></span>
						</div>
						<script>
							(function($) {
								"use strict";
								$(function() {
									$("#sub_<?php echo esc_attr($key); ?>").appendTo("#main_<?php echo esc_attr($key); ?>");
								});
							})(jQuery);
						</script>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="selection_item id_1">
						<span class="far fa-trash-alt directorypress-delete-selection-item"></span>
						<input name="selection_items[1]" type="text" class="regular-text" value="" />
						<span class="fas fa-arrows-alt directorypress-move-label"></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<input type="button" id="add_selection_item" class="button button-primary" value="<?php esc_attr_e('Add New Option', 'DIRECTORYPRESS'); ?>" />
			</div>
			<div class="id">
				<input type="hidden" name="id" value="">
			</div>
		</form>
	</div>
</div>