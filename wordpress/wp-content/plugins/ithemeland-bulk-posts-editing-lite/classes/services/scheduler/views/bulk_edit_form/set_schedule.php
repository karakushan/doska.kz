<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>
<div class="wpbe-tab-content-item" data-content="set_schedule">
    <?php
    if (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE):
        include WPBEL_VIEWS_DIR . 'alerts/warning-active-pro.php';
    else:
    ?>
        <div class="wpbe-alert wpbe-alert-default" style="padding-bottom: 10px;">
            <span><?php esc_html_e('The current Bulk Form will be saved as a job and applied on specific date/time.', 'ithemeland-bulk-posts-editing-lite'); ?>
                <button type="button" class="wpbe-schedule-current-time-update-button"><i class="wpbe-icon-refresh-cw"></i></button>
                <div style="float: right;">
                    <span style="color: #444;"><?php esc_html_e('Universal time is:', 'ithemeland-bulk-posts-editing-lite'); ?> </span>
                    <span class="wpbe-set-schedule-current-time"><?php echo esc_html(current_datetime()->format('Y-m-d H:i')); ?></span>
                </div>
            </span>
        </div>
        <div class="wpbe-wrap">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Enable Schedule Bulk Edit', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="checkbox" class="wpbe-set-schedule-enable-schedule required" value="yes">
            </div>
            <?php include WPBEL_DIR . "classes/services/scheduler/views/job_form/job_form.php"; ?>
        </div>
    <?php endif; ?>
</div>