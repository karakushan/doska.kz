<?php

namespace wpbel\classes\services\export;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\helpers\Post_Helper;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\services\export\handlers\CSV_Handler;
use wpbel\classes\services\export\handlers\XML_Handler;
use wpbel\classes\services\filter\Post_Filter_Service;

class Export_Service
{
    private $search_repository;
    private $post_repository;
    private $column_repository;

    public function __construct()
    {
        $this->column_repository = new Column();
        $this->post_repository = new Post();
        $this->search_repository = new Search();
    }

    public function export($data)
    {
        $handler = $this->get_handler($data['export_type']);
        if (empty($handler) || !class_exists($handler)) {
            return false;
        }

        $last_filter_data = isset($this->search_repository->get_current_data()['last_filter_data']) ? $this->search_repository->get_current_data()['last_filter_data'] : null;

        switch ($data['posts']) {
            case 'all':
                $filter_service = Post_Filter_Service::get_instance();
                $posts = $filter_service->get_filtered_posts($last_filter_data, [
                    'posts_per_page' => '-1',
                    'post_type' => [Post_Helper::get_active_post_type()],
                    'fields' => 'ids',
                ]);
                $post_ids = $posts['post_ids'];
                break;
            case 'selected':
                $post_ids = isset($data['item_ids']) ? $data['item_ids'] : [];
                break;
        }

        switch ($data['fields']) {
            case 'all':
                $columns = $this->column_repository->get_columns();
                break;
            case 'visible':
                $columns = $this->column_repository->get_active_columns()['fields'];
                break;
        }

        $handler_object = new $handler();
        return $handler_object->export([
            'post_ids' => $post_ids,
            'columns' => $columns
        ]);
    }

    private function get_handler($handler)
    {
        $handlers = $this->get_handlers();
        return (!empty($handlers[$handler])) ? $handlers[$handler] : '';
    }

    private function get_handlers()
    {
        return [
            'csv' => CSV_Handler::class,
            'xml' => XML_Handler::class,
        ];
    }
}
