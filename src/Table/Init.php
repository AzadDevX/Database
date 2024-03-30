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
            throw new \Azad\Database\Exceptions\Structure("The class of data table [$Table_Name] has not been created - class: [".$Class."]");
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
    public function RowExists ($column,$value) {
        try {
            $this->Select ("*")->WHERE($column,$value)->LastRow();
            return true;
        } catch (\Azad\Database\Exceptions\Row $e) {
            return false;
        }
    }
    public function __call($method, $args) {
        if (!method_exists($this->ClassName,$method)) {
            throw new \Azad\Database\Exceptions\Structure("The method you intend to use to send data does not exist in the data table class. class: [".$this->ClassName."] method: [".$method."]");
        }
        return $this->ClassName::$method ($args);
    }
    public function __get($name) {
        return $this->ClassName::$$name;
    }
}
