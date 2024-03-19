<?php

namespace Azad\Database\Table\Columns\Update;

class Rows extends \Azad\Database\Table\Columns\Get {
    public $WhereQ,$Data,$FindedData;
    private $Table,$LastKey,$hash;


    public function __construct($TableName,$Query,$FindedData,$hash) {
        $this->WhereQ = $Query;
        $this->Table = $TableName;
        $this->FindedData = $FindedData;
        $this->hash = $hash;
    }
    
    public function Key($key) {
        $this->LastKey = $key;
        parent::$UpdateData[$key] = null;
        return $this;
    }
    public function Value($value) {
        parent::PreparationValues ($this->LastKey,$value,$this->Table);
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
                $Data[$DKey][$Key] = \Azad\Database\Enums::ValueToEnum($this->Table,$Key,$Data[$DKey][$Key]);
            }
            $Query = parent::MakeQuery()::Edit($this->Table,$Data[$DKey],parent::where_data($OldDataForWhere,$this->Table));
            if($this->Query($Query) == false) {
                return false;
            }
        }
        return new \Azad\Database\Table\Columns\ReturnData($this->Table,$Data,parent::$MyHash);
    }



}