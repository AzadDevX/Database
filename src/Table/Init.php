<?php

namespace Azad\Database\Table;

class Init extends \Azad\Database\Database {
    public $Insert;
    private $TableName,$ClassName,$Hash;
    protected static $InsertSetting=[];
    public function __construct($Table_Name,$hash) {
        parent::$MyHash = $hash;
        $Class = parent::$name_prj[parent::$MyHash]."\\Tables\\".$Table_Name;
        if (!class_exists($Class)) {
            throw new Exception("Table [$Table_Name] does not exist");
        }
        $this->Hash = $hash;
        $this->ClassName = $Class;
        $this->TableName = parent::$is_have_prefix[parent::$MyHash]?parent::$TablePrefix[parent::$MyHash]."_".$Table_Name:$Table_Name;
    }
    public function Select (...$Column) {
        $Query = parent::MakeQuery()::Select($Column,$this->TableName);
        return new Columns\Init($this->TableName,$Query,$this->Hash);
    }
    public function Insert ($if_not_exists=true) {
        self::$InsertData['settings']["if_not_exists"] = $if_not_exists;
        return new Insert($this->TableName,$this->Hash);
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