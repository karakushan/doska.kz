<?php

namespace wpbel\classes\services\background_process\handlers;

use wpbel\classes\services\background_process\PostBackgroundProcess;

defined('ABSPATH') || exit(); // Exit if accessed directly

class PostRestoreHandler implements HandlerInterface
{
    public function handle($item)
    {
        if (empty($item['post_ids'])) {
            return;
        }

        if (!empty($item['post_ids']) && is_array($item['post_ids'])) {
            foreach ($item['post_ids'] as $post_id) {
                wp_untrash_post(intval($post_id));
            }

            $background_process = PostBackgroundProcess::get_instance();
            $background_process->add_completed_task(count($item['post_ids']));
        }
    }
}
