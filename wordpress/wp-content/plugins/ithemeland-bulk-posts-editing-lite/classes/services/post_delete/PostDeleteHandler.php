<?php

namespace wpbel\classes\services\post_delete;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\History;
use wpbel\classes\services\background_process\PostBackgroundProcess;

class PostDeleteHandler
{
    public function handle($data)
    {
        if (empty($data['post_ids']) || !is_array($data['post_ids']) || empty($data['delete_type'])) {
            return false;
        }

        $trashed = [];
        if (count($data['post_ids']) > PostDeleteService::MAX_PROCESS_COUNT && PostBackgroundProcess::is_enable()) {
            $this->push_to_queue($data['post_ids'], $data['delete_type'], $data['history_id']);
        } else {
            foreach ($data['post_ids'] as $post_id) {
                if ($data['delete_type'] == 'permanently') {
                    wp_delete_post(intval($post_id), true);
                } else {
                    $trashed[] = intval($post_id);
                    wp_trash_post(intval($post_id));
                }
                if (isset($data['background_process']) && $data['background_process'] === true) {
                    $background_process = PostBackgroundProcess::get_instance();
                    $background_process->add_completed_task(1);
                }
            }
        }

        if (!empty($trashed) && !empty($data['history_id'])) {
            $this->save_history_items($data['history_id'], $trashed);
        }
    }

    private function push_to_queue($post_ids, $delete_type, $history_id)
    {
        $background_process = PostBackgroundProcess::get_instance();
        if ($background_process->is_not_queue_empty()) {
            return false;
        }

        $ids = array_chunk($post_ids, PostDeleteService::MAX_PROCESS_COUNT);
        foreach ($ids as $items) {
            if (empty($items) || !is_array($items)) {
                continue;
            }
            $background_process->push_to_queue([
                'handler' => 'post_delete',
                'post_ids' => array_map('intval', $items),
                'delete_type' => $delete_type,
                'history_id' => $history_id
            ]);
            $background_process->save();
        }
        $background_process->set_total_tasks(count($post_ids));
        $background_process->start();
    }

    private function save_history_items($history_id, $post_ids)
    {
        if (empty($history_id) || empty($post_ids) || !is_array($post_ids)) {
            return false;
        }

        $history_repository = new History();
        foreach ($post_ids as $post_id) {
            $history_repository->save_history_item([
                'history_id' => intval($history_id),
                'historiable_id' => intval($post_id),
                'name' => 'post_delete',
                'type' => 'post_action',
                'prev_value' => 'untrash',
                'new_value' => 'trash',
                'prev_total_count' => 1,
                'new_total_count' => 1,
            ]);
        }

        return true;
    }
}
