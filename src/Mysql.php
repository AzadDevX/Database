<?php

namespace Azad\Database;

class Mysql {
    public $Database;
    public function __construct($host, $username, $password, $database) {
        try {
            $this->Database = new \mysqli($host, $username, $password, $database);
        } catch (\mysqli_sql_exception $e) {
            throw new Exception\Connection("Failed to connect to database: ".$e->getMessage());
        }
    }
    public function EscapeString ($string) {
        return $string != ''?$this->Database->real_escape_string($string):'';
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
