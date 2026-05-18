<?php

namespace wpbel\classes\providers\column;

defined('ABSPATH') || exit(); // Exit if accessed directly

abstract class ColumnProvider
{
    public function get_item_columns($item, $columns)
    {
        return $this->item_columns($item, $columns);
    }

    abstract protected function item_columns($item, $columns);
}
