<?php
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