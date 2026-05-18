<?php

namespace wpbel\classes\services\scheduler;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Render;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\services\scheduler\Scheduler;
use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\services\filter\Post_Filter_Service;
use wpbel\classes\services\history\HistoryUndoService;
use wpbel\classes\services\update\WPBEL_Post_Update;

class Post_Scheduler extends Scheduler
{
    private static $instance;

    protected $identifier = 'wpbe';

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();

        add_action('wp_ajax_wpbe_add_schedule_job', [$this, 'add_schedule_job']);
        add_action('wp_ajax_wpbe_get_schedule_jobs', [$this, 'get_schedule_jobs']);
    }

    public function update_handle($job)
    {
        if (empty($job->id)) {
            return false;
        }

        $edit_items = json_decode($job->edit_items, true);
        $filter_items = json_decode($job->filter_items, true);

        if (empty($filter_items['search_type']) && !empty($filter_items['post_ids']) && is_array($filter_items['post_ids'])) {
            $post_ids = array_map('intval', $filter_items['post_ids']);
        } else {
            $post_ids = $this->get_all_filtered($filter_items);
        }

        $update_service = WPBEL_Post_Update::get_instance();
        $update_service->set_update_data([
            'post_ids' => $post_ids,
            'post_data' => $edit_items,
            'save_history' => true,
            'complete_actions' => [
                [
                    'hook' => 'wpbe_schedule_update_completed',
                    'data' => [
                        'job_id' => $job->id
                    ]
                ]
            ]
        ]);

        $update_result = $update_service->perform();

        return [
            'success' => $update_result,
            'history_id' => $update_service->get_history_id(),
            'is_processing' => $update_service->is_processing(),
        ];
    }

    public function revert_handle($job)
    {
        if (empty($job->history_id)) {
            do_action('wpbe_schedule_revert_completed', ['job_id' => $job->id, 'result' => false]);
            return false;
        }

        $history_undo_service = new HistoryUndoService();
        $history_undo_service->set_data([
            'history_id' => intval($job->history_id),
            'complete_actions' => [
                [
                    'hook' => 'wpbe_schedule_revert_completed',
                    'data' => [
                        'job_id' => $job->id
                    ]
                ]
            ]
        ]);

        $history_undo_service->perform();
    }

    public function get_schedule_jobs()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wpbe_ajax_nonce')) {
            die();
        }

        $schedule_jobs = $this->get_jobs();
        $jobs = Render::html(WPBEL_DIR . 'classes/services/scheduler/views/jobs_list/rows.php', compact('schedule_jobs'));

        $awaiting_jobs = $this->repository->get_awaiting_count([
            'identifier' => $this->identifier
        ]);

        wp_send_json([
            'success' => true,
            'rows' => $jobs,
            'awaiting_count' => $awaiting_jobs,
        ]);
    }

    public function add_schedule_job()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'wpbe_ajax_nonce')) {
            die();
        }

        if (empty($_POST['edit_items']) || empty($_POST['label']) || empty($_POST['run_at'])) {
            wp_send_json([
                'success' => false,
            ]);
        }

        $result = $this->add_job([
            'label' => sanitize_text_field(wp_unslash($_POST['label'])),
            'description' => (isset($_POST['description'])) ? sanitize_text_field(wp_unslash($_POST['description'])) : null,
            'run_at' => sanitize_text_field(wp_unslash($_POST['run_at'])),
            'run_for' => (!empty($_POST['run_for'])) ? sanitize_text_field(wp_unslash($_POST['run_for'])) : null,
            'dates' => (!empty($_POST['dates'])) ? Sanitizer::array($_POST['dates']) : null, //phpcs:ignore
            'filter_items' => (!empty($_POST['filter_items'])) ? Sanitizer::array($_POST['filter_items']) : null, //phpcs:ignore
            'edit_items' => Sanitizer::array($_POST['edit_items']), //phpcs:ignore
            'stop_date' => (!empty($_POST['stop_date'])) ? sanitize_text_field(wp_unslash($_POST['stop_date'])) : null,
            'revert_date' => (!empty($_POST['revert_date'])) ? sanitize_text_field(wp_unslash($_POST['revert_date'])) : null,
        ]);

        if (!$result['success']) {
            wp_send_json([
                'success' => false,
                'message' => $result['message'],
            ]);
        }

        $awaiting_jobs = $this->repository->get_awaiting_count([
            'identifier' => $this->identifier
        ]);

        if ($_POST['run_at'] == 'now') {
            $handle_result = $this->schedule_handler();
            wp_send_json([
                'success' => (isset($handle_result['success'])) ? $handle_result['success'] : false,
                'is_processing' => (isset($handle_result['is_processing'])) ? $handle_result['is_processing'] : false,
                'awaiting_count' => $awaiting_jobs
            ]);
        }

        wp_send_json([
            'success' => true,
            'awaiting_count' => $awaiting_jobs
        ]);
    }

    private function get_all_filtered($filter_data)
    {
        $filter_service = Post_Filter_Service::get_instance();
        $posts = $filter_service->get_filtered_posts($filter_data, [
            'posts_per_page' => -1,
            'fields' => 'ids',
            'post_status' => ['any', 'trash'],
            'post_type' => [Post_Helper::get_active_post_type()],
        ]);

        return $posts['post_ids'];
    }
}
