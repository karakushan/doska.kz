<script>
jQuery(document).on('change', '#attachment-field-<?php echo esc_attr($field->id); ?>', function() {
	const bytes = directorypress_js_instance.max_attchment_size * 1024;				
	if(this.files[0].size > bytes){
       alert(this.files[0].name+' '+directorypress_js_instance.max_attchment_size_error +' '+directorypress_js_instance.max_attchment_size +' '+directorypress_js_instance.max_attchment_size_unit);
    }
});
</script>
<div class="field-wrap field-input-item submit_field_id_<?php echo $field->id; ?> field-type-<?php echo $field->type; ?> clearfix">
	<p class="directorypress-submit-field-title">
		<?php echo $field->name; ?>
		<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
		<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
		<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_attachment'); ?>
	</p>
		<div class="row">
			<div class="col-12">
				<label class="input-file-upload" for="directorypress-field-input-<?php echo $field->id; ?>">
					<input id="attachment-field-<?php echo $field->id; ?>" type="file" name="directorypress-field-input-<?php echo $field->id; ?>" class="directorypress-field-input-fileupload" />
					<i class="fa fa-cloud-upload"></i>
					<?php _e('Upload:', 'directorypress-advanced-fields'); ?>
				</label>
				<?php if ($field->use_text): ?>
					<label><input type="text" name="directorypress-field-input-text-<?php echo $field->id; ?>" placeholder="<?php _e('File title:', 'directorypress-advanced-fields'); ?>" class="form-control" value="<?php echo esc_attr($field->value['text']); ?>" /></label>
				<?php endif; ?>
				<?php if ($file): ?>
					<label><input type="checkbox" name="directorypress-reset-file-<?php echo $field->id; ?>" value="1" /> <?php _e('reset uploaded file', 'directorypress-advanced-fields'); ?></label>
				<?php endif; ?>
			</div>
			<?php if ($file): ?>
			<div class="col-12">
				<a href="<?php echo esc_url($file->guid); ?>" target="_blank"><?php echo basename($file->guid); ?></a>
				<input type="hidden" name="directorypress-uploaded-file-<?php echo $field->id; ?>" value="<?php echo $file->ID; ?>" />
			</div>
			<?php endif; ?>
		</div>
</div>