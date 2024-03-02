<?php

namespace Azad {
    class Query {
        public static function MakeTable($table_name,$data) {
            $GetPrp = new $table_name();

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
            if (isset($GetPrp->PRIMARY_KEY)) {
                $Query .= ", PRIMARY KEY (".$GetPrp->PRIMARY_KEY.")";
            }
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

        public static function MakeWhere($key,$value,$Conditions="=") {
            return $key." $Conditions '".$value."'";
        }
        
        public static function UpdateQuery($table_data,$value,$key) {
            $Query = "UPDATE ";
            $Query .= $table_data['table_name'];
            $Query .= " SET ";
            $Query .= $key."='".$value."'";
            $Query .= " WHERE ";
            $keys=array_keys($table_data["table_data"][0]);
            $EndColumn = array_pop($keys);
            array_walk($table_data["table_data"][0],function($value,$key) use (&$Query,$EndColumn) {
                $Query .= $key." = ".$value;
                $Query .= ($EndColumn == $key) ? "":" AND ";
            });
            return $Query;
        }
        public static function Insert ($table_name,$data) {
            $query = "INSERT INTO ".$table_name." ";
            $query .= "(".implode(",",$data["key"]).")";
            $query .= " VALUES ";
            $query .= "(".implode(",",$data["value"]).")";
            return $query;
        }
    }
}