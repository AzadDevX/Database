<?php

namespace Azad\Database\Table\Columns\Update;

class Rows extends \Azad\Database\Table\Columns\Get {
    public $Data,$FindedData;
    private $Table,$LastKey;


    public function __construct($TableName,$FindedData,$hash) {
        $this->Table = $TableName;
        $this->FindedData = $FindedData;
        parent::$MyHash = $hash;
    }
    
    public function Key($key) {
        $this->LastKey = $key;
        parent::$UpdateData[$key] = null;
        return $this;
    }
    public function Value($value) {
        $value = $this->PreparingNewData($this->Table,[$this->LastKey=>$value],encrypt:false,set_datatype:false,venum:true);
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
    private function OperationData ($type,$value,$value2) {
        if ($type == "normal") {
            return $value2;
        } elseif ($type == "increase") {
            return $value + $value2;
        } elseif ($type == "decrease") {
            return $value2 - $value;
        }
    }

    public function Push () {

        $OldDataForWhere = $this->FindedData;
        $Data = $this->FindedData;
        foreach ($Data as $DKey=>$OldData) {
            $ArraySet = [];
            foreach ($OldData as $Key=>$Value) {
                if(isset(parent::$UpdateData[$Key])) {
                    $type_value = parent::$UpdateData[$Key]['type'];
                    $value = $this->OperationData ($type_value,$Value,parent::$UpdateData[$Key]['value']);
                    $Data[$DKey][$Key] = $value;
                    $ArraySet[$Key] = $value;
                }
            }
            $SendToQuery = $this->PreparingNewData($this->Table,$ArraySet,enumv:true,normalizer:false);
            $Query = parent::MakeQuery()::Edit($this->Table,$SendToQuery,parent::where_data($OldDataForWhere[$DKey],$this->Table));
            if($this->Query($Query) == false) {
                return false;
            }
        }
        return new \Azad\Database\Table\Columns\ReturnRows($this->Table,$Data,parent::$MyHash);
    }



}