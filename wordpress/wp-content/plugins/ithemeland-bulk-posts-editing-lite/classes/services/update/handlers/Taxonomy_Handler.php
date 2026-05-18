<?php

namespace wpbel\classes\services\update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\repositories\Post;
use wpbel\classes\services\update\Update_Handler;

class Taxonomy_Handler extends Update_Handler
{
    private $post_repository;
    private $post;
    private $setter_method;
    private $update_data;
    private $current_field_value;
    private $is_processing;

    public function is_processing()
    {
        return $this->is_processing;
    }

    public function update($post_ids, $update_data)
    {
        $this->setter_method = $this->get_setter($update_data['name']);
        if (empty($this->setter_method) && empty($post_ids) && !is_array($post_ids)) {
            return false;
        }

        // has update method ?
        if (!method_exists($this, $this->setter_method)) {
            return false;
        };

        $this->post_repository = new Post();

        foreach ($post_ids as $post_id) {
            $this->update_data = $update_data;

            if (!isset($this->update_data['value'])) {
                $this->update_data['value'] = '';
            }

            $this->post = $this->post_repository->get_post(intval($post_id));
            if (!($this->post instanceof \WP_Post)) {
                return false;
            }

            $this->current_field_value = (!empty($this->update_data['name'])) ? wp_get_post_terms($this->post->ID, $this->update_data['name'], ['fields' => 'ids']) : '';

            // run update method
            $this->{$this->setter_method}();

            if (!empty($this->update_data['background_process'])) {
                $this->add_completed_task(1);
            }

            // save history item
            if (!empty($this->update_data['history_id'])) {
                $this->save_history_item([
                    'history_id' => $this->update_data['history_id'],
                    'historiable_id' => $this->post->ID,
                    'name' => $this->update_data['name'],
                    'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                    'type' => $this->update_data['type'],
                    'prev_value' => $this->current_field_value,
                    'new_value' => $this->update_data['value'],
                ]);
            }
        }

        return true;
    }

    private function get_setter($field_name)
    {
        $setter_methods = $this->get_setter_methods();
        return (!empty($setter_methods[$field_name])) ? $setter_methods[$field_name] : $setter_methods['default_taxonomy'];
    }

    private function get_setter_methods()
    {
        return [
            'default_taxonomy' => 'set_default_taxonomy',
        ];
    }

    private function set_default_taxonomy()
    {
        if (!empty($this->update_data['operator'])) {
            $this->update_data['value'] = Post_Helper::apply_operator($this->current_field_value, $this->update_data);
        }
        return wp_set_post_terms($this->post->ID, $this->update_data['value'], $this->update_data['name'], false);
    }
}
