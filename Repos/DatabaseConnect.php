<?php

namespace Azad;

class Database {
    public $Database;
    public function __construct($host, $username, $password, $database) {
        $this->Database = new \mysqli($host, $username, $password, $database);
    }
    public function Query ($command) {
        return $this->Database->query($command);
    }
}