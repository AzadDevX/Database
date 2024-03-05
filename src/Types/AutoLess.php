<?php
namespace Azad\Database\Types;
class AutoLess extends Init {
    public $SqlType = "INT";
    public function InsertMe() {
        return 9999;
    }
    public function Set($value) {
        return $value - 1;
    }
}