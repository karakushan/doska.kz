<?php

namespace wpbel\classes\services\background_process\handlers;

use wpbel\classes\repositories\Post;
use wpbel\classes\services\background_process\PostBackgroundProcess;

defined('ABSPATH') || exit(); // Exit if accessed directly

class PostDuplicateHandler implements HandlerInterface
{
    public function handle($item)
    {
        if (empty($item['count']) || empty($item['post_id'])) {
            return false;
        }

        $post_repository = new Post();
        $post = $post_repository->get_post(intval($item['post_id']));
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        for ($i = 1; $i <= intval($item['count']); $i++) {
            $post_repository->duplicate($post);
        }

        $background_process = PostBackgroundProcess::get_instance();
        $background_process->add_completed_task(intval($item['count']));
    }
}
