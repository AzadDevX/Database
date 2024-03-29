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
        return (gettype($data) != "array") ? throw new \Azad\Database\Exceptions\DataType("The entered value for this data type is not valid. - $data must be an array, ".get_debug_type($data)." given. - DATA (JSON) ".json_encode($data)) : true;
    }
}