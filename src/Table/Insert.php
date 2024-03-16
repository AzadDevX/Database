<?php

namespace Azad\Database\Table;

class Insert extends \Azad\Database\Database {
    private $key;
    private $TableName;
    private $Hash;

    public function __construct($TableName,$Hash) {
        parent::$MyHash = $Hash;
        $this->TableName = $TableName;
        $this->Hash = $Hash;
        array_walk(parent::$Tables[$Hash][$TableName]['short_types'],function ($value,$key) {
            if(method_exists(new $value(),"InsertMe")) {
                $DB = new $value();
                $this->Key($key)->Value($DB->InsertMe());
            }
        });
    }
    public function Key ($Key) {
        $this->key = $Key;
        parent::$InsertData[parent::$MyHash]["key"][] = $Key;
        return $this;
    }
    public function Value ($Value) {
        $TableName = $this->TableName;
        $Value = parent::PreparationValues ($this->key,$Value,$TableName);
        parent::$InsertData[parent::$MyHash]["value"][] = "'$Value'";
        return $this;
    }
    public function End() {
        $Result = false;
        try {
            $Table = (string) $this->TableName;
            $Data = parent::$InsertData[parent::$MyHash];
            $Query = parent::MakeQuery()::Insert(['table'=>$Table,'columns'=>$Data]);
            $Result = $this->Query($Query);
        } catch (\Azad\Database\Exception\SqlQuery $e) {
            if (parent::$InsertData[parent::$MyHash]['settings']["if_not_exists"] == true) {
                $Result = false;
            } else {
                throw new Exception($e->getMessage());
            }
        }
        parent::$InsertData = null;
        return (!$Result)?false:parent::$DataBase[parent::$MyHash]->LastID;
    }
}