<?php
namespace Azad\Database\Types;
class Token extends Init {
    public $SqlType = "VARCHAR";
    public function InsertMe() {
        return sha1(rand(1,999999) + time() + rand(1,999999));
    }
}