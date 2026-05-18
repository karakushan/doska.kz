<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-column-profiles">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Column Profiles', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" style="float: left; width: 100%; height: 100%;">
                    <?php wp_nonce_field('wpbe_post_nonce'); ?>
                    <input type="hidden" name="action" value="<?php echo esc_attr($plugin_key . '_load_column_profile'); ?>">
                    <div class="wpbe-float-side-modal-body">
                        <div class="wpbe-wrap">
                            <div class="wpbe-alert wpbe-alert-default">
                                <span><?php esc_html_e('You can load saved column profile presets through Column Manager. You can change the columns and save your changes too.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                            </div>
                            <div class="wpbe-column-profiles-choose">
                                <label for="wpbe-column-profiles-choose"><?php esc_html_e('Choose Preset', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                                <select id="wpbe-column-profiles-choose" name="preset_key">
                                    <?php
                                    if (!empty($column_manager_presets)) :
                                        $i = 0;
                                        foreach ($column_manager_presets as $column_manager_preset) :
                                            if ($i == 0) {
                                                $first_key = $column_manager_preset['key'];
                                            }
                                    ?>
                                            <option value="<?php echo esc_attr($column_manager_preset['key']); ?>" <?php echo (!empty($active_columns_key) && $active_columns_key == $column_manager_preset['key']) ? 'selected' : ''; ?>><?php echo esc_html($column_manager_preset['name']); ?></option>
                                    <?php
                                            $i++;
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <label class="wpbe-column-profile-select-all">
                                    <input type="checkbox" id="wpbe-column-profile-select-all" data-profile-name="<?php echo (!empty($active_columns_key)) ? esc_attr($active_columns_key) : ''; ?>">
                                    <span><?php esc_html_e('Select All', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                                </label>
                            </div>
                            <div class="wpbe-column-profile-search">
                                <label for="wpbe-column-profile-search"><?php esc_html_e('Search', 'ithemeland-bulk-posts-editing-lite'); ?> </label>
                                <input type="text" id="wpbe-column-profile-search" placeholder="<?php esc_attr_e('Search Column ...', 'ithemeland-bulk-posts-editing-lite'); ?>">
                            </div>
                            <div class="wpbe-column-profiles-fields">
                                <?php if (!empty($grouped_fields)) :
                                    $compatibles = [];
                                    if (!empty($grouped_fields['compatibles'])) {
                                        $compatibles = $grouped_fields['compatibles'];
                                        unset($grouped_fields['compatibles']);
                                    }
                                ?>
                                    <div class="wpbe-column-profile-fields">
                                        <?php foreach ($grouped_fields as $group_name => $column_fields) : ?>
                                            <?php if (!empty($column_fields)) : ?>
                                                <div class="wpbe-column-profile-fields-group">
                                                    <div class="group-title">
                                                        <h3><?php echo esc_html($group_name); ?></h3>
                                                    </div>
                                                    <ul>
                                                        <?php foreach ($column_fields as $name => $column_field) : ?>
                                                            <li>
                                                                <label>
                                                                    <input type="checkbox" name="columns[]" value="<?php echo esc_attr($name); ?>">
                                                                    <?php echo esc_html($column_field['label']); ?>
                                                                </label>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        <?php
                                        endforeach;
                                        if (!empty($compatibles) && is_array($compatibles)) : ?>
                                            <div class="wpbe-column-profile-compatibles-group">
                                                <strong class="wpbe-column-profile-compatibles-group-title"><?php esc_html_e('Fields from third-party plugins', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                                                <?php foreach ($compatibles as $compatible_name => $compatible_fields) : ?>
                                                    <div class="wpbe-column-profile-fields-group">
                                                        <div class="group-title">
                                                            <h3><?php echo esc_html($compatible_name); ?></h3>
                                                        </div>
                                                        <ul>
                                                            <?php foreach ($compatible_fields as $compatible_field_name => $compatible_field) : ?>
                                                                <li>
                                                                    <label>
                                                                        <input type="checkbox" name="columns[]" value="<?php echo esc_attr($compatible_field_name); ?>">
                                                                        <?php echo esc_html($compatible_field['label']); ?>
                                                                    </label>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpbe-float-side-modal-footer">
                        <button type="submit" class="wpbe-button wpbe-button-blue wpbe-float-left" id="wpbe-column-profiles-apply" data-preset-key="<?php echo (!empty($first_key)) ? esc_attr($first_key) : ''; ?>">
                            <?php esc_html_e('Apply To Table', 'ithemeland-bulk-posts-editing-lite'); ?>
                        </button>
                        <div class="wpbe-column-profile-save-dropdown" style="display: none">
                            <span>
                                <?php esc_html_e('Save Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                                <i class="wpbe-icon-chevron-down"></i>
                            </span>
                            <div class="wpbe-column-profile-save-dropdown-buttons">
                                <ul>
                                    <li id="wpbe-column-profiles-update-changes" <?php echo (!empty($active_columns_key) && !empty($default_columns_name) && in_array($active_columns_key, $default_columns_name)) ? 'style="display:none;"' : ''; ?>>
                                        <?php esc_html_e('Update selected preset', 'ithemeland-bulk-posts-editing-lite'); ?>
                                    </li>
                                    <li id="wpbe-column-profiles-save-as-new-preset">
                                        <?php esc_html_e('Save as new preset', 'ithemeland-bulk-posts-editing-lite'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>