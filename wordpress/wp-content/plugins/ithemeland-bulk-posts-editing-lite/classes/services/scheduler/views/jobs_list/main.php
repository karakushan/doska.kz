<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wpbe-float-side-modal" id="wpbe-float-side-modal-schedule-jobs">
    <div class="wpbe-float-side-modal-container">
        <div class="wpbe-float-side-modal-box">
            <div class="wpbe-float-side-modal-content">
                <div class="wpbe-float-side-modal-title">
                    <h2><?php esc_html_e('Scheduled jobs', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-float-side-modal-body">
                    <div class="wpbe-wrap" style="padding-top: 10px;">
                        <?php
                        if (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE):
                            include WPBEL_VIEWS_DIR . 'alerts/warning-active-pro.php';
                        else:
                        ?>
                            <div class="wpbe-float-side-modal-schedule-jobs-table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('#', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Name', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Schedules', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Status', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="100%" align="center"><img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="20" height="20" alt="Loading"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (defined('WPBE_ACTIVE') && WPBE_ACTIVE) {
    include "edit_job.php";
    include_once "job_log.php";
    include_once "job_edit_items_modal.php";
}
