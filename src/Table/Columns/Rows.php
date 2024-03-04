<?php

namespace Azad\Database\Table\Columns;

class Rows extends Init {
    public $QueryResult;
    public function ToArray () {
        $this->QueryResult = $this->Get();
        return $this->QueryResult;
    }
    public function First () {
        return new Row();
    }
}