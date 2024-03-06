<?php

namespace Azad\Database\Table;

class Insert extends \Azad\Database\Table\Init {
    private $key;
    public function __construct() {
        $TableName = (string) parent::$TableData['table_name'];
        array_walk(parent::$TableData[$TableName]['short'],function ($value,$key) {
            if(method_exists(new $value(),"InsertMe")) {
                $DB = new $value();
                $this->Key($key)->Value($DB->InsertMe());
            }
        });
    }
    public function Key ($Key) {
        $this->key = $Key;
        $this->Insert["key"][] = $Key;
        return $this;
    }
    public function Value ($Value) {
        $TableName = (string) parent::$TableData['table_name'];
        if (isset(parent::$TableData[$TableName]['data'][$this->key]['rebuilder'])) {
            $Value = $this->RebuilderResult(parent::$TableData[$TableName]['data'][$this->key]['rebuilder'],$Value);
        }
        if (isset(parent::$TableData[$TableName]['data'][$this->key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName]['data'][$this->key]['encrypter'];
            $EncrypetName = parent::$ProjectName."\\Encrypters\\".$EncrypetName;
            if (!class_exists($EncrypetName)) {
                throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
            }
            $Value = $EncrypetName::Encrypt($Value);
        }
        $this->Insert["value"][] = "'$Value'";
        return $this;
    }
    public function End() {
        try {
            $Table = parent::$TableData['table_name'];
            $Data = $this->Insert;
            return $this->Query(\Azad\Database\Query::Insert ($Table,$Data));
        } catch (\Azad\Database\Exception\SqlQuery $e) {
            if (parent::$InsertSetting["if_not_exists"] == true) {
                return false;
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }
}