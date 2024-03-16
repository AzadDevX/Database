<?php

namespace Azad\Database\Databases\Mysql;

class Database extends \Azad\Database\Databases\Database {
    public function __construct() {
        if(!class_exists("\mysqli")) {
            throw new Exception("class mysqli not found");
        }
    }
    public function Connect ($config) {
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $name = $config['name'];
        try {
            $this->Database = new \mysqli($host, $username, $password, $name);
        } catch (\mysqli_sql_exception $e) {
            throw new Exception("Failed to connect to database: ".$e->getMessage());
        }
        return $this;
    }
    public function Run ($query) {
        $this->Result = $this->Database->query($query);
        $this->affected_rows = $this->Database->affected_rows;
        $this->LastID = $this->Database->insert_id;
        return $this;
    }

    public function EscapeString ($string) {
        return ($string != '' and $string != null)?$this->Database->real_escape_string($string):'';
    }

    public function Fetch ($data) {
        $Data=[];
        if ($data->num_rows > 0) {
            while($row = $data->fetch_assoc()) {
                $Data[] = $row;
            }
        }
        return $Data;
    }
}