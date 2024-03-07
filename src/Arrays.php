<?php

namespace Azad\Database;

class Arrays {
    public static function Value($data,$cb) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::Value ($value,$cb);
            } else {
                $data[$key] = $cb($value);
            }
        }
        return $data;
    }
}

?>
