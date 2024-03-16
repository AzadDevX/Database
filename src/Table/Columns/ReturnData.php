<?php

namespace Azad\Database\Table\Columns;

class ReturnData {
    public $Result,$Update,$Condition;
    public function __construct($Table_Name,$Data,$hash,$is_multiple_data=false) {
        $this->Result = $Data;
        $this->Update = new Update\Row($Table_Name,$Data,$hash);
        if ($is_multiple_data == true) {
            $this->Update->Data = $Data;
        }
        $this->Condition = new \Azad\Database\Conditions\Conditional($Data,true,$this->Update);
    }
}