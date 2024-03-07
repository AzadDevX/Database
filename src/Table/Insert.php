<?php

namespace Azad\Database\Table;

class Insert extends \Azad\Database\Database {
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
    private function RebuilderResult($Rebuilder,$data) {
        $RebuilderName = parent::$ProjectName."\\Rebuilders\\".$Rebuilder;
        if (!class_exists($RebuilderName)) {
            throw new \Azad\Database\Exception\Load("Rebuilder [$RebuilderName] does not exist");
        }
        return $RebuilderName::Rebuild ($data);
    }
    public function Key ($Key) {
        $this->key = $Key;
        parent::$InsertData['columns']["key"][] = $Key;
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
        parent::$InsertData['columns']["value"][] = "'$Value'";
        return $this;
    }
    public function End() {
        $Result = false;
        try {
            $Table = (string) $this->TableName;
            $Data = parent::$InsertData;
            $Result = $this->Query(\Azad\Database\Query::Insert ($Table,$Data));
        } catch (\Azad\Database\Exception\SqlQuery $e) {
            if (parent::$InsertData['settings']["if_not_exists"] == true) {
                $Result = false;
            } else {
                throw new Exception($e->getMessage());
            }
        }
        parent::$InsertData = null;
        return $Result;
    }
}