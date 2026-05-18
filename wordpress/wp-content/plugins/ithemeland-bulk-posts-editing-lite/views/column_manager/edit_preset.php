<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <?php wp_nonce_field('wpbe_post_nonce'); ?>
    <input type="hidden" name="action" value="wpbe_column_manager_edit_preset">
    <input type="hidden" name="preset_key" id="wpbe-column-manager-edit-preset-key" value="">
    <div class="wpbe-modal" id="wpbe-modal-column-manager-edit-preset">
        <div class="wpbe-modal-container">
            <div class="wpbe-modal-box wpbe-modal-box-lg">
                <div class="wpbe-modal-content">
                    <div class="wpbe-modal-title">
                        <h2><?php esc_html_e('Edit Column Preset', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                        <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                            <i class="wpbe-icon-x"></i>
                        </button>
                    </div>
                    <div class="wpbe-modal-body">
                        <div class="wpbe-wrap">
                            <div class="wpbe-column-manager-new-profile wpbe-mt0">
                                <div class="wpbe-column-manager-new-profile-left">
                                    <label class="wpbe-column-manager-check-all-fields-btn" data-action="edit">
                                        <input type="checkbox" class="wpbe-column-manager-check-all-fields">
                                        <span><?php esc_html_e('Select All', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                    </label>
                                    <input type="text" title="Search Field" data-action="edit" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-bulk-posts-editing-lite'); ?>" class="wpbe-column-manager-search-field">
                                    <div class="wpbe-column-manager-available-fields" data-action="edit">
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>">
                                                        <label>
                                                            <input type="checkbox" data-name="<?php echo esc_attr($column_key); ?>" data-type="field" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wpbe-column-manager-new-profile-middle">
                                    <div class="wpbe-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="edit" class="wpbe-button wpbe-button-lg wpbe-button-square-lg wpbe-button-blue wpbe-column-manager-add-field">
                                                <i class="wpbe-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="wpbe-column-manager-new-profile-right">
                                    <div class="wpbe-column-manager-right-top">
                                        <input type="text" title="Profile Name" class="wpbe-w100p" id="wpbe-column-manager-edit-preset-name" name="preset_name" placeholder="<?php esc_html_e('Profile name ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                                    </div>
                                    <div class="wpbe-column-manager-added-fields wpbe-table-border-radius wpbe-mt10" data-action="edit">
                                        <div class="items"></div>
                                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wpbe-box-loading wpbe-hide">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpbe-modal-footer">
                        <button type="submit" name="edit_preset" class="wpbe-button wpbe-button-blue"><?php esc_html_e('Save Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>