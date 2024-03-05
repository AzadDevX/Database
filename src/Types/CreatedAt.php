<?php
namespace Azad\Database\Types;
class CreatedAt extends Init {
    public $SqlType = "timestamp";
    public function AddToQueryTable () {
        return "DEFAULT CURRENT_TIMESTAMP";
    }
}
