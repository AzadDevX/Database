<?php

namespace Azad\Database\built_in;

class Sort {
    private static $Data;

    public static function TableForeign ($TableData) {
        self::$Data = [];
        self::TF_Sort ($TableData);
        return self::$Data;
    }
    private static function TF_Sort ($TableData) {
        foreach ($TableData as $Key => $Value) {
            if (in_array($TableData[$Key]["foreign_from"],array_keys(self::$Data)) || $Value["foreign_from"] == false) {
                self::$Data[$Key] = $Value;
                unset($TableData[$Key]);
                self::TF_Sort($TableData);
            }
        }
    }
}