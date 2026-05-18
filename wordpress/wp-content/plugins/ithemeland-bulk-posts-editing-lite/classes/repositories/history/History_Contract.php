<?php

namespace wpbel\classes\repositories\history;

defined('ABSPATH') || exit(); // Exit if accessed directly

interface History_Contract
{
    public function revert($history_id);

    public function reset($history_id);
}
