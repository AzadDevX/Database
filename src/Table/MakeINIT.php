<?php
namespace Azad\Database\Table;
class MakeINIT extends \Azad\Database\Connect {
    protected static $SubClass=[];
    public static function MakeTables () {
        self::$SubClass = array_values(array_filter(get_declared_classes(),fn($class_name) => is_subclass_of($class_name,"Azad\Database\Table\Make")));
        array_map(function ($class_name) {
            new $class_name();
            $Query = \Azad\Database\Query::MakeTable($class_name,parent::$TableData[$class_name]);
            parent::$TableData[$class_name]['query'] = $Query;
        } ,self::$SubClass);
        return parent::$TableData;
    }
    public static function GetSetting ($class) {
        return get_class_vars($class);
    }
}