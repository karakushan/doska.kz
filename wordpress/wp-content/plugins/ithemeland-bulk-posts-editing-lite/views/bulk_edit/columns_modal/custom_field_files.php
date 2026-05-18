<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-custom-field-files">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-lg">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Select Files', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-custom-field-files-item-title" class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <button type="button" id="wpbe-modal-custom-field-files-add-file-item" class="wpbe-button wpbe-button-green wpbe-mb10"><?php esc_html_e('Add File', 'ithemeland-bulk-posts-editing-lite'); ?></button>
                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "loading-2.gif"); ?>" class="wpbe-files-loading" id="wpbe-modal-custom-field-files-loading">
                        <div class="wpbe-inline-custom-field-files"></div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" id="wpbe-modal-custom-field-files-apply" data-item-id="" data-field="" data-content-type="custom_field_files" data-update-type="meta_field" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>