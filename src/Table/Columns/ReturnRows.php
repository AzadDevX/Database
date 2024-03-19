<?php

namespace Azad\Database\Table\Columns;

class ReturnRows {
    public $Result,$Update,$Condition;
    public function __construct($Table_Name,$Data,$hash) {
        $this->Result = $Data;
        $this->Update = new Update\Rows($Table_Name,$Data,$hash);
        // $this->Update->Data = $Data;
        $this->Condition = new \Azad\Database\Conditions\Conditional($Data,$Table_Name,$hash,false);
    }
}