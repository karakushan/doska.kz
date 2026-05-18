<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-image">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Image Edit', 'ithemeland-bulk-posts-editing-lite'); ?> - <span id="wpbe-modal-image-item-title" class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-modal-body-content">
                        <div class="wpbe-wrap">
                            <div class="wpbe-inline-image-edit">
                                <button type="button" class="wpbe-inline-uploader wpbe-open-uploader" data-target="inline-edit" data-type="single" data-id="" data-item-id="">
                                    <i class="wpbe-icon-pencil"></i>
                                </button>
                                <div class="wpbe-inline-image-preview" data-image-preview-id=""></div>
                                <input type="hidden" id="" class="wpbe-image-preview-hidden-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-button-type="save" data-content-type="image" class="wpbe-button wpbe-button-blue wpbe-edit-action-with-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                    <button type="button" class="wpbe-button wpbe-button-red wpbe-edit-action-with-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(WPBEL_IMAGES_URL . "no-image.png"); ?>" data-field="" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>