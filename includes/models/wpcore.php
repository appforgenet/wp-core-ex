<?php

namespace appforge\coreex\includes\models;

use appforge\coreex\includes\models\Request;
use appforge\coreex\includes\models\WPCore;
use appforge\coreex\includes\models\Database;

    class WPCore
    {
        /** @var WPCore $wpcore */
        public static $app;

        public $request;
        public $urlManager;
        public $db;

        public static function init()
        {
            static::$app = new WPCore();
            static::$app->request = new Request();
            static::$app->db = new Database();
        }
    }
?>