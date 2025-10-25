<?php 
	global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
	$index = $search_field->field->id . '_' . $search_form->form_id;
?>
<div class="search-element-col field-id-<?php echo $search_field->field->id; ?> field-form-id-<?php echo $search_form->form_id; ?> unique-form-field-id-<?php echo $index; ?> field-type-<?php echo $search_field->field->type; ?> pull-left" style=" width:<?php echo esc_attr($search_field->field_width($search_form)); ?>%; padding:0 <?php echo esc_attr($search_form->args['gap_in_fields']); ?>px;">
	<?php $search_field->field_label($search_form); ?>
	<div class="search-field-content-wrapper">
		<input type="text" class="form-control" name="field_<?php echo $search_field->field->slug; ?>" placeholder="<?php printf(esc_attr__('Enter %s', 'directorypress-advanced-fields'), $search_field->field->name); ?>" value="<?php echo esc_attr($search_field->value); ?>" />
	</div>
</div>