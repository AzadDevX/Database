<?php
namespace Azad\Database\Types;
class Random extends Init {
    public $SqlType = "BIGINT";
    public function InsertMe() {
        return 12345;
    }
    public function UpdateMe() {
        return rand(1,100);
    }
}