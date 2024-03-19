<?php
namespace Azad\Database\Types;
class Token extends Init {
    public $SqlType = "VARCHAR";
    public function InsertMe() {
        return sha1(bin2hex(random_bytes(32)));
    }
}