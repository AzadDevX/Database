<?php
namespace Azad\Database\Types;
class ArrayData extends Init {
    public $SqlType = "JSON";
    public function Set($value) {
        return json_encode($value);
    }
    public function Get($value) {
        return json_decode($value,1);
    }
    public function is_valid ($data) {
        return (gettype($data) != "array") ? throw new \Azad\Database\Exception\DataType("The entered value for this data type is not valid.") : true;
    }
}