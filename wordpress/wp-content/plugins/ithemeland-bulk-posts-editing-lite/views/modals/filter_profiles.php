<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-filter-profiles">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap">
                        <div class="wpbe-filter-profiles-items wpbe-pb30">
                            <div class="wpbe-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Use Always', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($filters_preset)) : ?>
                                            <?php foreach ($filters_preset as $filter_item) : ?>
                                                <?php include "filter_profile_item.php"; ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="4" style="text-align: center;"><img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="20" height="20"></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>