<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-item-duplicate">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Duplicate', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-modal-body-content">
                        <div class="wpbe-wrap">
                            <div class="wpbe-form-group">
                                <label class="wpbe-label-big" for="wpbe-bulk-edit-duplicate-number">
                                    <?php esc_html_e('Enter how many item(s) to Duplicate!', 'ithemeland-bulk-posts-editing-lite'); ?>
                                </label>
                                <input type="number" class="wpbe-input-numeric-sm" id="wpbe-bulk-edit-duplicate-number" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue" id="wpbe-bulk-edit-duplicate-start">
                        <?php esc_html_e('Start Duplicate', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>