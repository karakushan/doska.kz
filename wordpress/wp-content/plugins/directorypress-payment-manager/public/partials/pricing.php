<?php 
if($id){directorypress_renderMessages();}
 ?>
<div class="directorypress-modal-content wp-clearfix">
		<form class="add-edit" method="POST" action="">
			<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_packages_nonce');?>
					
				<div class="row clearfix">
					<div class="col-md-12">
						<label><?php _e('Regular Price', 'directorypress-payment-manager'); ?><span class="directorypress-red-asterisk">*</span></label>
					</div>
					<div class="col-md-12">
						<input name="_regular_price" type="text" class="regular-text" value="<?php echo $values['regular_price']; ?>" />
						<?php directorypress_wpml_translation_notification_string(); ?>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-12">
						<label><?php _e('Sale Price', 'directorypress-payment-manager'); ?></label>
					</div>
					<div class="col-md-12">
						<input name="_sale_price" type="text" class="regular-text" value="<?php echo $values['sale_price']; ?>" />
						<?php directorypress_wpml_translation_notification_string(); ?>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-md-12">
						<label><?php _e('Bumpup Price', 'directorypress-payment-manager'); ?></label>
					</div>
					<div class="col-md-12">
						<input name="_bumpup_price" type="text" class="regular-text" value="<?php echo $values['bumpup_price']; ?>" />
						<?php directorypress_wpml_translation_notification_string(); ?>
					</div>
				</div>
			<div class="id">
				<input type="hidden" name="id" value="">
			</div>
		</form>
</div>