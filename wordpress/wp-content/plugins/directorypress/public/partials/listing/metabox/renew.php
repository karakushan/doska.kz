<h2>
	<?php echo apply_filters('directorypress_renew_option', esc_html__('Renew listing', 'DIRECTORYPRESS'), $listing); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</h2>

<p><?php esc_html_e('Listing will be renewed and raised up to the top of all lists, those ordered by date.', 'DIRECTORYPRESS'); ?></p>

<?php do_action('directorypress_renew_html', $listing); ?>

<?php if ($action == 'show'): ?>
<a href="<?php echo esc_url(admin_url('options.php?page=directorypress_renew&listing_id=' . $listing->post->ID . '&renew_action=renew&referer=' . urlencode($referer))); ?>" class="button button-primary"><?php esc_html_e('Renew listing', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php esc_html_e('Cancel', 'DIRECTORYPRESS'); ?></a>
<?php elseif ($action == 'renew'): ?>
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php esc_html_e('Go back ', 'DIRECTORYPRESS'); ?></a>
<?php endif; ?>

