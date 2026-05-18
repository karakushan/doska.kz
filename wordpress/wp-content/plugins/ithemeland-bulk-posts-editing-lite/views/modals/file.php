<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-file">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-lg">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Select File', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-select-file-item-title" class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-inline-select-files">
                            <div class="wpbe-modal-select-files-file-item">
                                <input type="text" class="wpbe-inline-edit-file-url wpbe-w60p" id="wpbe-file-url" placeholder="File Url ..." value="">
                                <button type="button" class="wpbe-button wpbe-button-white wpbe-open-uploader wpbe-inline-edit-choose-file" data-type="single" data-target="inline-file-custom-field"><?php esc_html_e('Choose File', 'ithemeland-bulk-posts-editing-lite'); ?></button>
                                <input type="hidden" id="wpbe-file-id" value="">
                                <button type="button" class="wpbe-button wpbe-button-white" id="wpbe-modal-file-clear"><?php esc_html_e('Clear', 'ithemeland-bulk-posts-editing-lite'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" id="wpbe-modal-file-apply" data-item-id="" data-field="" data-content-type="file" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>