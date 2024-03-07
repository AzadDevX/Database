<?php
namespace Azad\Database\Types;
class Token extends Init {
    public $SqlType = "VARCHAR";
    public function InsertMe() {
        return sha1(rand(1,9999) + time());
    }
}