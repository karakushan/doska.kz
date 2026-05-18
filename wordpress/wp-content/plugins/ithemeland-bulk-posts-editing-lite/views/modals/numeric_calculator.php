<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-numeric-calculator">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Calculator', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-numeric-calculator-item-title" class="wpbe-modal-product-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <select id="wpbe-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemeland-bulk-posts-editing-lite'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-bulk-posts-editing-lite'); ?>" id="wpbe-numeric-calculator-value" title="<?php esc_html_e('Value', 'ithemeland-bulk-posts-editing-lite'); ?>">
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wpbe-button wpbe-button-blue wpbe-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>