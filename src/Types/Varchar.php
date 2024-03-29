<?php

namespace Azad\Database\Types;

class Varchar extends Init {
    public $SqlType = "varchar";
    public function is_valid ($data) {
        return (!is_string($data) and !is_numeric($data)) ? throw new \Azad\Database\Exceptions\DataType("The entered value for this data type is not valid. - $data must be an string, ".get_debug_type($data)." given. - DATA (JSON) ".json_encode($data)) : true;
    }
}
