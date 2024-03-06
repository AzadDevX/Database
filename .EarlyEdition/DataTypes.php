<?php

namespace Azad\DataType;

class Data {
    public $data;
    public function __construct($data="") {
        $this->data = $data;
    }
}

class Varchar extends Data {
    public $SqlType = "varchar";
}

class Integer extends Data {
    public $SqlType = "Int";
}