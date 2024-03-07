<?php

namespace Azad\Database\Table\Columns;

class Rows extends Get {
    public $QueryResult;
    private $LQuery;
    public function __construct($TableName,$query) {
        $this->LQuery = $query;
        $this->TableName = $TableName;
    }
    public function ToArray () {
        $this->QueryResult = $this->Get($this->TableName);
        return $this->QueryResult;
    }
    public function First () {
        return new Row($this->TableName,$this->LQuery);
    }
}