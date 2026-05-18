<?php

namespace wpbel\classes\helpers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Formula;
use wpbel\classes\repositories\Post;

class Post_Helper
{
    public static function round($value, $round_item)
    {
        $division = intval('1' . str_repeat('0', wc_get_price_decimals()));
        switch ($round_item) {
            case 5:
            case 10:
                $value += floatval($round_item / $division);
                $decimals = floatval($value - floor($value));
                $value = floor($value) + ($decimals - floatval(intval(($decimals * $division) . '') % $round_item) / $division);
                break;
            case 9:
            case 19:
            case 29:
            case 39:
            case 49:
            case 59:
            case 69:
            case 79:
            case 89:
            case 99:
                $value = intval($value) + floatval($round_item / $division);
                break;
            default:
                break;
        }

        return $value;
    }

    public static function posts_id_parser($ids)
    {
        $output = '';
        $ids_array = explode('|', $ids);
        if (is_array($ids_array) && !empty($ids_array)) {
            foreach ($ids_array as $item) {
                $output .= self::parser($item);
            }
        } else {
            $output .= self::parser($ids_array);
        }

        return rtrim($output, ',');
    }

    private static function parser($ids_string)
    {
        $output = '';
        if (strpos($ids_string, '-') > 0) {
            $from_to = explode('-', $ids_string);
            if (isset($from_to[0]) && isset($from_to[1])) {
                for ($i = intval($from_to[0]); $i <= intval($from_to[1]); $i++) {
                    $output .= $i . ',';
                }
            }
        } else {
            $output = $ids_string . ',';
        }

        return $output;
    }

    public static function get_tax_query($taxonomy, $terms, $operator = null, $field = null)
    {
        $field = !empty($field) ? $field : 'term_id';
        $values = (is_array($terms)) ? array_map('urldecode', $terms) : $terms;
        switch ($operator) {
            case null:
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'AND'
                ];
                break;
            case 'or':
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'IN'
                ];
                break;
            case 'and':
                $tax_item['relation'] = 'AND';
                if (is_array($values) && !empty($values)) {
                    foreach ($values as $value) {
                        $tax_item[] = [
                            'taxonomy' => urldecode($taxonomy),
                            'field' => $field,
                            'terms' => [$value],
                        ];
                    }
                }
                break;
            case 'not_in':
                $tax_item = [
                    'taxonomy' => urldecode($taxonomy),
                    'field' => $field,
                    'terms' => $values,
                    'operator' => 'NOT IN'
                ];
                break;
        }
        return $tax_item;
    }

    public static function get_post_type_name(string $post_type = '')
    {
        $post_type = (!empty($post_type)) ? $post_type : Post_Helper::get_active_post_type();
        return (in_array($post_type, ['post', 'page'])) ? $post_type : 'custom_post';
    }

    public static function apply_operator($old_value, $data)
    {
        if (empty($data['operator'])) {
            return $data['value'];
        }

        $data['value'] = (!empty($data['operator_type'])) ? self::apply_calculator_operator($old_value, $data) : self::apply_default_operator($old_value, $data);
        $data['value'] = (isset($data['round']) && !empty($data['round'])) ? self::round($data['value'], $data['round']) : $data['value'];

        return $data['value'];
    }

    private static function apply_calculator_operator($old_value, $data)
    {
        $old_value = floatval($old_value);
        $data['value'] = floatval($data['value']);
        $data['sale_price'] = (isset($data['sale_price'])) ? floatval($data['sale_price']) : 0;
        $data['regular_price'] = (isset($data['regular_price'])) ? floatval($data['regular_price']) : 0;

        switch ($data['operator_type']) {
            case 'n':
                switch ($data['operator']) {
                    case '+':
                        $data['value'] += $old_value;
                        break;
                    case '-':
                        $data['value'] = $old_value - $data['value'];
                        break;
                }
                break;
            case '%':
                switch ($data['operator']) {
                    case '+':
                        $data['value'] = $old_value + ($old_value * $data['value'] / 100);
                        break;
                    case '-':
                        $data['value'] = $old_value - ($old_value * $data['value'] / 100);
                        break;
                }
                break;
        }

        return $data['value'];
    }

    private static function apply_default_operator($old_value, $data)
    {
        switch ($data['operator']) {
            case 'text_append':
                $data['value'] = $old_value . $data['value'];
                break;
            case 'text_prepend':
                $data['value'] = $data['value'] . $old_value;
                break;
            case 'text_new':
                $data['value'] = $data['value'];
                break;
            case 'text_delete':
                $data['value'] = str_replace($data['value'], '', $old_value);
                break;
            case 'text_replace':
                if (isset($data['value'])) {
                    $data['value'] = ($data['sensitive'] == 'yes') ? str_replace($data['value'], $data['replace'], $old_value) : str_ireplace($data['value'], $data['replace'], $old_value);
                } else {
                    $data['value'] = $old_value;
                }
                break;
            case 'text_remove_duplicate':
                $data['value'] = $old_value;
                break;
            case 'taxonomy_append':
                $data['value'] = array_unique(array_merge($old_value, $data['value']));
                break;
            case 'taxonomy_replace':
                $data['value'] = $data['value'];
                break;
            case 'taxonomy_delete':
                $data['value'] = array_values(array_diff($old_value, $data['value']));
                break;
            case 'number_new':
                $data['value'] = $data['value'];
                break;
            case 'number_delete':
                $data['value'] = str_replace($data['value'], '', $old_value);
                break;
            case 'text_clear':
                $data['value'] = '';
                break;
            case 'number_clear':
                $data['value'] = '';
                break;
            case 'number_formula':
                $formulaCalculator = new Formula();
                if (empty($old_value)) {
                    $old_value = 0;
                }
                $data['value'] = $formulaCalculator->calculate(strtolower($data['value']), ['x' => $old_value]);
                break;
            case 'increase_by_value':
                $data['value'] = floatval($old_value) + floatval($data['value']);
                break;
            case 'decrease_by_value':
                $data['value'] = floatval($old_value) - floatval($data['value']);
                break;
            case 'increase_by_percent':
                $data['value'] = floatval($old_value) + floatval(floatval($old_value) * floatval($data['value']) / 100);
                break;
            case 'decrease_by_percent':
                $data['value'] = floatval($old_value) - floatval(floatval($old_value) * floatval($data['value']) / 100);
                break;
        }

        return $data['value'];
    }

    public static function apply_variable($post, $value)
    {
        if (!($post instanceof \WP_Post)) {
            return $value;
        }
        $post_repository = new Post();
        $parent = $post_repository->get_post(intval($post->post_parent));
        $value = str_replace('{title}', $post->post_title, $value);
        $value = str_replace('{id}', $post->ID, $value);
        $value = str_replace('{menu_order}', $post->menu_order, $value);
        $value = str_replace('{parent_id}', $post->post_parent, $value);
        if ($parent instanceof \WP_Post) {
            $value = str_replace('{parent_title}', $parent->post_title, $value);
        } else {
            $value = str_replace('{parent_title}', '', $value);
        }
        return $value;
    }

    public static function get_active_post_type()
    {
        return get_option('wpbe_active_post_type', 'post');
    }

    public static function set_post_type($post_type)
    {
        return update_option('wpbe_active_post_type', sanitize_text_field($post_type));
    }
}
