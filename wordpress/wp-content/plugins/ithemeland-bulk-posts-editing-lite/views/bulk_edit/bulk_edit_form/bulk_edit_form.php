<?php

use wpbel\classes\helpers\Post_Helper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-bulk-edit">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Bulk Edit Form', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-tabs">
                            <div class="wpbe-tabs-navigation">
                                <nav class="wpbe-tabs-navbar">
                                    <ul class="wpbe-tabs-list" data-content-id="wpbe-bulk-edit-tabs">
                                        <li><a class="wpbe-tab-item selected" data-content="general" href="#"><?php esc_html_e('General', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <?php if (Post_Helper::get_active_post_type() != 'page') : ?>
                                            <li>
                                                <a class="wpbe-tab-item" data-content="categories-tags-taxonomies" href="#">
                                                    <?php esc_html_e('Categories/Tags/Taxonomies', 'ithemeland-bulk-posts-editing-lite'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a class="wpbe-tab-item" data-content="date-type" href="#"><?php esc_html_e('Date & Type', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <li><a class="wpbe-tab-item" data-content="custom-fields" href="#"><?php esc_html_e('Custom Fields', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <?php do_action('wpbe_bulk_edit_form_after_tab_title'); ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wpbe-tabs-contents wpbe-mt30" id="wpbe-bulk-edit-tabs">
                                <div class="selected wpbe-tab-content-item" data-content="general">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/general.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="categories-tags-taxonomies">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/taxonomies.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="date-type">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/date_type.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="custom-fields">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/bulk_edit_form/custom_field.php"; ?>
                                </div>
                                <?php do_action('wpbe_bulk_edit_form_after_tab_content'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-float-side-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue wpbe-bulk-edit-form-do-bulk-edit" id="wpbe-bulk-edit-form-do-bulk-edit" data-action="do">
                        <?php esc_html_e('Do Bulk Edit', 'ithemeland-bulk-posts-editing-lite'); ?>
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