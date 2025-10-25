<script>
	(function($) {
		"use strict";
	
		$(function() {
	
			<?php if (!$field->is_core_field): ?>
			$("#type").change(function() {
				if (
					<?php
					foreach ($fields->fields_types_names AS $field_type=>$field_name){
						$field_class_name = 'directorypress_field_' . esc_attr($field_type);
						if (class_exists($field_class_name)) {
							$_field = new $field_class_name;
							if (!$_field->is_this_field_orderable()) {
					?>
					$(this).val() == '<?php echo esc_attr($field_type); ?>' ||
					<?php
							}
						}
					} ?>
				'x'=='y')
					$("#is_ordered_block").hide();
				else
					$("#is_ordered_block").show();
	
				if (
					<?php
					foreach ($fields->fields_types_names AS $field_type=>$field_name){
						$field_class_name = 'directorypress_field_' . $field_type;
						if (class_exists($field_class_name)) {
							$_field = new $field_class_name;
							if (!$_field->is_this_field_requirable()) {
					?>
					$(this).val() == '<?php echo esc_attr($field_type); ?>' ||
					<?php
							}
						}
					} ?>
				'x'=='y')
					$("#is_required_block").hide();
				else
					$("#is_required_block").show();
			});
			<?php endif; ?>	
		});
	})(jQuery);
</script>
<script>
				(function($) {
					"use strict";
	
					$(function() {
						<?php if (!$field->is_core_field): ?>
						$("#type").change(function() {
							if (
								<?php 
								foreach ($fields->fields_types_names AS $field_type=>$field_name){
									$field_class_name = 'directorypress_field_' . $field_type;
									if (class_exists($field_class_name)) {
										$_field = new $field_class_name;
										if (!$_field->is_this_field_searchable()) {
								?>
								$(this).val() == '<?php echo esc_attr($field_type); ?>' ||
								<?php
										}
									}
								} ?>
							$(this).val() === '')
								$(".can_be_searched_block").hide();
							else
								$(".can_be_searched_block").show();
						});
						$("#on_search_form").click( function() {
							if ($(this).is(':checked'))
								$('input[name="advanced_search_form"]').removeAttr('disabled');
							else 
								$('input[name="advanced_search_form"]').attr('disabled', true);
						});
						$("#on_search_form_archive").click( function() {
							if ($(this).is(':checked'))
								$('input[name="advanced_archive_search_form"]').removeAttr('disabled');
							else 
								$('input[name="advanced_archive_search_form"]').attr('disabled', true);
						});
						$("#on_search_form_widget").click( function() {
							if ($(this).is(':checked'))
								$('input[name="advanced_widget_search_form"]').removeAttr('disabled');
							else 
								$('input[name="advanced_widget_search_form"]').attr('disabled', true);
						});
						<?php endif; ?>
					});
				})(jQuery);
			</script>
<?php 
$itab_id = uniqid(); 
directorypress_renderMessages();
//svar_dump($field);
?>
<div class="directorypress-modal-content wp-clearfix">
	<ul class="nav nav-tabs" id="tabContent">
		<li class="nav-item"><a class="nav-link active" href="#tab-general" data-bs-toggle="tab"><?php esc_html_e('General', 'DIRECTORYPRESS'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#tab-display" data-bs-toggle="tab"><?php esc_html_e('Display', 'DIRECTORYPRESS'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#tab-labels" data-bs-toggle="tab"><?php esc_html_e('Labels', 'DIRECTORYPRESS'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#tab-visibility" data-bs-toggle="tab"><?php esc_html_e('Visibility', 'DIRECTORYPRESS'); ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#tab-dependency" data-bs-toggle="tab"><?php esc_html_e('Dependency', 'DIRECTORYPRESS'); ?></a></li>
	</ul>
	<div class="tab-content">
		<form class="add-edit" method="POST" action="">
			<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_fields_nonce');?>

			<div id="tab-general" class="tab-pane fade active show">
				
					<div class="accordion-body">

						<div class="field-holder">
							<div><?php esc_html_e('Field Title', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></div>
							<input name="name" id="field_name" type="text" class="regular-text" value="<?php echo esc_attr($field->name); ?>" />
							<?php directorypress_wpml_translation_notification_string(); ?>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Custom title for search form', 'DIRECTORYPRESS'); ?></div>
							<input name="field_search_label" id="field_search_label" type="text" class="regular-text" value="<?php echo esc_attr($field->field_search_label); ?>" />
							<?php directorypress_wpml_translation_notification_string(); ?>
						</div>
						<?php if ($field->is_slug()) :?>
							<div class="field-holder">
								<div><?php esc_html_e('Slug', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></div>
								<?php if($field->slug == 'price'): ?>
									<div class="alert alert-info"><?php echo esc_html__('core price filed slug can not be changed!', 'DIRECTORYPRESS'); ?></div>
									<input name="slug" id="field_slug" type="hidden" class="regular-text" value="<?php echo esc_attr($field->slug); ?>" />
								<?php else: ?>
									<input name="slug" id="field_slug" type="text" class="regular-text" value="<?php echo esc_attr($field->slug); ?>" />
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="field-holder">
							<?php $fields_types_names = $fields->fields_types_names; ?>
							<div><?php esc_html_e('Field Type', 'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></div>
							<select name="type" id="type" <?php disabled($field->is_core_field); ?>>
								<option value=""><?php esc_html_e('- Select field type -', 'DIRECTORYPRESS'); ?></option>
								<?php if ($field->is_core_field) :?>
									<option value="summary" <?php selected($field->type, 'summary'); ?> ><?php echo esc_html($fields_types_names['summary']); ?></option>
									<option value="content" <?php selected($field->type, 'content'); ?> ><?php echo esc_html($fields_types_names['content']); ?></option>
									<option value="categories" <?php selected($field->type, 'categories'); ?> ><?php echo esc_html($fields_types_names['categories']); ?></option>
									<option value="tags" <?php selected($field->type, 'tags'); ?> ><?php echo esc_html($fields_types_names['tags']); ?></option>
									<option value="address" <?php selected($field->type, 'address'); ?> ><?php echo esc_html($fields_types_names['address']); ?></option>
									<option value="status" <?php selected($field->type, 'status'); ?> ><?php echo esc_html($fields_types_names['status']); ?></option>
								<?php endif; ?>
								<option value="text" <?php selected($field->type, 'text'); ?> ><?php echo esc_html($fields_types_names['text']); ?></option>
								<option value="textarea" <?php selected($field->type, 'textarea'); ?> ><?php echo esc_html($fields_types_names['textarea']); ?></option>
								<option value="select" <?php selected($field->type, 'select'); ?> ><?php echo esc_html($fields_types_names['select']); ?></option>
								<option value="link" <?php selected($field->type, 'link'); ?> ><?php echo esc_html($fields_types_names['link']); ?></option>
								<option value="email" <?php selected($field->type, 'email'); ?> ><?php echo esc_html($fields_types_names['email']); ?></option>
								<option value="price" <?php selected($field->type, 'price'); ?> ><?php echo esc_html($fields_types_names['price']); ?></option>
								<?php do_action('directorypress_fields_types_options', $field, $fields_types_names); ?>
							</select>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Field description', 'DIRECTORYPRESS'); ?></div>
							<textarea name="description" cols="60" rows="4" ><?php echo esc_textarea($field->description); ?></textarea>
							<?php directorypress_wpml_translation_notification_string(); ?>
						</div>
					</div>
				
			</div>
			
			<div id="tab-display" class="tab-pane fade">
					<div class="accordion-body">
						<div class="field-holder">
							<div><?php esc_html_e('Field width on Search Form', 'DIRECTORYPRESS'); ?></div>
							<input name="fieldwidth" id="field_width" type="text" class="regular-text" value="<?php echo esc_attr($field->fieldwidth); ?>" />
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Field width on Archive Search Form', 'DIRECTORYPRESS'); ?></div>
							<input name="fieldwidth_archive" id="field_width_archive" type="text" class="regular-text" value="<?php echo esc_attr($field->fieldwidth_archive); ?>" />
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Field Icon', 'DIRECTORYPRESS'); ?></div>
							<span class="directorypress-icon-tag"></span>
							<input type="text" name="icon_image" id="icon_image" value="<?php echo esc_attr($field->icon_image); ?>">
							<div><p><?php esc_html_e('Add an icon class e.g fas fa-heart', 'DIRECTORYPRESS'); ?></p></div>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Display inline on Grid/List view', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="is_field_in_line" type="checkbox" value="1" <?php checked($field->is_field_in_line); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Show On Grid view', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="on_exerpt_page" type="checkbox" value="1" <?php checked($field->on_exerpt_page); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Show On List view', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="on_exerpt_page_list" type="checkbox" value="1" <?php checked($field->on_exerpt_page_list); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Show On Detail Page', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="on_listing_page" type="checkbox" value="1" <?php checked($field->on_listing_page); ?> />
								<span class="slider"></span>
							</label>
						</div>
					</div>
			</div>
			
			
			<div id="tab-labels" class="tab-pane fade">
					<div class="accordion-body">
						<div class="field-holder">
							<div><?php esc_html_e('Label on Grid view', 'DIRECTORYPRESS'); ?></div>
							<select name="is_hide_name_on_grid" id="is_hide_name_on_grid">
								
								<option value="hide" <?php selected($field->is_hide_name_on_grid, 'hide'); ?> ><?php esc_html_e("Hide", 'DIRECTORYPRESS'); ?></option>
								<option value="show_only_label" <?php selected($field->is_hide_name_on_grid, 'show_only_label'); ?> ><?php esc_html_e("Show only label", 'DIRECTORYPRESS'); ?></option>
								<option value="show_icon_label" <?php selected($field->is_hide_name_on_grid, 'show_icon_label'); ?> ><?php esc_html_e("Show icon and label", 'DIRECTORYPRESS'); ?></option>
								<option value="show_only_icon" <?php selected($field->is_hide_name_on_grid, 'show_only_icon'); ?> ><?php esc_html_e("Show only icon", 'DIRECTORYPRESS'); ?></option>
							</select>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Label on List view', 'DIRECTORYPRESS'); ?></div>
							<select name="is_hide_name_on_list" id="is_hide_name_on_list">
								
								<option value="hide" <?php selected($field->is_hide_name_on_list, 'hide'); ?> ><?php esc_html_e("Hide", 'DIRECTORYPRESS'); ?></option>
								<option value="show_only_label" <?php selected($field->is_hide_name_on_list, 'show_only_label'); ?> ><?php esc_html_e("Show only label", 'DIRECTORYPRESS'); ?></option>
								<option value="show_icon_label" <?php selected($field->is_hide_name_on_list, 'show_icon_label'); ?> ><?php esc_html_e("Show icon and label", 'DIRECTORYPRESS'); ?></option>
								<option value="show_only_icon" <?php selected($field->is_hide_name_on_list, 'show_only_icon'); ?> ><?php esc_html_e("Show only icon", 'DIRECTORYPRESS'); ?></option>
							</select>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Hide Label on Detail page', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="is_hide_name" type="checkbox" value="1" <?php checked($field->is_hide_name); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Hide Label on Search', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="is_hide_name_on_search" type="checkbox" value="1" <?php checked($field->is_hide_name_on_search); ?> />
								<span class="slider"></span>
							</label>
						</div>
					</div>
			</div>
			<div id="tab-visibility" class="tab-pane fade">
					<div class="accordion-body">
						<div class="field-holder" id="is_required_block" <?php if (!$field->is_this_field_requirable()): ?>style="display: none;"<?php endif; ?>>
							<div><?php esc_html_e('Is required?', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="is_required" type="checkbox" value="1" <?php checked($field->is_required); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder" id="is_ordered_block" <?php if (!$field->is_this_field_orderable()): ?>style="display: none;"<?php endif; ?>>
							<div><?php esc_html_e('Show in Sorting', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="is_ordered" type="checkbox" value="1" <?php checked($field->is_ordered); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder">
							<div><?php esc_html_e('Show on Map', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="on_map" type="checkbox" value="1" <?php checked($field->on_map); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder can_be_searched_block" <?php if (!$field->is_this_field_searchable()): ?>style="display: none;"<?php endif; ?>>
							<div><?php esc_html_e('Show on Search Form', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input id="on_search_form" name="on_search_form" type="checkbox" value="1" <?php checked($field->on_search_form); ?> />
								<span class="slider"></span>
							</label>
						</div>
						<div class="field-holder can_be_searched_block" <?php if (!$field->is_this_field_searchable()): ?>style="display: none;"<?php endif; ?>>
							<div><?php esc_html_e('Show in Advanced filters?', 'DIRECTORYPRESS'); ?></div>
							<label class="switch">
								<input name="advanced_search_form" type="checkbox" value="1" <?php checked($field->advanced_search_form); ?> <?php disabled(!$field->on_search_form)?> />
								<span class="slider"></span>
							</label>
						</div>
						<?php do_action('directorypress_field_html', $field); ?>
					</div>
			</div>
			<?php if ($field->is_categories()): ?>
				
				<div id="tab-dependency" class="tab-pane fade">
						<div class="accordion-body">
							<div class="field-holder">													
								<div><?php esc_html_e('Assigned categories', 'DIRECTORYPRESS'); ?></div>
								<?php directorypress_termsSelectList('categories', DIRECTORYPRESS_CATEGORIES_TAX, $field->categories); ?>
							</div>
						</div>
				</div>
			<?php endif; ?>
			<div class="id">
				<input type="hidden" name="id" value="">
			</div>
		</form>
	</div>
</div>		