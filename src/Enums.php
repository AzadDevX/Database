<?php

namespace Azad\Database;

class Enums extends Database {
    public static function ValueToEnum($table,$column,$value) {
        $ColumnData = parent::$Tables[parent::$MyHash][$table]['columns'][$column];
        if (isset($ColumnData['enum'])) {
            if (isset($ColumnData['enum']::cases()[0]->value)) {
                $value = $ColumnData['enum']::tryFrom($value);
            } else {
                $value = constant("{$ColumnData['enum']}::{$value}");
            }
        }
        return $value;
    }
    public static function EnumToValue($table,$column,$enum) {
        $ColumnData = parent::$Tables[parent::$MyHash][$table]['columns'][$column];
        if (isset($ColumnData['enum'])) {
            if (!$enum instanceof $ColumnData['enum']) {
                throw new \Azad\Database\Exception\DataType("The values of the enum class do not match the database data.");
            }
            if (isset($ColumnData['enum']::cases()[0]->value)) {
                $value = $enum->value;
            } else {
                $value = $enum->name;
            }
        }
        return $value;
    }

}

/*





*/