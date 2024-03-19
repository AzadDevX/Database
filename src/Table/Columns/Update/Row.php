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

        $value = $this->PreparingNewData($this->Table,[$this->LastKey=>$value],encrypt:false,set_datatype:false);
        parent::$UpdateData[$this->LastKey] = $value[$this->LastKey];
        return $this;
    }
    public function Increase ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = $this->Data[$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] += $value;
        return $this;
    }
    public function Decrease ($value) {
        if (!isset(parent::$UpdateData[$this->LastKey])) {
            parent::$UpdateData[$this->LastKey] = $this->Data[$this->LastKey];
        }
        parent::$UpdateData[$this->LastKey] -= $value;
        return $this;
    }

    public function Push () {
        if ($this->IFResult == false) {
            return false;
        }
        $NewData = array_merge($this->Data,parent::$UpdateData);
        $Result = $this->PreparingNewData($this->Table,$NewData,encrypt:false);
        $SendToQuery = $this->PreparingNewData($this->Table,parent::$UpdateData,enumv:true);

        if(parent::$SystemConfig[parent::$MyHash]['RAM'] == true) {
            parent::SaveToRam ($this->Table,[$NewData]);
        }
        $Query = parent::MakeQuery()::Edit($this->Table,$SendToQuery,parent::where_data($this->Data,$this->Table));
        if ($this->Query($Query) == false) {
            return false;
        }
        $this->Clear ();
        return new \Azad\Database\Table\Columns\ReturnData($this->Table,$Result,parent::$MyHash);
    }
    private function Clear () {
        parent::$UpdateData = null;
    }
}