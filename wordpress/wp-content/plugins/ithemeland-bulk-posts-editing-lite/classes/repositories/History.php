<?php

namespace wpbel\classes\repositories;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\history\History_Main;
use wpbel\classes\helpers\Post_Helper;

class History extends History_Main
{
    public function __construct($post_type = "")
    {
        parent::__construct();
        $this->set_sub_system($post_type);
    }

    private function set_sub_system($post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->sub_system = "wordpress_{$post_type}";
    }
}
