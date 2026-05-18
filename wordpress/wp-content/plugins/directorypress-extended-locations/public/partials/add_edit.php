<?php if($item_id){directorypress_renderMessages();} ?>
<div class="directorypress-modal-content wp-clearfix">
	<form class="add-edit" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_locations_depths_nonce');?>
		<div class="row">
			<div class="col-md-12"><label><?php _e('Location Depth Level Name', 'directorypress-extended-locations'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div class="col-md-12"><input name="name" type="text" class="regular-text" value="<?php echo $item->name; ?>" /></div>
			<div class="col-md-12"><?php directorypress_wpml_translation_notification_string(); ?></div>
		</div>
		</br>
		<div class="row">
			<div class="col-md-12"><label><?php _e("show in address string", 'directorypress-extended-locations'); ?></label></div>
			<div class="col-md-12">
				<label class="switch">
					<input type="checkbox" value="1" name="in_address_line" <?php if ($item->in_address_line) echo 'checked'; ?> />
					<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>
