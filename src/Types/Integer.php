<?php

namespace Azad\Database\Types;
class Integer extends Init {
    public $SqlType = "Int";
    public function is_valid ($data) {
        return (!is_numeric($data)) ? throw new \Azad\Database\Exception\DataType("The entered value for this data type is not valid.") : true;
    }
}