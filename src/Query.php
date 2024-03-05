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
    public static function MakeTable($table_name,$data,$prefix=null) {
        $GetPrp = new $table_name();
        $Query = "CREATE TABLE IF NOT EXISTS ";
        $table_name = isset($prefix)?$prefix."_".$table_name:$table_name;
        $Query .= $table_name;
        $Query .= " (";
        $keys=array_keys($data['data']);
        $EndColumn = array_pop($keys);
        array_walk($data['data'],function($ColumnData,$ColumnName) use (&$Query,$EndColumn,&$GetPrp) {
            if ($ColumnData['type']->Primary == true) {
                $GetPrp->PRIMARY_KEY = $ColumnName;
            }
            $Query .= self::DataTypeTable ($ColumnName,$ColumnData);
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
    
    public static function UpdateQuery($table_data,$value,$key,$where) {
        $Query = "UPDATE ";
        $Query .= $table_data['table_name'];
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
        $query .= "(".implode(",",$data["key"]).")";
        $query .= " VALUES ";
        $query .= "(".implode(",",$data["value"]).")";
        return $query;
    }
}