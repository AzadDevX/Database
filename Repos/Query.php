<?php

namespace Azad {
    class Query {
        public static function MakeTable($table_name,$data) {
            $Query = "CREATE TABLE IF NOT EXISTS ";
            $Query .= $table_name;
            $Query .= " (";
            $keys=array_keys($data);
            $EndColumn = array_pop($keys);
            array_walk($data,function($ColumnData,$ColumnName) use (&$Query,$EndColumn) {
                $Query .= "`".$ColumnName."` ";
                $Query .= $ColumnData["type"]->SqlType;
                $Query .= "(".$ColumnData["size"].")";
                $Query .= ($EndColumn == $ColumnName) ? "":",";
            });
            $Query .= " )";
            return $Query;
        }
    }
}