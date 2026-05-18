<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<select class="wpbe-bulk-edit-form-variable" title="<?php esc_attr_e('Select Variable', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="variable">
    <option value=""><?php esc_html_e('Variable', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <option value="title"><?php esc_html_e('Title', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <option value="id"><?php esc_html_e('ID', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <option value="menu_order"><?php esc_html_e('Menu Order', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <option value="parent_id"><?php esc_html_e('Parent ID', 'ithemeland-bulk-posts-editing-lite'); ?></option>
    <option value="parent_title"><?php esc_html_e('Parent Title', 'ithemeland-bulk-posts-editing-lite'); ?></option>
</select>