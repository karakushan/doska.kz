<?php

use wpbel\classes\services\scheduler\Job_Presenter;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (empty($job) || empty($log) || !is_array($log)) {
    return;
}
?>

<div class="wpbe-schedule-job-log-info">
    <div>
        <strong><?php esc_html_e('Job name:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
        <span><?php echo esc_html($job->label); ?></span>
    </div>
    <?php if ($job->description != ''): ?>
        <div>
            <strong><?php esc_html_e('Description:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
            <span><?php echo esc_html($job->description); ?></span>
        </div>
    <?php endif; ?>
    <div>
        <strong><?php esc_html_e('Created at:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
        <span><?php echo esc_html(gmdate('Y-m-d H:i', strtotime($job->created_at))); ?></span>
    </div>
    <div>
        <strong><?php esc_html_e('Run for:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
        <span><?php echo ($job->run_at == 'now') ? 'Now' : esc_html(ucfirst($job->run_for)); ?></span>
    </div>
    <div>
        <strong><?php esc_html_e('Status:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
        <span><?php echo wp_kses_post(Job_Presenter::status($job->status)); ?></span>
    </div>
    <div>
        <strong><?php esc_html_e('Fields:', 'ithemeland-bulk-posts-editing-lite'); ?> </strong>
        <span><?php echo wp_kses_post(Job_Presenter::edit_items($job->edit_items)); ?></span>
    </div>
</div>

<div class="wpbe-schedule-job-log-table">
    <table>
        <thead>
            <tr>
                <th><?php esc_html_e('Action', 'ithemeland-bulk-posts-editing-lite'); ?></th>
                <th><?php esc_html_e('Run at', 'ithemeland-bulk-posts-editing-lite'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($log as $item): ?>
                <tr>
                    <td><?php echo esc_html(Job_Presenter::log_action($item['action'])); ?></td>
                    <td><?php echo esc_html($item['datetime']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>