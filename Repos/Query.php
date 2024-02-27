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

        public static function SelectQuery($data) {
            $Query = "SELECT ";
            if ($data["column_name"] != "*") {
                $keys=array_values($data["column_name"]);
                $EndColumn = array_pop($keys);
                array_map(function($ColumnName) use (&$Query,$EndColumn) {
                    $Query .= $ColumnName." ";
                    $Query .= ($EndColumn == $ColumnName) ? "":",";
                },$data["column_name"]);
            } else {
                $Query .= "* ";
            }
            $Query .= "FROM `".$data["table_name"]."`";
            return $Query;
        }
    }
}