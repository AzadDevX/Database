<?php

namespace Azad\Database\Table\Columns;

class Row extends Get {
    public $Condition,$QueryResult;
    private $FixedWhere;
    public $IFResult=true;

    public function __construct($TableName,$Query) {
        $this->TableName = $TableName;
        $this->QueryResult = $Query;
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
        parent::$query[$this->TableName] = $Query;
        $this->UpdateWhere ();
        array_walk(parent::$TableData[$TableName]['short'],function ($value,$key) {
            if(method_exists(new $value(),"UpdateMe")) {
                $DB = new $value();
                $this->Update($DB->UpdateMe(),$key);
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
    private function UpdateWhere () {
        $TableName = $this->TableName;
        array_walk(parent::$TableData[$TableName]['short'],function ($value,$key) use ($TableName) {
            $value = parent::$TableData["table_data"][0][$key];
            if ($value != null) {
                if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Set")) {
                    $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
                    $value = $DB->Set($value);
                }
                if (is_array($value)) {
                    $value = \Azad\Database\Arrays::Value($value,function ($data) {
                        return self::$DataBase->EscapeString ($data);
                    });
                } else {
                    $value = self::$DataBase->EscapeString ($value);
                }
                if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
                    $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
                    $EncrypetName = parent::$ProjectName."\\Encrypters\\".$EncrypetName;
                    if (!class_exists($EncrypetName)) {
                        throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                    }
                    $value = $EncrypetName::Encrypt(parent::$TableData["table_data"][0][$key]);
                }
                $this->FixedWhere[$key] = $value;
            }
        });
        return $this->FixedWhere;
    }
    public function Increase ($number,$key=null) {
        $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\Exception("Column not set.")):$key;
        $value = $number + parent::$TableData["table_data"][0][$key];
        $this->Update($value,$key);
    }
    public function Decrease($number,$key=null) {
        $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\Exception("Column not set.")):$key;
        $value = parent::$TableData["table_data"][0][$key] - $number;
        $this->Update($value,$key);
    }
    public function Update($value,$key=null) {
        $TableName = $this->TableName;
        $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\Exception("Column not set.")):$key;

        if (isset(parent::$TableData[$TableName]['data'][$key]['rebuilder'])) {
            if (!is_array($value)) {
                $value = $this->RebuilderResult(parent::$TableData[$TableName]['data'][$key]['rebuilder'],$value);
            } else {
                $Rebuilder = parent::$TableData[$TableName]['data'][$key]['rebuilder'];
                $value = \Azad\Database\Arrays::Value($value,function ($data) use ($Rebuilder) {
                    return $this->RebuilderResult($Rebuilder,$data);
                });
            }
        }
        if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
            $EncrypetName = parent::$ProjectName."\\Encrypters\\".$EncrypetName;
            if (!class_exists($EncrypetName)) {
                throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
            }
            $value = $EncrypetName::Encrypt($value);
        }
        if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Set")) {
            $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
            $value = $DB->Set($value);
        }
        $value = self::$DataBase->EscapeString ($value);

        if ($this->IFResult == false) {
            return false;
        }
        parent::$query[$this->TableName] = $this->QueryResult;
        $this->QueryResult = $this->Get($this->TableName);
        $this->UpdateWhere ();
        $Result = ($this->Query(\Azad\Database\Query::UpdateQuery($this->TableName,$value,$key,$this->FixedWhere)) == true)?$this:false;
        $this->Condition = new \Azad\Database\Conditions\Conditional($this->QueryResult[0],$this);
        return $Result;
    }
}
