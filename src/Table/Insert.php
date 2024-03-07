<?php

namespace Azad\Database\Table;

class Insert extends \Azad\Database\Table\Init {
    private $key;
    private $TableName;

    public function __construct($TableName) {
        $this->TableName = $TableName;
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
        $TableName = $this->TableName;
        if (isset(parent::$TableData[$TableName]['data'][$this->key]['rebuilder'])) {
            if (!is_array($Value)) {
                $Value = $this->RebuilderResult(parent::$TableData[$TableName]['data'][$this->key]['rebuilder'],$Value);
            } else {
                $Rebuilder = parent::$TableData[$TableName]['data'][$this->key]['rebuilder'];
                $Value = \Azad\Database\Arrays::Value($Value,function ($data) use ($Rebuilder) {
                    return $this->RebuilderResult($Rebuilder,$data);
                });
            }
        }
        if (isset(parent::$TableData[$TableName]['data'][$this->key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName]['data'][$this->key]['encrypter'];
            $EncrypetName = parent::$ProjectName."\\Encrypters\\".$EncrypetName;
            if (!class_exists($EncrypetName)) {
                throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
            }
            $Value = $EncrypetName::Encrypt($Value);
        }
        if(method_exists(new parent::$TableData[$TableName]['data'][$this->key]['type'],"Set")) {
            $DB = new parent::$TableData[$TableName]['data'][$this->key]['type']();
            $Value = $DB->Set($Value);
        }
        $Value = self::$DataBase->EscapeString ($Value);
        $this->Insert["value"][] = "'$Value'";
        return $this;
    }
    public function End() {
        try {
            $Table = (string) $this->TableName;
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