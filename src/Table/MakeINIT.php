<?php
namespace Azad\Database\Table;
class MakeINIT extends \Azad\Database\Connect {
    protected static $SubClass=[];
    public static function MakeTables ($prefix=null) {
        self::$SubClass = array_values(array_filter(get_declared_classes(),fn($class_name) => is_subclass_of($class_name,"Azad\Database\Table\Make")));
        array_map(function ($class_name) use ($prefix) {
            new $class_name();
            $table_name = isset(self::$TablePrefix)?self::$TablePrefix."_".$class_name:$class_name;
            $Query = \Azad\Database\Query::MakeTable($class_name,parent::$TableData[$table_name],$prefix);
            parent::$TableData[$table_name]['query'] = $Query;
        } ,self::$SubClass);
        return parent::$TableData;
    }
    public static function GetSetting ($class) {
        return get_class_vars($class);
    }
}