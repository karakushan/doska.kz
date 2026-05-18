<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-modal" id="wpbe-modal-new-post-taxonomy">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('New Term', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-form-group">
                            <div class="wpbe-new-post-taxonomy-form-group">
                                <label for="wpbe-new-post-taxonomy-name"><?php esc_html_e('Name', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                <input type="text" id="wpbe-new-post-taxonomy-name" placeholder="<?php esc_html_e('Taxonomy Name ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                            </div>
                            <div class="wpbe-new-post-taxonomy-form-group">
                                <label for="wpbe-new-post-taxonomy-slug"><?php esc_html_e('Slug', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                <input type="text" id="wpbe-new-post-taxonomy-slug" placeholder="<?php esc_html_e('Taxonomy Slug ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                            </div>
                            <div class="wpbe-new-post-taxonomy-form-group">
                                <label for="wpbe-new-post-taxonomy-parent"><?php esc_html_e('Parent', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                <select id="wpbe-new-post-taxonomy-parent">
                                    <option value="-1"><?php esc_html_e('None', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="wpbe-new-post-taxonomy-form-group">
                                <label for="wpbe-new-post-taxonomy-description"><?php esc_html_e('Description', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                <textarea id="wpbe-new-post-taxonomy-description" rows="8" placeholder="<?php esc_html_e('Description ...', 'ithemeland-bulk-posts-editing-lite') ?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue" id="wpbe-create-new-post-taxonomy">
                        <?php esc_html_e('Create', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>