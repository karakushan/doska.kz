<?php

namespace wpbel\classes\services\background_process\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Post;
use wpbel\classes\services\background_process\PostBackgroundProcess;

class PostCreateHandler implements HandlerInterface
{
    public function handle($item)
    {
        if (empty($item['count']) || empty($item['post_data'])) {
            return;
        }

        $post_repository = new Post();
        for ($i = 1; $i <= intval($item['count']); $i++) {
            $post_repository->create($item['post_data']);
        }

        $background_process = PostBackgroundProcess::get_instance();
        $background_process->add_completed_task(intval($item['count']));
    }
}
