<?php

namespace appforge\coreex\includes\models;

    class Database
    {
        public $wpdb;

        public function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
        }
    }
?>