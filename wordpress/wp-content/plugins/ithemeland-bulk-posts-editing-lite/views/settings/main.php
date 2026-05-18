<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-settings">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Settings', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="display: inline-block; height: 100%;">
                    <?php wp_nonce_field('wpbe_post_nonce'); ?>
                    <input type="hidden" name="action" value="wpbe_settings">
                    <div class="wpbe-float-side-modal-body">
                        <div class="wpbe-wrap">
                            <div class="wpbe-tab-middle-content">
                                <div class="wpbe-alert wpbe-alert-default">
                                    <span><?php esc_html_e('You can set bulk editor settings', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-count-per-page"><?php esc_html_e('Count Per Page', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[count_per_page]" id="wpbe-settings-count-per-page" title="The number of posts per page">
                                        <?php
                                        if (!empty($count_per_page_items)) :
                                            foreach ($count_per_page_items as $count_per_page_item) :
                                        ?>
                                                <option value="<?php echo intval(esc_attr($count_per_page_item)); ?>" <?php if (isset($settings['count_per_page']) && $settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                                    <?php echo esc_html($count_per_page_item); ?>
                                                </option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-default-sort-by"><?php esc_html_e('Default Sort By', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbe-settings-default-sort-by" class="wpbe-input-md" name="settings[default_sort_by]">
                                        <option value="id" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'id') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ID', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="title" <?php echo (isset($settings['default_sort_by']) && $settings['default_sort_by'] == 'title') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Title', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-default-sort"><?php esc_html_e('Default Sort', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[default_sort]" id="wpbe-settings-default-sort" class="wpbe-input-md">
                                        <option value="ASC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'ASC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('ASC', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="DESC" <?php echo (isset($settings['default_sort']) && $settings['default_sort'] == 'DESC') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('DESC', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-close-popup-after-applying"><?php esc_html_e('Close popup after applying', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[close_popup_after_applying]" id="wpbe-settings-close-popup-after-applying" class="wpbe-input-md">
                                        <option value="yes" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['close_popup_after_applying']) && $settings['close_popup_after_applying'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-sticky-first-columns"><?php esc_html_e("Sticky 'ID' & 'Title' Columns", 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[sticky_first_columns]" id="wpbe-settings-sticky-first-columns" class="wpbe-input-md">
                                        <option value="yes" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['sticky_first_columns']) && $settings['sticky_first_columns'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-display-full-columns-title"><?php esc_html_e('Display Columns Label', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[display_full_columns_title]" id="wpbe-settings-display-full-columns-title" class="wpbe-input-md">
                                        <option value="yes" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Completely', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['display_full_columns_title']) && $settings['display_full_columns_title'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('In short', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-display-cell-content"><?php esc_html_e('Display cell content', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[display_cell_content]" id="wpbe-settings-display-cell-content" class="wpbe-input-md">
                                        <option value="long" <?php echo (isset($settings['display_cell_content']) && $settings['display_cell_content'] == 'long') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Long text', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="short" <?php echo (isset($settings['display_cell_content']) && $settings['display_cell_content'] == 'short') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Short text', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                    <p class="wpbe-settings-description"><?php esc_html_e("If choose 'Short text' the cell content will be trimmed", 'ithemeland-bulk-posts-editing-lite'); ?></p>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-keep-filled-data-in-bulk-edit-form"><?php esc_html_e('Keep filled data in bulk edit form', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[keep_filled_data_in_bulk_edit_form]" id="wpbe-settings-keep-filled-data-in-bulk-edit-form" class="wpbe-input-md">
                                        <option value="yes" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['keep_filled_data_in_bulk_edit_form']) && $settings['keep_filled_data_in_bulk_edit_form'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="wpbe-form-group">
                                    <label for="wpbe-settings-enable-background-processing"><?php esc_html_e('Enable Background Processing', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select name="settings[enable_background_processing]" id="wpbe-settings-enable-background-processing" class="wpbe-input-md">
                                        <option value="yes" <?php echo (isset($settings['enable_background_processing']) && $settings['enable_background_processing'] == 'yes') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Yes', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                        <option value="no" <?php echo (isset($settings['enable_background_processing']) && $settings['enable_background_processing'] == 'no') ? 'selected' : ''; ?>>
                                            <?php esc_html_e('No', 'ithemeland-bulk-posts-editing-lite'); ?>
                                        </option>
                                    </select>
                                    <p class="wpbe-settings-description">If you enable this option, all the heavy and time-consuming operations are executed as Background Processing until the end, and you are safe from the <strong>"Error 524: A Timeout Occurred"</strong> message.
                                        <br>
                                        Note) You will not be able to access other parts of the plugin while the operation is running.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpbe-float-side-modal-footer">
                        <button type="submit" class="wpbe-button wpbe-button-blue">
                            <?php $img = WPBEL_IMAGES_URL . 'save.svg'; ?>
                            <img src="<?php echo esc_url($img); ?>" alt="">
                            <span><?php esc_html_e('Save Changes', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>