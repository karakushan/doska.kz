<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-import-export">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Import/Export', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbe-wrap">
                        <div class="wpbe-tab-middle-content">
                            <div class="wpbe-alert wpbe-alert-default">
                                <span><?php esc_html_e('Import/Export posts as (CSV/XML) files', 'ithemeland-bulk-posts-editing-lite'); ?>.</span>
                            </div>
                            <div class="wpbe-export">
                                <form action="<?php echo esc_url(admin_url("admin-post.php")); ?>" method="post">
                                    <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbe_export_posts">
                                    <div id="wpbe-export-items-selected"></div>
                                    <div class="wpbe-export-fields">
                                        <div class="wpbe-export-field-item">
                                            <strong class="label"><?php esc_html_e('Posts', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                                            <label class="wpbe-export-radio">
                                                <input type="radio" name="posts" value="all" checked="checked" id="wpbe-export-all-items-in-table">
                                                <?php esc_html_e('All Posts In Table', 'ithemeland-bulk-posts-editing-lite'); ?>
                                            </label>
                                            <label class="wpbe-export-radio">
                                                <input type="radio" name="posts" id="wpbe-export-only-selected-items" value="selected" disabled="disabled">
                                                <?php esc_html_e('Only Selected posts', 'ithemeland-bulk-posts-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wpbe-export-field-item">
                                            <strong class="label"><?php esc_html_e('Fields', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                                            <label class="wpbe-export-radio">
                                                <input type="radio" name="fields" value="all" checked="checked">
                                                <?php esc_html_e('All Fields', 'ithemeland-bulk-posts-editing-lite'); ?>
                                            </label>
                                            <label class="wpbe-export-radio">
                                                <input type="radio" name="fields" value="visible">
                                                <?php esc_html_e('Only Visible Fields', 'ithemeland-bulk-posts-editing-lite'); ?>
                                            </label>
                                        </div>
                                        <div class="wpbe-export-field-item">
                                            <label class="label" for="wpbe-export-delimiter"><?php esc_html_e('Delimiter', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                            <select name="export_delimiter" id="wpbe-export-delimiter">
                                                <option value=",">,</option>
                                            </select>
                                        </div>
                                        <div class="wpbe-export-field-item">
                                            <label class="label" for="wpbe-export-type"><?php esc_html_e('Export Type', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                            <select name="export_type" id="wpbe-export-type">
                                                <option value="csv">CSV</option>
                                                <option value="xml">XML</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wpbe-export-buttons">
                                        <div class="wpbe-export-buttons-left">
                                            <button type="submit" class="wpbe-button wpbe-button-lg wpbe-button-blue" id="wpbe-export-posts">
                                                <i class="wpbe-icon-filter1"></i>
                                                <span><?php esc_html_e('Export Now', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="wpbe-import">
                                <div class="wpbe-import-content">
                                    <p><?php esc_html_e("If you have posts in another system, you can import those into this site. ", 'ithemeland-bulk-posts-editing-lite'); ?></p>
                                </div>
                                <div class="wpbe-import-buttons">
                                    <div class="wpbe-import-buttons-left">
                                        <a href="<?php echo esc_url(admin_url("import.php")); ?>" target="_blank" class="wpbe-button wpbe-button-lg wpbe-button-blue">
                                            <i class="wpbe-icon-filter1"></i>
                                            <span><?php esc_html_e('Import Now', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>