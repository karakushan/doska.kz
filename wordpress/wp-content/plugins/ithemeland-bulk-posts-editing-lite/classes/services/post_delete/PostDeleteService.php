<?php

namespace wpbel\classes\services\post_delete;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\History;

class PostDeleteService
{
    const MAX_PROCESS_COUNT = 50;

    private $is_processing;

    public function __construct()
    {
        $this->is_processing = false;
    }

    public function perform($post_ids, $delete_type)
    {
        if (empty($post_ids) || empty($delete_type)) {
            return false;
        }

        if (count($post_ids) > self::MAX_PROCESS_COUNT) {
            $this->is_processing = true;
        }

        $history_id = 0;
        if ($delete_type != 'permanently') {
            $history_id = $this->save_history();
        }

        $post_delete = new PostDeleteHandler();
        return $post_delete->handle([
            'history_id' => $history_id,
            'post_ids' => $post_ids,
            'delete_type' => $delete_type
        ]);
    }

    private function save_history()
    {
        $history_repository = new History();
        return $history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize(['post_delete']),
            'operation_type' => History::BULK_OPERATION,
            'operation_date' => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function is_processing()
    {
        return $this->is_processing;
    }
}
