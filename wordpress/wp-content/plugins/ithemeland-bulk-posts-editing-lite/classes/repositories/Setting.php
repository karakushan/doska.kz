<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\setting\Setting_Main;
use wpbel\classes\helpers\Post_Helper;

class Setting extends Setting_Main
{
    const MAX_COUNT_PER_PAGE = 50;

    public function __construct($post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : Post_Helper::get_active_post_type();
        $this->set_option_name($post_type);
    }

    private function set_option_name($post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->settings_option_name = "wpbe_{$post_type}_settings";
        $this->current_settings_option_name = "wpbe_{$post_type}_current_settings";
    }

    public function set_current_settings($settings)
    {
        $this->update_current_settings([
            'count_per_page' => ($settings['count_per_page'] > self::MAX_COUNT_PER_PAGE) ? self::MAX_COUNT_PER_PAGE : intval($settings['count_per_page']),
            'sticky_first_columns' => $settings['sticky_first_columns']
        ]);
    }

    public function set_default_settings()
    {
        return $this->update([
            'count_per_page' => 10,
            'default_sort_by' => 'id',
            'default_sort' => "DESC",
            'show_quick_search' => 'yes',
            'close_popup_after_applying' => 'no',
            'sticky_first_columns' => 'yes',
            'display_full_columns_title' => 'yes',
            'keep_filled_data_in_bulk_edit_form' => 'no',
            'display_cell_content' => 'long',
            'enable_background_processing' => 'yes',
        ]);
    }

    public function get_count_per_page_items()
    {
        return [
            '10',
            '25',
            '50',
        ];
    }
}
