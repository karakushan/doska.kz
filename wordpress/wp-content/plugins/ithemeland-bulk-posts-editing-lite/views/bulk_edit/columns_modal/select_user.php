<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-select-user">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Select User', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-inline-select-user">
                            <div style="font-size: 13px; padding: 0 0 10px 0;">
                                <strong>Current User: <span id="wpbe-modal-select-user-current"></span></strong>
                            </div>
                            <div>
                                <select id="wpbe-modal-select-user-input" class="wpbe-select2-users" style="width: 100%; height:43px;"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-item-id="" data-field="post_author" data-name="post_author" data-content-type="select_user" data-update-type="wp_posts_field" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>