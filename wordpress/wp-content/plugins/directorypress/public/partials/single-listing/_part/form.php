<form method="POST" action="<?php the_permalink($listing->post->ID); ?>#contact-tab" id="directorypress_contact_form">
	<input type="hidden" name="listing_id" id="contact_listing_id" value="<?php echo esc_attr($listing->post->ID); ?>" />
	<input type="hidden" name="contact_nonce" id="contact_nonce" value="<?php print esc_attr(wp_create_nonce('directorypress_contact_nonce')); ?>" />
	<h3><?php
			printf(esc_html__('Send message to %s', 'DIRECTORYPRESS'), esc_attr(get_the_author_meta( 'nickname', $listing->post->post_author)) );
	?></h3>
	<h5 id="contact_warning" style="display: none; color: red;"></h5>
	<div class="directorypress-contact-form">
		<?php if (is_user_logged_in()): ?>
		<p>
			<?php printf(esc_html__('You are currently logged in as %s. Your message will be sent using your logged in name and email.', 'DIRECTORYPRESS'), esc_attr(wp_get_current_user()->user_login)); ?>
			<input type="hidden" name="contact_name" id="contact_name" />
			<input type="hidden" name="contact_email" id="contact_email" />
		</p>
		<?php else: ?>
		<p>
			<label for="contact_name"><?php esc_html_e('Contact Name', 'DIRECTORYPRESS'); ?><span class="red-asterisk">*</span></label>
			<input type="text" name="contact_name" id="contact_name" class="form-control" value="<?php echo esc_attr(directorypress_get_input_value($_POST, 'contact_name')); ?>" size="35" />
		</p>
		<p>
			<label for="contact_email"><?php esc_html_e("Contact Email", "DIRECTORYPRESS"); ?><span class="red-asterisk">*</span></label>
			<input type="text" name="contact_email" id="contact_email" class="form-control" value="<?php echo esc_attr(directorypress_get_input_value($_POST, 'contact_email')); ?>" size="35" />
		</p>
		<?php endif; ?>
		<p>
			<label for="contact_message"><?php esc_html_e("Your message", "DIRECTORYPRESS"); ?><span class="red-asterisk">*</span></label>
			<textarea name="contact_message" id="contact_message" class="form-control" rows="6"><?php echo esc_textarea(directorypress_get_input_value($_POST, 'contact_message')); ?></textarea>
		</p>
		
		<?php echo wp_kses_post(directorypress_has_recaptcha()); ?>
		
		<input type="submit" name="submit" class="directorypress-send-message-button btn btn-primary" value="<?php esc_attr_e('Send message', 'DIRECTORYPRESS'); ?>" />
	</div>
</form>