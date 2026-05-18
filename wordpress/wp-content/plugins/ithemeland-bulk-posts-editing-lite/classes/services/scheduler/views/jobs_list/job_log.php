<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wpbe-modal" id="wpbe-modal-schedule-job-log">
    <div class="wpbe-modal-container">
        <div class="wpbe-modal-box wpbe-modal-box-sm">
            <div class="wpbe-modal-content">
                <div class="wpbe-modal-title">
                    <h2><?php esc_html_e('Job log', 'ithemeland-bulk-posts-editing-lite'); ?></h2>
                    <button type="button" class="wpbe-modal-close" data-toggle="modal-close">
                        <i class="wpbe-icon-x"></i>
                    </button>
                </div>
                <div class="wpbe-modal-body">
                    <div class="wpbe-modal-body-content">
                        <div class="wpbe-wrap">
                            <div class="wpbe-schedule-job-log-loading">
                                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="20" height="20" />
                            </div>

                            <div class="wpbe-schedule-job-log-container">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>