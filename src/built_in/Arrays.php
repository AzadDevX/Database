<?php

namespace Azad\Database\built_in;

class Arrays {
    public static function Value(array $data,$cb) : array {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::Value ($value,$cb);
            } else {
                $data[$key] = $cb($value);
            }
        }
        return $data;
    }
    public static function ToObject(array $array) : object {
        return json_decode(json_encode($array));
    }
}
