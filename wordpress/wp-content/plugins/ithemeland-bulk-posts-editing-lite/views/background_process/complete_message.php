<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<span><?php echo (!empty($complete_message) && !empty($complete_message['message'])) ? esc_html($complete_message['message']) : esc_html_e('Your changes have been applied', 'ithemeland-bulk-posts-editing-lite'); ?></span>