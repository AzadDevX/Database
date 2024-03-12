<?php

namespace Azad\Database\Table;

class Init extends \Azad\Database\Database {
    public $Insert;
    private $TableName,$ClassName;
    protected static $InsertSetting=[];
    public function __construct($Table_Name) {
        $Class = parent::$name_prj."\\Tables\\".$Table_Name;
        if (!class_exists($Class)) {
            throw new Exception("Table [$Table_Name] does not exist");
        }
        $this->ClassName = $Class;
        $this->TableName = parent::$is_have_prefix?parent::$TablePrefix."_".$Table_Name:$Table_Name;
    }
    public function Select (...$Column) {
        $Query = \Azad\Database\Query::SelectQuery($Column,$this->TableName);
        return new Columns\Init($this->TableName,$Query);
    }
    public function Insert ($if_not_exists=true) {
        self::$InsertData['settings']["if_not_exists"] = $if_not_exists;
        return new Insert($this->TableName);
    }
    public function __call($method, $args) {
        if (!method_exists($this->ClassName,$method)) {
            throw new Exception("Method [$method] [$this->TableName] does not exist");
        }
        return $this->ClassName::$method ($args);
    }
    public function __get($name) {
        return $this->ClassName::$$name;
    }
}