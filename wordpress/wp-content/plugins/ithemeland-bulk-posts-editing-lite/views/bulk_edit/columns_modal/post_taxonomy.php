<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-post-taxonomy">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_attr_e('Taxonomy Edit', 'ithemeland-bulk-posts-editing-lite'); ?> - <span class="wpbe-modal-item-title"></span></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-wrap">
                    <div class="wpbe-modal-top-search">
                        <input class="wpbe-search-in-list" title="Type for search" data-target=".wpbe-modal-post-taxonomy-terms-list label" type="text" placeholder="<?php esc_attr_e('Type for search', 'ithemeland-bulk-posts-editing-lite'); ?> ...">
                    </div>
                </div>
                <div class="wpbe-modal-body">
                    <div style="width: 100%; float: left; text-align: center;">
                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "loading-2.gif"); ?>" class="wpbe-modal-post-taxonomy-loading" alt="loading...">
                    </div>
                    <div class="wpbe-wrap">
                        <div class="wpbe-modal-post-taxonomy-terms-list">

                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" data-item-id="" data-update-type="taxonomy" data-name="" data-toggle="modal-close" class="wpbe-button wpbe-button-blue wpbe-inline-edit-taxonomy-save">
                        <?php esc_attr_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                    <button type="button" class="wpbe-button wpbe-button-white wpbe-inline-edit-add-new-taxonomy" data-item-id="" data-item-name="" data-field="" data-toggle="modal" data-target="#wpbe-modal-new-post-taxonomy">
                        <?php esc_attr_e('Add New', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>