<?php

namespace wpbel\classes\services\background_process\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\services\post_delete\PostDeleteHandler as PostDeleteServiceHandler;

class PostDeleteHandler implements HandlerInterface
{
    public function handle($item)
    {
        if (empty($item['post_ids']) || empty($item['delete_type'])) {
            return;
        }

        $post_delete = new PostDeleteServiceHandler();
        $post_delete->handle([
            'post_ids' => $item['post_ids'],
            'delete_type' => $item['delete_type'],
            'background_process' => true,
            'history_id' => isset($item['history_id']) ? $item['history_id'] : 0,
        ]);
    }
}
