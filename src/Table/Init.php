<?php

namespace Azad\Database\Table;

class Init extends \Azad\Database\Database {
    public $Insert;
    private $TableName;
    protected static $InsertSetting=[];
    public function __construct($Table_Name) {
        $this->TableName = $Table_Name;
    }
    public function Select (...$Column) {
        $Query = \Azad\Database\Query::SelectQuery($Column,$this->TableName);
        return new Columns\Init($this->TableName,$Query);
    }
    public function Insert ($if_not_exists=true) {
        self::$InsertData['settings']["if_not_exists"] = $if_not_exists;
        return new Insert($this->TableName);
    }
}