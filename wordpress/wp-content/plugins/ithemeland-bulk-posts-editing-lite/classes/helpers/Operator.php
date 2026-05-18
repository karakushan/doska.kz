<?php

namespace wpbel\classes\helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class Operator
{
    public static function edit_text($extra = [])
    {
        $operators =  [
            'text_new' => esc_html__('New', 'ithemeland-bulk-posts-editing-lite'),
            'text_append' => esc_html__('Append', 'ithemeland-bulk-posts-editing-lite'),
            'text_prepend' => esc_html__('Prepend', 'ithemeland-bulk-posts-editing-lite'),
            'text_delete' => esc_html__('Delete', 'ithemeland-bulk-posts-editing-lite'),
            'text_replace' => esc_html__('Replace', 'ithemeland-bulk-posts-editing-lite'),
            'text_clear' => esc_html__('Clear', 'ithemeland-bulk-posts-editing-lite'),
        ];

        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $key => $label) {
                $operators[sanitize_text_field($key)] = sanitize_text_field($label);
            }
        }

        return $operators;
    }

    public static function edit_taxonomy()
    {
        return [
            'taxonomy_append' => esc_html__('Append', 'ithemeland-bulk-posts-editing-lite'),
            'taxonomy_replace' => esc_html__('Replace', 'ithemeland-bulk-posts-editing-lite'),
            'taxonomy_delete' => esc_html__('Delete', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => esc_html__('Set New', 'ithemeland-bulk-posts-editing-lite'),
            'number_clear' => esc_html__('Clear Value', 'ithemeland-bulk-posts-editing-lite'),
            'number_formula' => esc_html__('Formula', 'ithemeland-bulk-posts-editing-lite'),
            'increase_by_value' => esc_html__('Increase by value', 'ithemeland-bulk-posts-editing-lite'),
            'decrease_by_value' => esc_html__('Decrease by value', 'ithemeland-bulk-posts-editing-lite'),
            'increase_by_percent' => esc_html__('Increase by %', 'ithemeland-bulk-posts-editing-lite'),
            'decrease_by_percent' => esc_html__('Decrease by %', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => esc_html__('Increase by value (From sale)', 'ithemeland-bulk-posts-editing-lite'),
            'increase_by_percent_from_sale' => esc_html__('Increase by % (From sale)', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => esc_html__('Decrease by value (From regular)', 'ithemeland-bulk-posts-editing-lite'),
            'decrease_by_percent_from_regular' => esc_html__('Decrease by % (From regular)', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => esc_html__('Like', 'ithemeland-bulk-posts-editing-lite'),
            'exact' => esc_html__('Exact', 'ithemeland-bulk-posts-editing-lite'),
            'not' => esc_html__('Not', 'ithemeland-bulk-posts-editing-lite'),
            'begin' => esc_html__('Begin', 'ithemeland-bulk-posts-editing-lite'),
            'end' => esc_html__('End', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function filter_multi_select()
    {
        return [
            'or' => esc_html__('OR', 'ithemeland-bulk-posts-editing-lite'),
            'and' => esc_html__('AND', 'ithemeland-bulk-posts-editing-lite'),
            'not_in' => esc_html__('NOT IN', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function round_items()
    {
        return [
            5 => 5,
            10 => 10,
            19 => 19,
            29 => 29,
            39 => 39,
            49 => 49,
            59 => 59,
            69 => 69,
            79 => 79,
            89 => 89,
            99 => 99
        ];
    }

    public static function get_current_value()
    {
        return [
            //'' => esc_html__('Current Value', 'ithemeland-bulk-posts-editing-lite'),
            'yes' => esc_html__('Yes', 'ithemeland-bulk-posts-editing-lite'),
            'no' => esc_html__('No', 'ithemeland-bulk-posts-editing-lite'),
        ];
    }

    public static function get_all_operators_name()
    {
        $operators = array_merge(
            self::edit_text(),
            self::edit_taxonomy(),
            self::edit_number(),
            self::edit_regular_price(),
            self::edit_sale_price(),
            self::filter_text(),
            self::filter_multi_select(),
            self::get_current_value(),
        );

        return $operators;
    }
}
