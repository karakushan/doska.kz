<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-text-editor">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-lg">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Content Edit', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-text-editor-item-title" class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <?php wp_editor("", 'wpbe-text-editor'); ?>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-field="" data-item-id="" data-content-type="textarea" id="wpbe-text-editor-apply" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>