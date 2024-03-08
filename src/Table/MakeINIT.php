<?php
namespace Azad\Database\Table;
class MakeINIT extends \Azad\Database\Database {
    protected static $SubClass=[];
    public static function MakeTables () {
        self::$SubClass = array_values(array_filter(get_declared_classes(),fn($class_name) => is_subclass_of($class_name,"Azad\Database\Table\Make") && strpos( $class_name, parent::$name_prj."\Tables" ) !== false));
        array_map(function ($class_name) {
            $table_name = str_replace(parent::$name_prj."\\Tables\\",'',$class_name);
            $table_name = parent::$is_have_prefix?self::$TablePrefix."_".$table_name:$table_name;
            $class_name = new $class_name();
            $Query = \Azad\Database\Query::MakeTable($table_name,parent::$TableData[$table_name],$class_name);
            parent::$TableData[$table_name]['query'] = $Query;
        } ,self::$SubClass);
        return parent::$TableData;
    }
    public static function GetSetting ($class) {
        return get_class_vars($class);
    }
}