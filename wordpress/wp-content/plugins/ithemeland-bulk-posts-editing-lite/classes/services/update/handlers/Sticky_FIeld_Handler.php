<?php

namespace wpbel\classes\services\update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Post;
use wpbel\classes\services\update\Update_Handler;

class Sticky_Field_Handler extends Update_Handler
{
    private $post_repository;
    private $post;
    private $update_data;
    private $current_field_value;
    private $is_processing;

    public function is_processing()
    {
        return $this->is_processing;
    }

    public function update($post_ids, $update_data)
    {
        if (empty($update_data['value'])) {
            return false;
        }

        foreach ($post_ids as $post_id) {
            $post_repository = new Post();
            $post = $post_repository->get_post(intval($post_id));
            if (!($post instanceof \WP_Post)) {
                return false;
            }

            $sticky_posts = get_option('sticky_posts', []);
            $sticky_posts = (!is_array($sticky_posts)) ? unserialize($sticky_posts) : $sticky_posts;
            $current_field_value = '';

            if ($update_data['value'] == 'yes') {
                if (!in_array($post_id, $sticky_posts)) {
                    $current_field_value = 'no';
                    $sticky_posts[] = intval($post_id);
                }
            } else {
                $key = array_search($post_id, $sticky_posts);
                if (isset($sticky_posts[$key])) {
                    $current_field_value = 'yes';
                    unset($sticky_posts[array_search($post_id, $sticky_posts)]);
                }
            }

            update_option('sticky_posts', $sticky_posts);

            if (!empty($this->update_data['background_process'])) {
                $this->add_completed_task(1);
            }

            // save history item
            if (!empty($update_data['history_id'])) {
                $this->save_history_item([
                    'history_id' => $update_data['history_id'],
                    'historiable_id' => $this->post->ID,
                    'name' => $update_data['name'],
                    'sub_name' => (!empty($update_data['sub_name'])) ? $update_data['sub_name'] : '',
                    'type' => $update_data['type'],
                    'prev_value' => $current_field_value,
                    'new_value' => $update_data['value'],
                ]);
            }
        }

        return true;
    }
}
