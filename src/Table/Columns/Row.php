<?php

namespace Azad\Database\Table\Columns;

class Row extends Init {
    public $Condition,$QueryResult;
    public function __construct() {
        $this->QueryResult = $this->Get();
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
    }
    public function Update($value,$key=null) {
        $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\AzadException("Column not set.")):$key;
        $TableName = parent::$TableData['table_name'];
        if (isset(parent::$TableData[$TableName][$key]['rebuilder'])) {
            $value = $this->RebuilderResult(parent::$TableData[$TableName][$key]['rebuilder'],$value);
        }
        if (isset(parent::$TableData[$TableName][$key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName][$key]['encrypter'];
            $value = $EncrypetName::Encrypt($value);
            parent::$TableData["table_data"][0][$key] = $EncrypetName::Encrypt(parent::$TableData["table_data"][0][$key]);
        }
        if ($this->IFResult == false) {
            return false;
        }
        $Result = ($this->Query(\Azad\Database\Query::UpdateQuery(parent::$TableData,$value,$key)) == true)?$this:false;
        $this->QueryResult = $this->Get();
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
        return $Result;
    }
}

?>