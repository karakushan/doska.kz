<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wpbe-modal" id="wpbe-modal-schedule-edit-job">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Edit Job', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-modal-body-content">
                        <div class="wpbe-wrap">
                            <div class="wpbe-schedule-edit-job-loading">
                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="20" height="20" />
                            </div>
                            <div class="wpbe-schedule-edit-job-container">
                                <?php include WPBEL_DIR . "classes/services/scheduler/views/job_form/job_form.php"; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbe-modal-footer">
                    <button type="button" class="wpbe-button wpbe-button-blue wpbe-schedule-edit-job-apply-button" disabled>
                        <?php esc_html_e('Apply Changes', 'ithemeland-bulk-posts-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>