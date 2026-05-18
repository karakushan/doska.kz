<?php global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS; ?>
<div class="search-element-col field-id-<?php echo $search_field->field->id; ?> field-form-id-<?php echo $search_form->form_id; ?> unique-form-field-id-<?php echo $search_field->field->id; ?>_<?php echo $search_form->form_id; ?> field-type-<?php echo $search_field->field->type; ?> pull-left" style=" width:<?php echo esc_attr($search_field->field_width($search_form)); ?>%; padding:0 <?php echo esc_attr($search_form->args['gap_in_fields']); ?>px;">
	<?php $search_field->field_label($search_form); ?>
	<div class="search-field-content-wrapper">
		<input type="text" name="field_<?php echo $search_field->field->slug; ?>" class="form-control" value="<?php echo esc_attr($search_field->value); ?>" />
	</div>
</div>