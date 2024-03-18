<?php

namespace Azad\Database\Table\Columns\Update;

class Row extends \Azad\Database\Table\Columns\Get {
    private $Table,$LastKey;
    public $IFResult=true,$Data;

    public function __construct($TableName,$Data,$hash) {
        $this->Data = $Data;
        $this->Table = $TableName;
        parent::$MyHash = $hash;
    }
    public function Key($key) {
        $this->LastKey = $key;
        parent::$UpdateData[$key] = null;
        return $this;
    }
    public function Value($value) {
        $value = parent::PreparationValues ($this->LastKey,$value,$this->Table);
        parent::$UpdateData[$this->LastKey] = $value;
        return $this;
    }
    public function Increase ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = parent::$Tables[parent::$MyHash][$this->Table]['data'][$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] += $value;
        return $this;
    }
    public function Decrease ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = parent::$Tables[parent::$MyHash][$this->Table]['data'][$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] -= $value;
        return $this;
    }

    public function Push () {
        if ($this->IFResult == false) {
            return false;
        }

        
        $NewData = array_merge($this->Data,parent::$UpdateData);
        foreach($NewData as $key=>$value) {
            $NewData[$key] = \Azad\Database\Enums::ValueToEnum($this->Table,$key,$value);
        }

        if(parent::$SystemConfig[parent::$MyHash]['RAM'] == true) {
            parent::SaveToRam ($this->Table,[$NewData]);
        }
        $Query = parent::MakeQuery()::Edit($this->Table,parent::$UpdateData,parent::where_data($this->Data,$this->Table));
        $Result = ($this->Query($Query) == true)?$NewData:false;
        if ($Result == false) {
            return false;
        }
        $this->Clear ();
        return new \Azad\Database\Table\Columns\ReturnData($this->Table,$NewData,parent::$MyHash);
    }
    private function Clear () {
        parent::$UpdateData = null;
    }
}