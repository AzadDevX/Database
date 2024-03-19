<?php

namespace Azad\Database\Table\Columns;

class ReturnRow {
    public $Result,$Update,$Condition;
    public function __construct($Table_Name,$Data,$hash) {
        $this->Result = $Data;
        $this->Update = new Update\Row($Table_Name,$Data,$hash);
        $this->Condition = new \Azad\Database\Conditions\Conditional($Data,$Table_Name,$hash,true);
    }
}