<?php

namespace wpbel\classes\providers\item;

defined('ABSPATH') || exit(); // Exit if accessed directly

abstract class ItemProvider
{
    public function get_items($items, $columns)
    {
        return $this->items($items, $columns);
    }

    abstract protected function items($items, $columns);
}
