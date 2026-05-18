<?php

use wpbel\classes\helpers\Post_Helper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-bulk-new-posts">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk New Posts Form', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>

                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-form-group wpbe-quantity" data-name="menu_order" data-type="wp_posts_field">
                            <label for="wpbe-bulk-new-form-post-quantity"><?php esc_html_e('Quantity', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                            <input type="number" placeholder="<?php esc_attr_e('Quantity', 'ithemeland-bulk-posts-editing-lite'); ?>" data-field="value" id="wpbe-bulk-new-form-post-quantity" class="wpbe-input-md" value="1">
                            <span class="wpbe-description-full-width">
                                <?php esc_html_e('If you want to create a raw post, leave the following fields empty. Otherwise, new post(s) will be created with the entered values.
                            ', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                        </div>
                        <div class="wpbe-tabs">
                            <div class="wpbe-tabs-navigation">
                                <nav class="wpbe-tabs-navbar">
                                    <ul class="wpbe-tabs-list" data-content-id="wpbe-bulk-new-tabs">
                                        <li><a class="wpbe-tab-item selected" data-content="general" href="#"><?php esc_html_e('General', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <?php if (Post_Helper::get_active_post_type() != 'page') : ?>
                                            <li>
                                                <a class="wpbe-tab-item" data-content="categories-tags-taxonomies" href="#">
                                                    <?php esc_html_e('Categories/Tags/Taxonomies', 'ithemeland-bulk-posts-editing-lite'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a class="wpbe-tab-item" data-content="date-type" href="#"><?php esc_html_e('Date & Type', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wpbe-tabs-contents wpbe-mt30" id="wpbe-bulk-new-tabs">
                                <div class="selected wpbe-tab-content-item" data-content="general">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_new_posts/general.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="categories-tags-taxonomies">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_new_posts/taxonomies.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="date-type">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_new_posts/date_type.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-float-side-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue wpbe-bulk-new-form-do-bulk-new" id="wpbe-bulk-new-form-do-bulk-new" data-action="do">
                        <?php esc_html_e('Create Bulk Posts', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                    <?php do_action('wpbe_bulk_edit_form_after_bulk_edit_button'); ?>
                    <button type="button" class="wpbe-button wpbe-button-white" id="wpbe-bulk-edit-form-reset">
                        <?php esc_html_e('Reset Form', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>