<?php

namespace wpbel\classes\services\post_duplicate;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Post;
use wpbel\classes\services\background_process\PostBackgroundProcess;

class PostDuplicateService
{
    const MAX_PROCESS_COUNT = 10;
    const MAX_PROCESS_IDS = 10;

    private $is_processing;

    public function perform($data)
    {
        if (empty($data['post_ids']) || !is_array($data['post_ids']) || empty($data['count'])) {
            return false;
        }

        if ((count($data['post_ids']) > self::MAX_PROCESS_IDS || intval($data['count']) > self::MAX_PROCESS_COUNT) && PostBackgroundProcess::is_enable()) {
            $this->push_to_queue($data);
        } else {
            $post_repository = new Post();
            foreach ($data['post_ids'] as $post_id) {
                $post = $post_repository->get_post(intval($post_id));
                if (!($post instanceof \WP_Post)) {
                    continue;
                }

                for ($i = 1; $i <= intval($data['count']); $i++) {
                    $post_repository->duplicate($post);
                }
            }
        }

        return true;
    }

    private function push_to_queue($data)
    {
        $background_process = PostBackgroundProcess::get_instance();
        if ($background_process->is_not_queue_empty()) {
            return false;
        }

        foreach ($data['post_ids'] as $post_id) {
            if ($data['count'] > self::MAX_PROCESS_COUNT) {
                $round = ceil(intval($data['count']) / self::MAX_PROCESS_COUNT);
                for ($i = 1; $i <= intval($round); $i++) {
                    if ($i == $round) {
                        $count = intval($data['count']) - (($round - 1) * self::MAX_PROCESS_COUNT);
                    } else {
                        $count = self::MAX_PROCESS_COUNT;
                    }

                    $background_process->push_to_queue([
                        'handler' => 'post_duplicate',
                        'post_id' => intval($post_id),
                        'count' => intval($count),
                    ]);
                    $background_process->save();
                }
            } else {
                $background_process->push_to_queue([
                    'handler' => 'post_duplicate',
                    'post_id' => intval($post_id),
                    'count' => intval($data['count']),
                ]);
                $background_process->save();
            }
        }
        $background_process->set_total_tasks(count($data['post_ids']) * intval($data['count']));
        $background_process->start();
        $this->is_processing = true;
        return true;
    }

    public function is_processing()
    {
        return $this->is_processing;
    }
}
