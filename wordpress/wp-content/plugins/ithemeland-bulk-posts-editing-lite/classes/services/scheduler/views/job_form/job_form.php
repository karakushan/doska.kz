<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
?>

<div class="wpbe-set-schedule-form">
    <div class="wpbe-form-group">
        <label><?php esc_html_e('Name', 'ithemeland-bulk-posts-editing-lite'); ?></label>
        <input type="text" class="wpbe-set-schedule-name required" placeholder="<?php esc_attr_e('Name', 'ithemeland-bulk-posts-editing-lite'); ?> ...">
    </div>
    <div class="wpbe-form-group">
        <label><?php esc_html_e('Description', 'ithemeland-bulk-posts-editing-lite'); ?></label>
        <textarea class="wpbe-set-schedule-description" placeholder="<?php esc_attr_e('Description', 'ithemeland-bulk-posts-editing-lite'); ?> ..."></textarea>
    </div>
    <div class="wpbe-form-group">
        <label><?php esc_html_e('Run at', 'ithemeland-bulk-posts-editing-lite'); ?></label>
        <select class="wpbe-set-schedule-run-at required">
            <option value="now"><?php esc_html_e('Now', 'ithemeland-bulk-posts-editing-lite'); ?></option>
            <option value="later"><?php esc_html_e('Later', 'ithemeland-bulk-posts-editing-lite'); ?></option>
        </select>
    </div>
    <div class="wpbe-set-schedule-dependent">
        <div data-content="later">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Run for', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <select class="wpbe-set-schedule-run-for required">
                    <option value="once"><?php esc_html_e('Once', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="daily"><?php esc_html_e('Daily', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="weekly"><?php esc_html_e('Weekly', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="monthly"><?php esc_html_e('Monthly', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                </select>
            </div>
        </div>

        <div data-content="once">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Type', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <select class="wpbe-set-schedule-once-type required">
                    <option value="specific_date_time"><?php esc_html_e('Specific Date & Time', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="n_hours_later"><?php esc_html_e('n Hours later', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="n_days_later"><?php esc_html_e('n Days later', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                </select>
            </div>
        </div>

        <div data-content="specific_date_time">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Date & Time', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-datetimepicker wpbe-set-schedule-once-date-time required" placeholder="Date & Time">
            </div>
        </div>

        <div data-content="n_hours_later">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select time', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-timepicker wpbe-set-schedule-once-hours required" placeholder="Time">
                <span style="line-height: 34px; font-size: 14px;"><?php esc_html_e('Later', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            </div>
        </div>

        <div data-content="n_days_later">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Days', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="number" class="wpbe-set-schedule-once-days required" placeholder="Days">
                <span style="line-height: 34px; font-size: 14px;"><?php esc_html_e('Days Later', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            </div>
        </div>

        <div data-content="daily">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Time', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-timepicker wpbe-set-schedule-daily-time required" placeholder="Time">
            </div>
        </div>

        <div data-content="weekly">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Days', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <select class="wpbe-select2 wpbe-set-schedule-weekly-days required" multiple>
                    <option value="monday"><?php esc_html_e('Monday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="tuesday"><?php esc_html_e('Tuesday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="wednesday"><?php esc_html_e('Wednesday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="thursday"><?php esc_html_e('Thursday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="friday"><?php esc_html_e('Friday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="saturday"><?php esc_html_e('Saturday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                    <option value="sunday"><?php esc_html_e('Sunday', 'ithemeland-bulk-posts-editing-lite'); ?></option>
                </select>
            </div>
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Time', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-timepicker wpbe-set-schedule-weekly-time required" placeholder="Time">
            </div>
        </div>

        <div data-content="monthly">
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Days', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <select class="wpbe-select2 wpbe-set-schedule-monthly-days required" multiple>
                    <?php for ($i = 1; $i <= 31; $i++) : ?>
                        <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="wpbe-form-group">
                <label><?php esc_html_e('Select Time', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-timepicker wpbe-set-schedule-monthly-time required" placeholder="Time">
            </div>
        </div>

        <div data-content="stop_schedule">
            <div class="wpbe-form-group">
                <label><strong><?php esc_html_e('Stop', 'ithemeland-bulk-posts-editing-lite'); ?></strong> <?php esc_html_e('schedule on', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-datetimepicker wpbe-set-schedule-stop-date-time" placeholder="Date & Time">
                <span class="wpbe-set-schedule-short-description"><?php esc_html_e('Leave blank if you don\'t want to stop the schedule on specific Date & Time.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            </div>
        </div>

        <div data-content="revert_changes">
            <div class="wpbe-form-group">
                <label><strong><?php esc_html_e('Revert', 'ithemeland-bulk-posts-editing-lite'); ?></strong> <?php esc_html_e('Last Update', 'ithemeland-bulk-posts-editing-lite'); ?></label>
                <input type="text" class="wpbe-schedule-datetimepicker wpbe-set-schedule-revert-date-time" placeholder="Date & Time">
                <span class="wpbe-set-schedule-short-description"><?php esc_html_e('Leave blank if you don\'t want revert changes.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
                <span class="wpbe-set-schedule-short-description"><?php esc_html_e('If set date, it will override your previous update.', 'ithemeland-bulk-posts-editing-lite'); ?></span>
            </div>
        </div>
    </div>
</div>