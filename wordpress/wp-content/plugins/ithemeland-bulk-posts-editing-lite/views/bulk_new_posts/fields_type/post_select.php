<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select id="<?php echo esc_attr($field_id); ?>"
    class="wpbe-select2 wpbe-get-posts-ajax wpbe-ml5"
    <?php echo (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled"' : ''; ?>>
</select>