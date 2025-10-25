<h2>
	<?php printf(esc_html__('Approve or decline claim of listing "%s"', 'DIRECTORYPRESS'), esc_attr($listing->title())); ?>
</h2>

<?php if ($action == 'show'): ?>
<p><?php printf(esc_html__('User "%s" had claimed this listing.', 'DIRECTORYPRESS'), esc_attr($listing->claim->claimer->display_name)); ?></p>
<?php if ($listing->claim->claimer_message): ?>
<p><?php esc_html_e('Message from claimer:', 'DIRECTORYPRESS'); ?><br /><i><?php echo esc_html($listing->claim->claimer_message); ?></i></p>
<?php endif; ?>
<p><?php esc_html_e('Claimer will receive email notification.', 'DIRECTORYPRESS'); ?></p>

<a href="<?php echo esc_url(admin_url('options.php?page=directorypress_process_claim&listing_id=' . esc_attr($listing->post->ID) . '&claim_action=approve&referer=' . urlencode($referer))); ?>" class="button button-primary"><?php esc_html_e('Approve', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url(admin_url('options.php?page=directorypress_process_claim&listing_id=' . esc_attr($listing->post->ID) . '&claim_action=decline&referer=' . urlencode($referer))); ?>" class="button button-primary"><?php esc_html_e('Decline', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php esc_html_e('Cancel', 'DIRECTORYPRESS'); ?></a>
<?php elseif ($action == 'processed'): ?>
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php esc_html_e('Go back ', 'DIRECTORYPRESS'); ?></a>
<?php endif; ?>

