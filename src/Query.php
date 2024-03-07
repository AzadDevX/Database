<?php

namespace Azad\Database;
class Query {

    private static function DataTypeTable ($ColumnName,$ColumnData) {
        $Query = "`".$ColumnName."` ";
        $SqlType = $ColumnData["type"]->SqlType;
        if ($SqlType == "ID") {
            $Query .= "BIGINT";
            $Query .= "(".$ColumnData["size"].")";
        } else {
            $Query .= $ColumnData["type"]->SqlType;
            if (isset($ColumnData["size"])) {
                $Query .= "(".$ColumnData["size"].")";
            }
        }
        if (method_exists($ColumnData["type"],"AddToQueryTable")) {
            $Query .= " ".$ColumnData["type"]->AddToQueryTable();
        }
        return $Query;
    }
    public static function MakeTable($table_name,$data,$class_name) {
        $Query = "CREATE TABLE IF NOT EXISTS ";
        $Query .= $table_name;
        $Query .= " (";
        $keys=array_keys($data['data']);
        $EndColumn = array_pop($keys);
        array_walk($data['data'],function($ColumnData,$ColumnName) use (&$Query,$EndColumn,&$class_name) {
            if ($ColumnData['type']->Primary == true) {
                $class_name->PRIMARY_KEY = $ColumnName;
            }
            $Query .= self::DataTypeTable ($ColumnName,$ColumnData);
            $Query .= ($EndColumn == $ColumnName) ? "":",";
        });
        if (isset($class_name->PRIMARY_KEY)) {
            $Query .= ", PRIMARY KEY (".$class_name->PRIMARY_KEY.")";
        }
        if (is_array($class_name->Unique) and count($class_name->Unique) > 0) {
            $Query .= ", UNIQUE (".implode(",",$class_name->Unique).")";
        }
        $Query .= " )";
        return $Query;
    }

    public static function SelectQuery($column_name,$table_name) {
        $Query = "SELECT ";
        if ($column_name != "*") {
            $keys=array_values($column_name);
            $EndColumn = array_pop($keys);
            array_map(function($ColumnName) use (&$Query,$EndColumn) {
                $Query .= $ColumnName." ";
                $Query .= ($EndColumn == $ColumnName) ? "":",";
            },$column_name);
        } else {
            $Query .= "* ";
        }
        $Query .= "FROM `".$table_name."`";
        return $Query;
    }

    public static function MakeWhere($key,$value,$Conditions="=") {
        return $key." $Conditions '".$value."'";
    }
    
    public static function UpdateQuery($table_name,$value,$key,$where) {
        $Query = "UPDATE ";
        $Query .= $table_name;
        $Query .= " SET ";
        $Query .= $key."='".$value."'";
        $Query .= " WHERE ";
        $keys=array_keys($where);
        $EndColumn = array_pop($keys);
        array_walk($where,function($value,$key) use (&$Query,$EndColumn) {
            $Query .= $key." = '".$value."'";
            $Query .= ($EndColumn == $key) ? "":" AND ";
        });
        return $Query;
    }
    public static function Insert ($table_name,$data) {
        $query = "INSERT INTO ".$table_name." ";
        $query .= "(".implode(",",$data['columns']["key"]).")";
        $query .= " VALUES ";
        $query .= "(".implode(",",$data['columns']["value"]).")";
        return $query;
    }
}