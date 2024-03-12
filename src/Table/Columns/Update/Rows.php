<?php

namespace Azad\Database\Table\Columns\Update;

class Rows extends \Azad\Database\Table\Columns\Get {
    public $WhereQ,$Data,$FindedData;
    private $Table,$LastKey;


    public function __construct($TableName,$Query,$FindedData) {
        $this->WhereQ = $Query;
        $this->Table = $TableName;
        $this->FindedData = $FindedData;
    }
    
    public function Key($key) {
        $this->LastKey = $key;
        parent::$UpdateData[$key] = null;
        return $this;
    }
    public function Value($value) {
        $value = $this->PreparationValues ($this->LastKey,$value);
        parent::$UpdateData[$this->LastKey]['value'] = $value;
        parent::$UpdateData[$this->LastKey]['type'] = "normal";
        return $this;
    }
    public function Increase ($value) {
        parent::$UpdateData[$this->LastKey]['value'] = $value;
        parent::$UpdateData[$this->LastKey]['type'] = "increase";
        return $this;
    }
    public function Decrease ($value) {
        parent::$UpdateData[$this->LastKey]['value'] = $value;
        parent::$UpdateData[$this->LastKey]['type'] = "decrease";
        return $this;
    }

    public function Push () {

        $OldDataForWhere = (isset($this->Data)) ? $this->Data : $this->FindedData;

        $Data = (isset($this->Data)) ? $this->Data : $this->FindedData;

        foreach ($Data as $DKey=>$OldData) {
            foreach ($OldData as $Key=>$Value) {
                if(isset(parent::$UpdateData[$Key])) {
                    $type_value = parent::$UpdateData[$Key]['type'];
                    if ($type_value == "normal") {
                        $Data[$DKey][$Key] = parent::$UpdateData[$Key]['value'];
                    } elseif ($type_value == "increase") {
                        $Data[$DKey][$Key] = $Value + parent::$UpdateData[$Key]['value'];
                    } elseif ($type_value == "decrease") {
                        $Data[$DKey][$Key] = parent::$UpdateData[$Key]['value'] - $Value;
                    }
                }
            }
            $Query = \Azad\Database\Query::UpdateQuery($this->Table,$Data[$DKey],$this->where($OldDataForWhere[$DKey]));
            if($this->Query($Query) != true) {
                throw new Exception("Failed Update - Query: ".$Query);
            }
        }
        return $Data;
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