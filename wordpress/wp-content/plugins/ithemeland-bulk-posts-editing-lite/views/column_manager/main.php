<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-column-manager">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Column Manager', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wpbe-wrap">
                        <div class="wpbe-tab-middle-content">
                            <div class="wpbe-alert wpbe-alert-default">
                                <span><?php esc_html_e('Mange columns of table. You can Create your customize presets and use them in column profile section.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                            </div>
                            <div class="wpbe-column-manager-items">
                                <h3><?php esc_html_e('Column Profiles', 'ithemeland-bulk-posts-editing-lite'); ?></h3>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbe-column-manager-delete-preset-form">
                                    <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbe_column_manager_delete_preset">
                                    <input type="hidden" name="delete_key" id="wpbe_column_manager_delete_preset_key">
                                    <div class="wpbe-table-border-radius">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php esc_html_e('Profile Name', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Date Modified', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                    <th><?php esc_html_e('Actions', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($column_manager_presets)) : ?>
                                                    <?php $i = 1 ?>
                                                    <?php foreach ($column_manager_presets as $key => $column_manager_preset) : ?>
                                                        <tr>
                                                            <td><?php echo esc_html($i); ?></td>
                                                            <td>
                                                                <span class="wpbe-history-name"><?php echo (isset($column_manager_preset['name'])) ? esc_html($column_manager_preset['name']) : ''; ?></span>
                                                            </td>
                                                            <td><?php echo (isset($column_manager_preset['date_modified'])) ? esc_html(gmdate('d M Y', strtotime($column_manager_preset['date_modified']))) : ''; ?></td>
                                                            <td>
                                                                <?php if (!in_array($key, \wpbel\classes\repositories\Column::get_default_columns_name())) : ?>
                                                                    <button type="button" class="wpbe-button wpbe-button-blue wpbe-column-manager-edit-field-btn" data-toggle="modal" data-target="#wpbe-modal-column-manager-edit-preset" value="<?php echo esc_attr($key); ?>" data-preset-name="<?php echo (isset($column_manager_preset['name'])) ? esc_attr($column_manager_preset['name']) : ''; ?>">
                                                                        <i class="wpbe-icon-pencil"></i>
                                                                        <?php esc_html_e('Edit', 'ithemeland-bulk-posts-editing-lite'); ?>
                                                                    </button>
                                                                    <button type="button" name="delete_preset" class="wpbe-button wpbe-button-red wpbe-column-manager-delete-preset" value="<?php echo esc_attr($key); ?>">
                                                                        <i class="wpbe-icon-trash-2"></i>
                                                                        <?php esc_html_e('Delete', 'ithemeland-bulk-posts-editing-lite'); ?>
                                                                    </button>
                                                                <?php else : ?>
                                                                    <i class="wpbe-icon-lock1"></i>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="wpbe-column-manager-new-profile">
                                <h3 class="wpbe-column-manager-section-title"><?php esc_html_e('Create New Profile', 'ithemeland-bulk-posts-editing-lite'); ?></h3>
                                <div class="wpbe-column-manager-new-profile-left">
                                    <input type="text" title="<?php esc_html_e('Search Field', 'ithemeland-bulk-posts-editing-lite'); ?>" data-action="new" placeholder="<?php esc_html_e('Search Field ...', 'ithemeland-bulk-posts-editing-lite'); ?>" class="wpbe-column-manager-search-field">
                                    <div class="wpbe-column-manager-available-fields" data-action="new">
                                        <label class="wpbe-column-manager-check-all-fields-btn" data-action="new">
                                            <input type="checkbox" class="wpbe-column-manager-check-all-fields">
                                            <span><?php esc_html_e('Select All', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                        </label>
                                        <ul>
                                            <?php if (!empty($column_items)) : ?>
                                                <?php foreach ($column_items as $column_key => $column_field) : ?>
                                                    <li data-name="<?php echo esc_attr($column_key); ?>" data-added="false">
                                                        <label>
                                                            <input type="checkbox" data-type="field" data-name="<?php echo esc_attr($column_key); ?>" value="<?php echo esc_attr($column_field['label']); ?>">
                                                            <?php echo esc_html($column_field['label']); ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="wpbe-column-manager-new-profile-middle">
                                    <div class="wpbe-column-manager-middle-buttons">
                                        <div>
                                            <button type="button" data-action="new" data-type="checked" class="wpbe-button wpbe-button-lg wpbe-button-square-lg wpbe-button-blue wpbe-column-manager-add-field">
                                                <i class="wpbe-icon-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wpbe-column-manager-add-new-preset">
                                    <?php wp_nonce_field('wpbe_post_nonce'); ?>
                                    <input type="hidden" name="action" value="wpbe_column_manager_new_preset">
                                    <div class="wpbe-column-manager-new-profile-right">
                                        <div class="wpbe-column-manager-right-top">
                                            <input type="text" title="Profile Name" id="wpbe-column-manager-new-preset-name" name="preset_name" placeholder="Profile name ..." required>
                                            <button type="submit" name="save_preset" id="wpbe-column-manager-new-preset-btn" class="wpbe-button wpbe-button-lg wpbe-button-blue">
                                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'save.svg'); ?>" alt="">
                                                <?php esc_html_e('Save Preset', 'ithemeland-bulk-posts-editing-lite'); ?>
                                            </button>
                                        </div>
                                        <div class="wpbe-column-manager-added-fields-wrapper">
                                            <p class="wpbe-column-manager-empty-text"><?php esc_html_e('Please add your columns here', 'ithemeland-bulk-posts-editing-lite'); ?></p>
                                            <div class="wpbe-column-manager-added-fields" data-action="new">
                                                <div class="items"></div>
                                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" alt="" class="wpbe-box-loading wpbe-hide">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>