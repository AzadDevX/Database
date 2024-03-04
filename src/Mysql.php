<?php

namespace Azad\Database;

class Mysql {
    public $Database;
    public function __construct($host, $username, $password, $database) {
        $this->Database = new \mysqli($host, $username, $password, $database);
    }
    public function QueryRun ($command) {
        return $this->Database->query($command);
    }
    public function FetchQuery ($QueryData) {
        $Data=[];
        if ($QueryData->num_rows > 0) {
            while($row = $QueryData->fetch_assoc()) {
                $Data[] = $row;
            }
        }
        return $Data;
    }
}