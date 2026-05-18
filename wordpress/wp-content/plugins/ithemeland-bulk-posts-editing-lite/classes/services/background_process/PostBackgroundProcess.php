<?php

namespace wpbel\classes\services\background_process;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\services\background_process\BackgroundProcess;
use wpbel\classes\repositories\Setting;
use wpbel\classes\services\background_process\handlers\HistoryRedoHandler;
use wpbel\classes\services\background_process\handlers\HistoryUndoHandler;
use wpbel\classes\services\background_process\handlers\PostCreateHandler;
use wpbel\classes\services\background_process\handlers\PostDeleteHandler;
use wpbel\classes\services\background_process\handlers\PostDuplicateHandler;
use wpbel\classes\services\background_process\handlers\PostRestoreHandler;
use wpbel\classes\services\background_process\handlers\PostUpdateHandler;

class PostBackgroundProcess extends BackgroundProcess
{
    private static $instance;
    private static $is_enable;

    protected $prefix = 'wpbe';

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        $setting_repository = new Setting();
        $settings = $setting_repository->get_settings();
        self::$is_enable = (!empty($settings['enable_background_processing']) && $settings['enable_background_processing'] == 'no') ? false : true;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public static function get_instance()
    {
        return self::$instance;
    }

    public static function is_enable()
    {
        return (!defined('WPBE_ACTIVE') || !WPBE_ACTIVE) ? false : self::$is_enable;
    }

    protected function get_handlers()
    {
        return [
            'post_update' => PostUpdateHandler::class,
            'post_create' => PostCreateHandler::class,
            'post_delete' => PostDeleteHandler::class,
            'post_restore' => PostRestoreHandler::class,
            'post_duplicate' => PostDuplicateHandler::class,
            'history_undo' => HistoryUndoHandler::class,
            'history_redo' => HistoryRedoHandler::class,
        ];
    }
}
