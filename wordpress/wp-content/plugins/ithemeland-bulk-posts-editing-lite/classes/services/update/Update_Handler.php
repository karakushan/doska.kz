<?php

namespace wpbel\classes\services\update;

use wpbel\classes\repositories\History;
use wpbel\classes\services\background_process\PostBackgroundProcess;

defined('ABSPATH') || exit(); // Exit if accessed directly

abstract class Update_Handler
{
    abstract function update($post_ids, $update_data);

    protected function save_history_item($data)
    {
        $history_repository = new History();
        return $history_repository->save_history_item($data);
    }

    protected function add_completed_task($number)
    {
        $background_process = PostBackgroundProcess::get_instance();
        $background_process->add_completed_task(intval($number));
    }
}
