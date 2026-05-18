<?php

namespace wpbel\framework\analytics;

use wpbel\framework\onboarding\Onboarding;

defined('ABSPATH') || exit();

class AnalyticsTracker
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public function __construct()
    {
        add_action('admin_init', [$this, 'analytics_check_field']);
        add_action('init', [$this, 'schedule_weekly_analytics']);
    }

    public function schedule_weekly_analytics()
    {
        if (!Onboarding::usage_track_is_allowed()) {
            return false;
        }

        $transient_name = 'ithemeland_wpbel_analytics_send';
        if (false === get_transient($transient_name)) {
            $analytics_service = AnalyticsService::get_instance();
            $analytics_service->send();
            set_transient($transient_name, 'sent', WEEK_IN_SECONDS);
        }
    }

    public function analytics_check_field()
    {
        register_setting(
            'general',
            'wpbel_usage_track',
            array(
                'type' => 'boolean',
                'sanitize_callback' => [$this, 'sanitize_checkbox'],
                'default' => 1
            )
        );

        add_settings_field(
            'wpbel_usage_track',
            esc_html__('Enable Usage Tracking', 'ithemeland-bulk-posts-editing-lite'),
            [$this, 'usage_tracking_checkbox'],
            'general',
            'default',
            array(
                'label_for' => 'wpbel_usage_track_general',
                'description' => esc_html__('Allow anonymous usage data tracking to help improve our plugin.', 'ithemeland-bulk-posts-editing-lite')
            )
        );
    }

    public function sanitize_checkbox($input)
    {
        return (isset($input) && $input == 'yes') ? 'yes' : '';
    }

    public function usage_tracking_checkbox($args)
    {
        $option = Onboarding::usage_track_is_allowed();
        $description = $args['description'] ?? '';

        include WPBEL_FW_DIR . 'analytics/views/usage_track_checkbox.php';
    }
}
