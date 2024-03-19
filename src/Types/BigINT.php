<?php
namespace Azad\Database\Types;
class BigINT extends Init {
    public $SqlType = "BIGINT";
    public function is_valid ($data) {
        return (!is_numeric($data)) ? throw new \Azad\Database\Exceptions\DataType("The entered value for this data type is not valid.") : true;
    }
}