<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-select-post">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Select Post', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-select-post-item-title" class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-inline-select-post">
                            <select id="wpbe-select-post-value" class="wpbe-select2 wpbe-get-posts-ajax"></select>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-content-type="select_post" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>