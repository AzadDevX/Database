<?php
namespace Azad\Database\Types;
class ID extends Init {
    public $SqlType = "BIGINT";
    public $Primary = true;
    public function AddToQueryTable () {
        return "AUTO_INCREMENT";
    }
}
