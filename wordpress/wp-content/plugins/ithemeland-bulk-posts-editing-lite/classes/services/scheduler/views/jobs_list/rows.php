<?php

use wpbel\classes\services\scheduler\model\Schedule_Job;
use wpbel\classes\services\scheduler\Job_Presenter;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (!empty($schedule_jobs)):
    $i = 1;
    foreach ($schedule_jobs as $job):
?>
        <tr>
            <td><?php echo intval($i); ?></td>
            <td><?php echo esc_html($job->label); ?></td>
            <td><?php echo wp_kses_post(Job_Presenter::schedules($job)); ?></td>
            <td><?php echo wp_kses_post(Job_Presenter::status($job->status)); ?></td>
            <td>
                <button <?php echo !defined('WPBE_ACTIVE') ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-schedule-jobs-list-action-button" data-id="<?php echo esc_attr($job->id); ?>" data-action="show_edit_items" data-toggle="modal" data-target="#wpbe-modal-schedule-job-edit-items" title="<?php esc_html_e('Edit Items', 'ithemeland-bulk-posts-editing-lite'); ?>"><i class="wpbe-icon-list1"></i></button>

                <?php if ($job->status == Schedule_Job::PENDING): ?>
                    <button <?php echo !defined('WPBE_ACTIVE') ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-schedule-jobs-list-action-button" data-id="<?php echo esc_attr($job->id); ?>" data-action="edit" data-toggle="modal" data-target="#wpbe-modal-schedule-edit-job" title="<?php esc_attr_e('Edit', 'ithemeland-bulk-posts-editing-lite'); ?>"><i class="wpbe-icon-edit"></i></button>
                <?php else: ?>
                    <button type="button" class="wpbe-schedule-jobs-list-action-button" data-id="<?php echo esc_attr($job->id); ?>" data-action="log" data-toggle="modal" data-target="#wpbe-modal-schedule-job-log" title="<?php esc_attr_e('Log', 'ithemeland-bulk-posts-editing-lite'); ?>"><i class="wpbe-icon-file-text1"></i></button>
                <?php endif; ?>
                <button <?php echo !defined('WPBE_ACTIVE') ? 'disabled="disabled"' : ''; ?> type="button" class="wpbe-schedule-jobs-list-action-button" data-id="<?php echo esc_attr($job->id); ?>" data-action="delete" title="<?php esc_attr_e('Delete Job', 'ithemeland-bulk-posts-editing-lite'); ?>"><i class="wpbe-icon-trash-2"></i></button>
                <?php if ($job->status == Schedule_Job::RUNNING): ?>
                    <button type="button" class="wpbe-schedule-jobs-list-action-button" data-id="<?php echo esc_attr($job->id); ?>" data-action="stop" title="<?php esc_attr_e('Stop Now', 'ithemeland-bulk-posts-editing-lite'); ?>"><i class="wpbe-icon-stop-circle"></i></button>
                <?php endif; ?>
                <?php if (!defined('WPBE_ACTIVE')): ?>
                    <span class="wpbe-alert-pro-description"><?php esc_html_e('Upgrade to pro version!', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php
        $i++;
    endforeach;
else:
    ?>
    <tr>
        <td colspan="100%">No Data Available!</td>
    </tr>
<?php
endif;
?>