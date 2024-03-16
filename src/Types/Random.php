<?php
namespace Azad\Database\Types;
class Random extends Init {
    public $SqlType = "BIGINT";
    public function InsertMe() {
        return rand(1,100000);
    }
    public function UpdateMe() {
        return rand(1,100000);
    }
}