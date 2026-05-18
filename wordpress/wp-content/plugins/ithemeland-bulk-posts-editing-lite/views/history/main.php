<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-history">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('History', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-alert wpbe-alert-default">
                            <span><?php esc_html_e('List of your changes and possible to roll back to the previous data', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                        </div>
                        <?php if (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE) : ?>
                            <?php include WPBEL_VIEWS_DIR . 'alerts/warning-active-pro.php' ?>
                        <?php endif; ?>
                        <div class="wpbe-history-filter">
                            <div class="wpbe-history-filter-fields">
                                <div class="wpbe-history-filter-field-item">
                                    <label for="wpbe-history-filter-operation"><?php esc_html_e('Operation', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select id="wpbe-history-filter-operation">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                                        <?php if (!empty($history_types = \wpbel\classes\repositories\history\History_Main::get_operation_types())) : ?>
                                            <?php foreach ($history_types as $history_type_key => $history_type_label) : ?>
                                                <option value="<?php echo esc_attr($history_type_key); ?>"><?php echo esc_html($history_type_label); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wpbe-history-filter-field-item">
                                    <label for="wpbe-history-filter-author"><?php esc_html_e('Author', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <select <?php echo (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE) ? 'disabled="disabled"' : ''; ?> id="wpbe-history-filter-author">
                                        <option value=""><?php esc_html_e('Select', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $user_item) : ?>
                                                <option value="<?php echo esc_attr($user_item->ID); ?>"><?php echo esc_html($user_item->user_login); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="wpbe-history-filter-field-item">
                                    <label for="wpbe-history-filter-fields"><?php esc_html_e('Fields', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" id="wpbe-history-filter-fields" placeholder="for example: ID">
                                </div>
                                <div class="wpbe-history-filter-field-item wpbe-history-filter-field-date">
                                    <label><?php esc_html_e('Date', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                    <input type="text" id="wpbe-history-filter-date-from" class="wpbe-datepicker wpbe-date-from" data-to-id="wpbe-history-filter-date-to" placeholder="<?php esc_html_e('From ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                                    <input type="text" id="wpbe-history-filter-date-to" class="wpbe-datepicker" placeholder="<?php esc_html_e('To ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                                </div>
                            </div>
                            <div class="wpbe-history-filter-buttons">
                                <div class="wpbe-history-filter-buttons-left">
                                    <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-button wpbe-button-lg wpbe-button-blue" id="wpbe-history-filter-apply">
                                        <i class="wpbe-icon-filter1"></i>
                                        <span><?php esc_html_e('Apply Filters', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                    </button>
                                    <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-button wpbe-button-lg wpbe-button-gray" id="wpbe-history-filter-reset">
                                        <i class="wpbe-icon-rotate-cw"></i>
                                        <span><?php esc_html_e('Reset Filters', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                    </button>
                                </div>
                                <div class="wpbe-history-filter-buttons-right">
                                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbe-history-clear-all">
                                        <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                        <input type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_clear_all_history'); ?>">
                                        <button <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="button" name="clear_all" value="1" id="wpbe-history-clear-all-btn" class="wpbe-button wpbe-button-lg wpbe-button-red">
                                            <i class="wpbe-icon-trash-2"></i>
                                            <span><?php esc_html_e('Clear History', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="wpbe-history-items">
                            <h3><?php esc_html_e('Column(s)', 'ithemeland-bulk-posts-editing-lite'); ?></h3>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbe-history-items">
                                <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                <input <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_history_action'); ?>">
                                <input <?php echo !defined('WPBE_ACTIVE') || !WPBE_ACTIVE ? 'disabled="disabled"' : ''; ?> type="hidden" name="" value="" id="wpbe-history-clicked-id">
                                <div class="wpbe-table-border-radius">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php esc_html_e('History Name', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                <th><?php esc_html_e('Author', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                <th class="wpbe-mw125"><?php esc_html_e('Date Modified', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                <th class="wpbe-mw250"><?php esc_html_e('Actions', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php include 'history_items.php'; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wpbe-history-pagination-container">
                                    <?php include 'history_pagination.php'; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>