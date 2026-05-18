<?php

use wpbel\classes\helpers\Sanitizer;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-meta-fields">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Meta Fields', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbe-wrap">
                        <div class="wpbe-tab-middle-content">
                            <div class="wpbe-alert wpbe-alert-default">
                                <span><?php esc_html_e('You can add new posts meta fields in two ways: 1- Individually 2- Get from other post.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                            </div>
                            <?php if (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE) : ?>
                                <?php include WPBEL_VIEWS_DIR . 'alerts/warning-active-pro.php' ?>
                            <?php endif; ?>
                            <div class="wpbe-meta-fields-left">
                                <div class="wpbe-meta-fields-manual">
                                    <label for="wpbe-meta-fields-manual_key_name"><?php esc_html_e('Manually', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <div class="wpbe-meta-fields-manual-field">
                                        <input <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="text" id="wpbe-meta-fields-manual_key_name" placeholder="<?php esc_html_e('Enter Meta Key ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                                        <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-button wpbe-button-square wpbe-button-blue" id="wpbe-add-meta-field-manual" disabled>
                                            <i class="wpbe-icon-plus1 wpbe-m0"></i>
                                        </button>
                                        <div class="wpbe-add-meta-field-message"></div>
                                    </div>
                                </div>
                                <div class="wpbe-meta-fields-automatic">
                                    <label for="wpbe-meta-fields-automatic"><?php esc_html_e('Automatically From post', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <div class="wpbe-meta-fields-automatic-field">
                                        <input <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="text" id="wpbe-add-meta-fields-post-id" placeholder="<?php esc_html_e('Enter Post ID ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                                        <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-button wpbe-button-square wpbe-button-blue" id="wpbe-get-meta-fields-by-post-id">
                                            <i class="wpbe-icon-plus1 wpbe-m0"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                <input type="hidden" name="action" value="wpbe_meta_fields">
                                <div class="wpbe-meta-fields-right" id="wpbe-meta-fields-items">
                                    <p class="wpbe-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php echo wp_kses(sprintf('Please add your meta key manually %s OR %s From another post', '<br>', '<br>'), Sanitizer::allowed_html()); ?></p>
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $meta_field) : ?>
                                            <?php include WPBEL_VIEWS_DIR . 'meta_field/meta_field_item.php'; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="droppable-helper"></div>
                                </div>
                                <div class="wpbe-meta-fields-buttons">
                                    <div class="wpbe-meta-fields-buttons-left">
                                        <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="submit" value="1" name="save_meta_fields" id="save-meta-fields-button" class="wpbe-button wpbe-button-lg wpbe-button-blue">
                                            <?php $img = WPBEL_IMAGES_URL . 'save.svg'; ?>
                                            <img src="<?php echo esc_url($img); ?>" alt="">
                                            <span><?php esc_html_e('Save Fields', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>