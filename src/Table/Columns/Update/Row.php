<?php

namespace Azad\Database\Table\Columns\Update;

class Row extends \Azad\Database\Table\Columns\Get {
    private $Table,$LastKey;
    public $IFResult=true,$Data;

    public function __construct($TableName,$Data) {
        $this->Data = $Data;
        $this->Table = $TableName;
    }

    public function Key($key) {
        $this->LastKey = $key;
        parent::$UpdateData[$key] = null;
        return $this;
    }
    public function Value($value) {
        $value = $this->PreparationValues ($this->LastKey,$value);
        parent::$UpdateData[$this->LastKey] = $value;
        return $this;
    }
    public function Increase ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = parent::$TableData["table_data"][0][$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] += $value;
        return $this;
    }
    public function Decrease ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = parent::$TableData["table_data"][0][$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] -= $value;
        return $this;
    }

    public function Push () {
        if ($this->IFResult == false) {
            return false;
        }
        $NewData = parent::$UpdateData;
        $Query = \Azad\Database\Query::UpdateQuery($this->Table,parent::$UpdateData,$this->where($this->Data));
        $Result = ($this->Query($Query) == true)?$NewData:false;
        $this->Clear ();
        $this->UpdateVariables ($Result);
        return $Result;
    }

    private function UpdateVariables ($new_data) {
        array_walk($new_data,function ($value,$key) { parent::$TableData['table_data'][0][$key] = $value; });
    }
    private function RebuilderResult($Rebuilder,$data) {
        $RebuilderName = parent::$name_prj."\\Rebuilders\\".$Rebuilder;
        if (!class_exists($RebuilderName)) {
            throw new \Azad\Database\Exception\Load("Rebuilder [$RebuilderName] does not exist");
        }
        return $RebuilderName::Rebuild ($data);
    }
    private function PreparationValues ($key,$value) {
        $TableName = $this->Table;
        # ---- Rebuilder
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
        # ---- Encrypter
        if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
            $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
            $EncrypetName = parent::$name_prj."\\Encrypters\\".$EncrypetName;
            if (!class_exists($EncrypetName)) {
                throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
            }
            $value = $EncrypetName::Encrypt($value);
        }
        # ---- Set method (in type)
        if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Set")) {
            $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
            $value = $DB->Set($value);
        }
        # ---- Escape String
        return parent::$DataBase->EscapeString ($value);
    }
    private function where($old_data) {
        $new = [];
        foreach ($old_data as $key=>$value) {
            if ($value == null or $value == [] or $value == '') {
                continue;
            }
            if (isset(parent::$TableData[$this->Table]['data'][$key]['encrypter'])) {
                $EncrypetName = parent::$TableData[$this->Table]['data'][$key]['encrypter'];
                $EncrypetName = parent::$name_prj."\\Encrypters\\".$EncrypetName;
                if (!class_exists($EncrypetName)) {
                    throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                }
                $value = $EncrypetName::Encrypt($value);
            }
            if(method_exists(new parent::$TableData[$this->Table]['data'][$key]['type'],"Set")) {
                $DB = new parent::$TableData[$this->Table]['data'][$key]['type']();
                $value = $DB->Set($value);
            }
            $new[$key] = $value;
        }
        return $new;
    }
    private function Clear () {
        parent::$UpdateData = null;
    }
}