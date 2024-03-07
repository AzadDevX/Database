<?php

namespace Azad\Database\Table\Columns;

class Rows extends Init {
    public $QueryResult;
    private $TableName;
    public function __construct($TableName) {
        $this->TableName = $TableName;
    }
    public function ToArray () {
        $this->QueryResult = $this->Get();
        return $this->QueryResult;
    }
    public function First () {
        return new Row($this->TableName,$this->QueryResult);
    }
}