<?php

namespace Azad\Database\Table;

class Insert extends \Azad\Database\Table\Init {
    private $key;
    public function __construct() { }
    public function Key ($Key) {
        $this->key = $Key;
        $this->Insert["key"][] = $Key;
        return $this;
    }
    public function Value ($Value) {
        $TableName = parent::$TableData['table_name'];
        if (isset(parent::$TableData[$TableName][$this->key]['rebuilder'])) {
            $Value = $this->RebuilderResult(parent::$TableData[$TableName][$this->key]['rebuilder'],$Value);
        }
        if (isset(parent::$TableData[$TableName][$this->key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName][$this->key]['encrypter'];
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
        } catch (\Azad\Database\Exception $e) {
            if (parent::$InsertSetting["if_not_exists"] == true) {
                return false;
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }
}