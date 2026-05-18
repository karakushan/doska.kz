<?php global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS; ?>
<?php if (count($search_field->min_max_options)): ?>
<div class="search-element-col field-id-<?php echo $search_field->field->id; ?> field-form-id-<?php echo $search_form->form_id; ?> unique-form-field-id-<?php echo $search_field->field->id; ?>_<?php echo $search_form->form_id; ?> field-type-<?php echo $search_field->field->type; ?> pull-left" style=" width:<?php echo esc_attr($search_field->field_width($search_form)); ?>%; padding:0 <?php echo esc_attr($search_form->args['gap_in_fields']); ?>px;">
	<?php $search_field->field_label($search_form); ?>
	<select name="field_<?php echo $search_field->field->slug; ?>" class="search-field-content-wrapper form-control directorypress-select2">
		<option value=""><?php printf(__('%s Range Options', 'directorypress-advanced-fields'), $search_field->field->name); ?></option>
		<?php foreach ($search_field->field->range_options AS $key=>$item): ?>
			<option value="<?php echo esc_attr($key); ?>"><?php echo $item; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php endif; ?>