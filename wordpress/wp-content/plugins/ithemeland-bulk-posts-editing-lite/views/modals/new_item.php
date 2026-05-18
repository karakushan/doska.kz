<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

use wpbel\classes\helpers\Sanitizer;
?>

<div class="wpbe-modal" id="wpbe-modal-new-item">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2 id="wpbe-new-item-title"></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-form-group">
                            <label class="wpbe-label-big" for="wpbe-new-item-count" id="wpbe-new-item-description"></label>
                            <input type="number" class="wpbe-input-numeric-sm wpbe-m0" id="wpbe-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                        </div>
                        <div id="wpbe-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="wpbe-form-group">
                                        <?php echo wp_kses($extra_field['label'], Sanitizer::allowed_html()); ?>
                                        <?php echo wp_kses($extra_field['field'], Sanitizer::allowed_html()); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue" id="wpbe-create-new-item"><?php esc_html_e('Create', 'ithemeland-bulk-posts-editing-lite'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>