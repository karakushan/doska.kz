<?php

namespace wpbel\classes\services\update\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\services\update\Update_Handler;

class Post_Action_Handler extends Update_Handler
{
    private $post_id;

    public function update($post_ids, $update_data)
    {
        $methods = $this->get_methods();
        $method = (!empty($methods[$update_data['value']])) ? $methods[$update_data['value']] : '';
        if (empty($method) || !method_exists($this, $method)) {
            return false;
        }

        foreach ($post_ids as $post_id) {
            $this->post_id = intval($post_id);
            $this->{$method}();
            if (isset($update_data['background_process']) && $update_data['background_process'] === true) {
                $this->add_completed_task(1);
            }
        }

        return true;
    }

    private function get_methods()
    {
        return [
            'trash' => 'delete_post',
            'untrash' => 'restore_post'
        ];
    }

    private function delete_post()
    {
        return wp_trash_post($this->post_id);
    }

    private function restore_post()
    {
        return wp_untrash_post($this->post_id);
    }
}
