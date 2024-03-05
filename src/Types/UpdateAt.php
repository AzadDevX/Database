<?php
namespace Azad\Database\Types;
class UpdateAt extends Init {
    public $SqlType = "timestamp";
    public function AddToQueryTable () {
        return "DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }
}
