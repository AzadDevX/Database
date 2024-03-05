<?php

namespace Azad\Database\Table\Columns;

class Row extends Init {
    public $Condition,$QueryResult;
    private $FixedWhere;

    public function __construct() {
        $this->QueryResult = $this->Get();
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
        $TableName = parent::$TableData['table_name'];
        $this->UpdateWhere ();
        array_walk(parent::$TableData[$TableName]['short'],function ($value,$key) {
            if(method_exists(new $value(),"UpdateMe")) {
                $DB = new $value();
                $this->Update($DB->UpdateMe(),$key);
                $this->UpdateWhere ();
            }
        });
    }
    private function UpdateWhere () {
        $TableName = parent::$TableData['table_name'];
        array_walk(parent::$TableData[$TableName]['short'],function ($value,$key) use ($TableName) {
            if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
                $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
                $this->FixedWhere[$key] = $EncrypetName::Encrypt(parent::$TableData["table_data"][0][$key]);
            } else {
                $this->FixedWhere[$key] = parent::$TableData["table_data"][0][$key];
            }
        });

        return $this->FixedWhere;
    }
    public function Update($value,$key=null) {
        $TableName = parent::$TableData['table_name'];
        $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\AzadException("Column not set.")):$key;
        if (isset(parent::$TableData[$TableName]['data'][$key]['rebuilder'])) {
            $value = $this->RebuilderResult(parent::$TableData[$TableName]['data'][$key]['rebuilder'],$value);
        }
        if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
            $value = $EncrypetName::Encrypt($value);
        }
        if ($this->IFResult == false) {
            return false;
        }
        $Result = ($this->Query(\Azad\Database\Query::UpdateQuery(parent::$TableData,$value,$key,$this->FixedWhere)) == true)?$this:false;
        $this->QueryResult = $this->Get();
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
        return $Result;
    }
}

?>