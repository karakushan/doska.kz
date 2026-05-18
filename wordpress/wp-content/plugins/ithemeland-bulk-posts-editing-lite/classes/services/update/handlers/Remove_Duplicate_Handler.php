<?php

namespace wpbel\classes\services\update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Others;
use wpbel\classes\repositories\Post;
use wpbel\classes\services\update\Update_Handler;

class Remove_Duplicate_Handler extends Update_Handler
{
    private $deleted_ids;
    private $update_data;
    private $post_ids;

    public function __construct()
    {
        $this->deleted_ids = [];
    }

    public function update($post_ids, $update_data)
    {
        $method = $this->get_method($update_data['value']);
        if (empty($method)) {
            return false;
        }

        $this->post_ids = $post_ids;
        $this->update_data = $update_data;

        // action
        return $this->{$method}();
    }

    private function get_method($value)
    {
        $methods = $this->get_methods();
        return (!empty($methods[$value]) && method_exists($this, $methods[$value])) ? $methods[$value] : '';
    }

    private function get_methods()
    {
        return [
            'trash' => 'trash_post',
            'untrash' => 'untrash_post'
        ];
    }

    private function trash_post()
    {
        $ids = (!empty($this->update_data['deleted_ids']) && is_array($this->update_data['deleted_ids'])) ? $this->update_data['deleted_ids'] : $this->get_post_ids_with_like_names();
        if (!empty($ids)) {
            foreach ($ids as $post_id) {
                wp_trash_post(intval($post_id));
                $this->deleted_ids[] = intval($post_id);
            }
        }

        if (!empty($this->update_data['background_process'])) {
            $this->add_completed_task(1);
        }

        // save history item
        if (!empty($this->update_data['history_id'])) {
            $this->save_history_item([
                'history_id' => $this->update_data['history_id'],
                'historiable_id' => 0,
                'name' => $this->update_data['name'],
                'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                'type' => $this->update_data['type'],
                'deleted_ids' => $this->deleted_ids,
                'prev_value' => 'untrash',
                'new_value' => 'trash',
                'prev_total_count' => count($this->deleted_ids),
                'new_total_count' => count($this->deleted_ids),
            ]);
        }

        return true;
    }

    private function get_post_ids_with_like_names()
    {
        $output = [];
        $post_repository = new Post();
        $post_ids = (!empty($this->post_ids) && is_array($this->post_ids) && !empty($this->post_ids[0])) ? $this->post_ids : [];
        $posts = $post_repository->get_post_ids_with_like_names($post_ids);

        if (!empty($posts)) {
            // move to trash
            foreach ($posts as $post) {
                $ids = explode(',', $post['post_ids']);
                if (!empty($ids)) {
                    $ids = array_reverse($ids);
                    // unset last post
                    if (!empty($ids[0])) {
                        unset($ids[0]);
                    }

                    $output[] = $ids;
                }
            }
        }

        return Others::array_flatten($output);
    }

    private function untrash_post()
    {
        if (empty($this->update_data['deleted_ids'])) {
            return false;
        }

        foreach ($this->update_data['deleted_ids'] as $post_id) {
            wp_untrash_post(intval($post_id));
            $this->deleted_ids[] = intval($post_id);
        }

        return true;
    }
}
