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
}