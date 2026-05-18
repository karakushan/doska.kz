<?php

namespace wpbel\classes\services\history;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\History;
use wpbel\classes\repositories\Post;
use wpbel\classes\services\background_process\PostBackgroundProcess;
use wpbel\classes\services\update\WPBEL_Post_Update;

class HistoryRedoService
{
    const MAX_PROCESS_COUNT = 100;

    private $is_processing;
    private $post_ids;
    private $total_count;
    private $history_repository;
    private $history_id;
    private $complete_actions;

    public function __construct()
    {
        $this->is_processing = false;
        $this->history_repository = new History();
    }

    public function is_processing()
    {
        return $this->is_processing;
    }

    public function get_total_tasks()
    {
        return $this->total_count;
    }

    public function set_data($data)
    {
        $this->history_id = (!empty($data['history_id'])) ? intval($data['history_id']) : 0;
        $this->complete_actions = (!empty($data['complete_actions'])) ? $data['complete_actions'] : null;
    }

    public function perform()
    {
        if (empty($this->history_id)) {
            return false;
        }

        $this->post_ids = [];
        $this->total_count = $this->history_repository->get_history_items_total_count($this->history_id, 'new_total_count');
        if ($this->total_count == 0) {
            return false;
        }

        if ($this->total_count > self::MAX_PROCESS_COUNT && PostBackgroundProcess::is_enable()) {
            return $this->push_to_queue();
        } else {
            return $this->redo();
        }
    }

    private function push_to_queue()
    {
        $background_process = PostBackgroundProcess::get_instance();
        if ($background_process->is_not_queue_empty()) {
            return false;
        }

        $this->is_processing = true;
        $history_items = $this->history_repository->get_history_rows(intval($this->history_id), [
            'columns' => ['historiable_id', 'field', 'new_value', 'new_total_count'],
            'orderby' => 'ASC'
        ]);

        foreach ($history_items as $item) {
            $item->new_value = is_serialized($item->new_value) ? unserialize($item->new_value) : $item->new_value;
            $field = unserialize($item->field);
            $post_data = [
                [
                    'name' => $field['name'],
                    'sub_name' => (!empty($field['sub_name'])) ? $field['sub_name'] : '',
                    'type' => $field['type'],
                    'deleted_ids' => !empty($field['deleted_ids']) ? $field['deleted_ids'] : [],
                    'operator' => '',
                    'value' => $item->new_value,
                    'operation' => 'inline_edit',
                    'background_process' => true,
                ]
            ];

            $background_process->push_to_queue([
                'handler' => 'history_redo',
                'post_data' => $post_data,
                'post_id' => intval($item->historiable_id),
                'history_id' => $this->history_id,
            ]);
            $background_process->save();
        }

        $background_process->set_total_tasks($this->total_count);
        $background_process->add_complete_action([
            'handler' => 'history_redo',
            'data' => [
                'history_id' => $this->history_id
            ]
        ]);

        if (!empty($this->complete_actions)) {
            foreach ($this->complete_actions as $action) {
                $background_process->add_complete_action($action);
            }
        }

        $background_process->start();
        return true;
    }

    private function redo()
    {
        $history_items = $this->history_repository->get_history_rows(intval($this->history_id), [
            'columns' => ['historiable_id', 'field', 'new_value'],
            'orderby' => 'ASC'
        ]);

        if (empty($history_items) || !is_array($history_items)) {
            $this->do_complete_actions(false);
            return false;
        }

        $update_service = WPBEL_Post_Update::get_instance();
        foreach ($history_items as $item) {
            $field = unserialize($item->field);
            if (empty($field) || !is_array($field)) {
                continue;
            }

            if (isset($field['name']) && isset($field['type'])) {
                $post_ids = [intval($item->historiable_id)];
                $post_data = [
                    [
                        'name' => $field['name'],
                        'sub_name' => (!empty($field['sub_name'])) ? $field['sub_name'] : '',
                        'type' => $field['type'],
                        'deleted_ids' => !empty($field['deleted_ids']) ? $field['deleted_ids'] : [],
                        'operator' => '',
                        'value' => unserialize($item->new_value),
                        'operation' => 'inline_edit',
                    ]
                ];

                $update_service->set_update_data([
                    'post_ids' => $post_ids,
                    'post_data' => $post_data,
                    'save_history' => false,
                ]);
                $update_result = $update_service->perform();
                if (!$update_result) {
                    $this->do_complete_actions(false);
                    return false;
                }
            } else {
                $this->old_reset($field, $item);
            }
        }

        $this->history_repository->update_history($this->history_id, ['reverted' => 0]);

        $this->do_complete_actions(true);
        return  true;
    }

    private function do_complete_actions($result)
    {
        if (!empty($this->complete_actions)) {
            foreach ($this->complete_actions as $action) {
                if (!empty($action['hook'])) {
                    if (!empty($action['data'])) {
                        $action['data']['result'] = $result;
                        do_action($action['hook'], $action['data']);
                    } else {
                        do_action($action['hook']);
                    }
                }
            }
        }
    }

    public function get_post_ids()
    {
        return $this->post_ids;
    }

    private function old_reset($field, $item)
    {
        $post_repository = new Post();
        foreach ($field as $field_type => $field_name) {
            if (is_numeric($field_type)) {
                switch ($field_name) {
                    case '_thumbnail_id':
                        $post_repository->update([$item->historiable_id], [
                            'field_type' => 'main_field',
                            'field' => $field_name,
                            'value' => intval((unserialize($item->new_value))['id'])
                        ]);
                        break;
                    case 'post_delete':
                        wp_trash_post(intval($item->historiable_id));
                        break;
                    default:
                        $post_repository->update([$item->historiable_id], [
                            'field_type' => 'main_field',
                            'field' => $field_name,
                            'value' => unserialize($item->new_value)
                        ]);
                }
            } else {
                switch ($field_type) {
                    case 'custom_field':
                        if (is_array($field_name)) {
                            $new_val = unserialize($item->new_value);
                            foreach ($field_name as $field_item) {
                                $post_repository->update([$item->historiable_id], [
                                    'field_type' => 'custom_field',
                                    'field' => $field_item,
                                    'value' => (isset($new_val[$field_type][$field_item])) ? $new_val[$field_type][$field_item] : '',
                                    'operator' => 'taxonomy_replace'
                                ]);
                            }
                        } else {
                            $post_repository->update([$item->historiable_id], [
                                'field_type' => 'custom_field',
                                'field' => $field_name,
                                'value' => unserialize($item->new_value),
                                'operator' => 'taxonomy_replace'
                            ]);
                        }

                        break;
                    case 'taxonomy':
                        if (is_array($field_name)) {
                            $new_val = unserialize($item->new_value);
                            foreach ($field_name as $field_item) {
                                $post_repository->update([$item->historiable_id], [
                                    'field_type' => 'taxonomy',
                                    'field' => $field_item,
                                    'value' => (isset($new_val[$field_type][$field_item])) ? $new_val[$field_type][$field_item] : [],
                                    'operator' => 'taxonomy_replace'
                                ]);
                            }
                        } else {
                            $post_repository->update([$item->historiable_id], [
                                'field_type' => 'taxonomy',
                                'field' => $field_name,
                                'value' => unserialize($item->new_value),
                                'operator' => 'taxonomy_replace'
                            ]);
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
