<?php esc_html_e('Listing title:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($listing_title); ?>

<?php esc_html_e('Listing URL:', 'DIRECTORYPRESS'); ?> <?php echo esc_url($listing_url); ?>

<?php esc_html_e('Name:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($name); ?>

<?php esc_html_e('Email:', 'DIRECTORYPRESS'); ?> <?php echo esc_html($email); ?>

<?php esc_html_e('Message:', 'DIRECTORYPRESS'); ?>


<?php echo wp_kses_post($message); ?>