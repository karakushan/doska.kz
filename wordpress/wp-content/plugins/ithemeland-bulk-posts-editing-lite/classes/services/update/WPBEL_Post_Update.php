<?php

namespace wpbel\classes\services\update;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\History;
use wpbel\classes\services\background_process\PostBackgroundProcess;
use wpbel\classes\services\update\handlers\Remove_Duplicate_Handler;
use wpbel\classes\services\update\handlers\Taxonomy_Handler;
use wpbel\classes\services\update\handlers\Meta_Field_Handler;
use wpbel\classes\services\update\handlers\Post_Action_Handler;
use wpbel\classes\services\update\handlers\Sticky_Field_Handler;
use wpbel\classes\services\update\handlers\WP_Posts_Handler;

class WPBEL_Post_Update
{
    private static $instance;
    private $post_ids;
    private $post_data;
    private $update_classes;
    private $save_history;
    private $is_processing;
    private $max_process_count;
    private $history_id;
    private $complete_actions;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->update_classes = $this->get_update_classes();
        $this->max_process_count = 100;
    }

    public function is_processing()
    {
        return $this->is_processing;
    }

    public function get_history_id()
    {
        return $this->history_id;
    }

    public function set_update_data($data)
    {
        if (!isset($data['post_ids']) || empty($data['post_data']) || !is_array($data['post_data'])) {
            return false;
        }
        $this->post_ids = array_unique($data['post_ids']);
        $this->post_data = $data['post_data'];
        $this->save_history = (!empty($data['save_history']));
        $this->complete_actions = (!empty($data['complete_actions'])) ? $data['complete_actions'] : null;
    }

    public function perform()
    {
        // save history
        if ($this->save_history) {
            $this->history_id = $this->save_history();
            if (empty($this->history_id)) {
                return false;
            }
        }

        $total_count = count($this->post_ids) * count($this->post_data);
        $background_process = PostBackgroundProcess::get_instance();
        $update_result = false;

        foreach ($this->post_data as $update_item) {
            if (!empty($this->history_id)) {
                $update_item['history_id'] = intval($this->history_id);
            }

            // check items
            if (!$this->is_valid_update_item($update_item)) {
                continue;
            }

            $class = $this->update_classes[$update_item['type']];
            if ($total_count > $this->max_process_count && PostBackgroundProcess::is_enable()) {
                if ($background_process->is_not_queue_empty()) {
                    if (!empty($this->history_id)) {
                        $this->delete_history($this->history_id);
                    }
                    return false;
                }

                foreach ($this->post_ids as $post_id) {
                    $background_process->push_to_queue([
                        'handler' => 'post_update',
                        'update_class' => $class,
                        'post_id' => $post_id,
                        'update_item' => $update_item,
                    ]);
                    $background_process->save();
                }
                $this->is_processing = true;
            } else {
                $instance = new $class();
                $update_result = $instance->update($this->post_ids, $update_item);
            }
        }

        if ($this->is_processing === true) {
            $background_process->set_total_tasks($total_count);
            if (!empty($this->complete_actions)) {
                foreach ($this->complete_actions as $action) {
                    $background_process->add_complete_action($action);
                }
            }
            $background_process->start();
        } else {
            if (!empty($this->complete_actions)) {
                foreach ($this->complete_actions as $action) {
                    if (!empty($action['hook'])) {
                        if (!empty($action['data'])) {
                            $action['data']['result'] = $update_result;
                            do_action($action['hook'], $action['data']);
                        } else {
                            do_action($action['hook']);
                        }
                    }
                }
            }
        }

        return true;
    }

    private function is_valid_update_item($update_item)
    {
        // has require item ?
        if (
            empty($update_item['name'])
            || empty($update_item['type'])
            || (empty($update_item['value'])
                && (!empty($update_item['operator'])
                    && !in_array($update_item['operator'], ['text_remove_duplicate', 'text_replace', 'number_clear', 'text_clear'])
                    && $update_item['operation'] != 'inline_edit'))
        ) {
            return false;
        }

        // has update method ?
        if (!isset($this->update_classes[$update_item['type']]) || !class_exists($this->update_classes[$update_item['type']])) {
            return false;
        }

        return true;
    }

    private function get_update_classes()
    {
        return apply_filters('wpbel_post_update_handlers', [
            'wp_posts_field' => WP_Posts_Handler::class,
            'meta_field' => Meta_Field_Handler::class,
            'sticky_field' => Sticky_Field_Handler::class,
            'post_action' => Post_Action_Handler::class,
            'taxonomy' => Taxonomy_Handler::class,
            'remove_duplicate' => Remove_Duplicate_Handler::class
        ]);
    }

    private function save_history()
    {
        $history_repository = new History();
        $fields = array_column($this->post_data, 'name');
        $history_id = $history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => (count($this->post_data) > 1) ? History::BULK_OPERATION : History::INLINE_OPERATION,
            'operation_date' => gmdate('Y-m-d H:i:s'),
        ]);

        return $history_id;
    }

    private function delete_history($history_id)
    {
        $history_repository = new History();
        return $history_repository->delete_history(intval($history_id));
    }
}
