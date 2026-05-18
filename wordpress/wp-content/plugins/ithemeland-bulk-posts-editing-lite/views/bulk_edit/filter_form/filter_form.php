<?php

use wpbel\classes\helpers\Post_Helper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-filter">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <input type="hidden" id="filter-form-changed" value="">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Form', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-tabs">
                            <div class="wpbe-tabs-navigation">
                                <nav class="wpbe-tabs-navbar">
                                    <ul class="wpbe-tabs-list" data-content-id="wpbe-bulk-edit-filter-tabs-contents">
                                        <li><a class="wpbe-tab-item selected" data-content="bulk-edit-filter-general" href="#"><?php esc_html_e('General', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <?php if (Post_Helper::get_active_post_type() != 'page') : ?>
                                            <li>
                                                <a class="wpbe-tab-item" data-content="bulk-edit-filter-categories-tags-taxonomies" href="#">
                                                    <?php esc_html_e('Categories/Tags/Taxonomies', 'ithemeland-bulk-posts-editing-lite'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><a class="wpbe-tab-item" data-content="bulk-edit-filter-date-type" href="#"><?php esc_html_e('Date & Type', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <li><a class="wpbe-tab-item" data-content="bulk-edit-filter-custom-fields" href="#"><?php esc_html_e('Custom Fields', 'ithemeland-bulk-posts-editing-lite'); ?></a></li>
                                        <?php do_action('wpbe_filter_form_after_tab_title'); ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="wpbe-tabs-contents" id="wpbe-bulk-edit-filter-tabs-contents">
                                <div class="selected wpbe-tab-content-item" data-content="bulk-edit-filter-general">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/general.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="bulk-edit-filter-categories-tags-taxonomies">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/taxonomies.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="bulk-edit-filter-date-type">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/date_type.php"; ?>
                                </div>
                                <div class="wpbe-tab-content-item" data-content="bulk-edit-filter-custom-fields">
                                    <?php include_once WPBEL_VIEWS_DIR . "bulk_edit/filter_form/custom_fields.php"; ?>
                                </div>
                                <?php do_action('wpbe_filter_form_after_tab_content'); ?>
                            </div>
                            </nav>
                        </div>
                    </div>

                    <div class="wpbe-float-side-modal-footer">
                        <div class="wpbe-tab-footer-left">
                            <button type="button" id="wpbe-filter-form-get-posts" class="wpbe-button wpbe-button-blue wpbe-filter-form-action" data-search-action="pro_search">
                                <?php esc_html_e('Get Posts', 'ithemeland-bulk-posts-editing-lite'); ?>
                            </button>
                            <button type="button" class="wpbe-button wpbe-button-white" id="wpbe-filter-form-reset">
                                <?php esc_html_e('Reset Filters', 'ithemeland-bulk-posts-editing-lite'); ?>
                            </button>
                        </div>
                        <div class="wpbe-tab-footer-right">
                            <input type="text" name="save_filter" id="wpbe-filter-form-save-preset-name" placeholder="Filter Name ..." class="" title="Filter Name">
                            <button type="button" id="wpbe-filter-form-save-preset" class="wpbe-button wpbe-button-blue">
                                <?php esc_html_e('Save Profile', 'ithemeland-bulk-posts-editing-lite'); ?>
                            </button>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>