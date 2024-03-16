<?php

namespace Azad\Database\Databases\Mysql;

class Query extends \Azad\Database\Databases\Query {
    public static function MakeTable($table_obj) {
        # Data Variable
        preg_match("#(.*)\\\\Tables\\\\(.*)#",get_class($table_obj),$table_name);
        $table_name = $table_name[2];
        $prefix = $table_obj->Prefix ?? null;
        $table_name = isset($prefix)?$prefix."_".$table_name:$table_name;

        $Foreign_Query = null;
        $Query = "CREATE TABLE IF NOT EXISTS ".$table_name." (";
        $Foreign_Query = null;
        $primary_key = null;
        array_walk($table_obj->Columns,function($ColumnData,$ColumnName) use (&$Query,&$primary_key,&$Foreign_Query) {
            if ($ColumnData['type']->Primary == true) { $primary_key = $ColumnName; }
            if (isset($ColumnData["foreign"])) { $Foreign_Query = ", FOREIGN KEY (".$ColumnName.") REFERENCES ".$ColumnData["foreign"]["table"]."(".$ColumnData["foreign"]["column"].")"; }
            $Query .= self::DataTypeTable ($ColumnName,$ColumnData).", ";
        });
        $Query = rtrim($Query,", ");
        if (isset($primary_key)) {
            $table_obj->PRIMARY_KEY = $primary_key;
            $Query .= ", PRIMARY KEY (".$primary_key.")";
        }
        if (is_array($table_obj->Unique) and count($table_obj->Unique) > 0) { $Query .= ", UNIQUE (".implode(",",$table_obj->Unique).")"; }
        if ($Foreign_Query !== null) { $Query .= $Foreign_Query; }
        $Query .= " )";
        return $Query;
    }


    public static function Insert($data) {
        $table_name = $data['table']; $columns = $data['columns'];
        $Key = $columns['key']; $Value = $columns['value'];;
        $query = "INSERT INTO ".$table_name." ";
        $query .= "(".implode(",",$Key).")";
        $query .= " VALUES ";
        $query .= "(".implode(",",$Value).")";
        return $query;
    }
    
    public static function Find($data) {

    }
    public static function Edit($table_name,$new_data,$Who) {
        $Query = "UPDATE ".$table_name." SET ";
        array_walk($new_data,function($value,$key) use (&$Query) { $Query .= $key." = '".$value."', "; });
        $Query = rtrim($Query,", ")." WHERE ";
        array_walk($Who,function($value,$key) use (&$Query) { $Query .= $key." = '".$value."' AND "; });
        $Query = rtrim($Query," AND ");
        return $Query;
    }

    public static function Select($column,$table) {
        $Query = "SELECT ";
        if ($column != "*") {
            $keys=array_values($column);
            $EndColumn = array_pop($keys);
            array_map(function($ColumnName) use (&$Query,$EndColumn) {
                $Query .= $ColumnName." ";
                $Query .= ($EndColumn == $ColumnName) ? "":",";
            },$column);
        } else {
            $Query .= "* ";
        }
        $Query .= "FROM `".$table."`";
        return $Query;
    }

    public static function Where ($key,$value,$Conditions) {
        return $key." $Conditions '".$value."'";
    }


    /* Private Methods */
    private static function DataTypeTable ($ColumnName,$ColumnData) {
        $Query = "`".$ColumnName."` ";
        $Query .= $ColumnData["type"]->SqlType;
        if (isset($ColumnData["size"])) {
            $Query .= "(".$ColumnData["size"].")";
        }
        if (isset($ColumnData['default'])) {
            $DefaultValue = ($ColumnData['default'] == 'NULL')?'NULL':"'".$ColumnData['default']."'";
            $Query .= "DEFAULT ".$DefaultValue;
        }
        if (isset($ColumnData['not_null']) and $ColumnData['not_null'] == true) {
            $Query .= " NOT NULL ";
        }
        if (method_exists($ColumnData["type"],"AddToQueryTable")) {
            $Query .= " ".$ColumnData["type"]->AddToQueryTable();
        }
        return $Query;
    }

}