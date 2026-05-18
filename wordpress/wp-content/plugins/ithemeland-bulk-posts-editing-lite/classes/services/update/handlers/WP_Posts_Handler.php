<?php

namespace wpbel\classes\services\update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\services\update\Update_Handler;

class WP_Posts_Handler extends Update_Handler
{
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
        if (empty($post_ids) && !is_array($post_ids)) {
            return false;
        }

        foreach ($post_ids as $post_id) {
            $this->update_data = $update_data;

            if (!isset($this->update_data['value'])) {
                $this->update_data['value'] = '';
            }

            $post = get_post(intval($post_id));
            if (!($post instanceof \WP_Post)) {
                return false;
            }

            $this->post = $post;
            $this->current_field_value = (!empty($this->post->{$this->update_data['name']})) ? $this->post->{$this->update_data['name']} : '';

            // replace text variable
            if (!is_numeric($this->update_data['value']) && !is_array($this->update_data['value'])) {
                $this->update_data['value'] = Post_Helper::apply_variable($post, $this->update_data['value']);
            }
            if (!empty($this->update_data['replace'])) {
                $this->update_data['replace'] = Post_Helper::apply_variable($post, $this->update_data['replace']);
            }

            // set value with operator
            if (!empty($this->update_data['operator'])) {
                $this->update_data['value'] = Post_Helper::apply_operator($this->current_field_value, $this->update_data);
            }

            if ($this->update_data['name'] == 'post_date') {
                $post_date = gmdate('Y-m-d H:i:s', strtotime($this->update_data['value']));
                $post_status = (time() < strtotime($this->update_data['value'])) ? 'future' : 'publish';
                $update_result = wp_update_post([
                    'ID' => intval($post_id),
                    'edit_date' => true,
                    'post_status' => $post_status,
                    'post_date' => $post_date,
                    'post_date_gmt' => get_date_from_gmt($post_date, 'Y-m-d H:i:s')
                ]);
            } else {
                $edit_args = [
                    'ID' => intval($post_id),
                    sanitize_text_field($this->update_data['name']) => sanitize_text_field($this->update_data['value'])
                ];
                $update_result = wp_update_post($edit_args);
            }

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
}
