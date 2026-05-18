<?php

namespace wpbel\classes\services\background_process\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\History;
use wpbel\classes\services\update\WPBEL_Post_Update;

class HistoryUndoHandler implements HandlerInterface
{
    public function handle($item)
    {
        if (empty($item['post_data']) || empty($item['post_id'])) {
            return false;
        }

        $update_service = WPBEL_Post_Update::get_instance();
        $update_service->set_update_data([
            'post_ids' => [intval($item['post_id'])],
            'post_data' => $item['post_data'],
            'save_history' => false,
        ]);

        return $update_service->perform();
    }

    public function complete($data)
    {
        if (empty($data['history_id'])) {
            return false;
        }

        if (isset($data['background_process_result']) && $data['background_process_result']) {
            $history_repository = new History();
            return $history_repository->update_history(intval($data['history_id']), ['reverted' => 1]);
        }
    }
}
