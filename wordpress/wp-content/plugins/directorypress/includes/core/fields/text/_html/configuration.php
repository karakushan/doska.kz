<div class="directorypress-modal-content wp-clearfix">
	<form class="config" method="POST" action="">
		<?php wp_nonce_field(DIRECTORYPRESS_PATH, 'directorypress_configure_fields_nonce'); ?>
		<div class="field-holder">
			<div><label><?php esc_html_e('Max length',  'DIRECTORYPRESS'); ?><span class="directorypress-red-asterisk">*</span></label></div>
			<div>
				<input name="max_length" type="text" size="2" value="<?php echo esc_attr($field->max_length); ?>" />
			</div>
		</div>
		<div class="field-holder">
			<div><label><?php esc_html_e('PHP Regex',  'DIRECTORYPRESS'); ?></label><span class="directorypress-red-asterisk">*</span></label></div>
			<div>
				<input class="regular-text" name="regex" type="text" value="<?php echo esc_attr($field->regex); ?>" />
			</div>
		</div>
		<div class="field-holder">
			<div><label for="is_phone"><?php esc_html_e('Is phone field?',  'DIRECTORYPRESS'); ?></label></div>
			<div>
				<label class="switch">
					<input id="is_phone" name="is_phone" type="checkbox" value="1" <?php checked(1, $field->is_phone); ?> />
					<span class="slider"></span>
				</label>
				<p class="description"><?php esc_html_e("if checked, phone tag would be added for mobile devices", 'DIRECTORYPRESS'); ?></p>
			</div>
		</div>
		<div class="id">
			<input type="hidden" name="id" value="">
		</div>
	</form>
</div>