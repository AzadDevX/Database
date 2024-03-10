<?php
namespace Azad\Database\Types;
class timestamp extends Init {
    public $SqlType = "timestamp";
    public function AddToQueryTable () {
        return "NULL";
    }
}