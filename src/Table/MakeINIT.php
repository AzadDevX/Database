<?php
namespace Azad\Database\Table;
class MakeINIT extends \Azad\Database\Database {
    protected static $SubClass=[];
    public static function MakeTables ($HashID) {
        self::$SubClass = array_values(array_filter(get_declared_classes(),fn($class_name) => is_subclass_of($class_name,"Azad\Database\Table\Make") && strpos( $class_name, parent::$name_prj[$HashID]."\Tables" ) !== false));
        array_map(function ($class_name) use ($HashID) {
            $class_name = new $class_name();
            if (parent::$is_have_prefix[$HashID]) { $class_name->Prefix = parent::$TablePrefix[$HashID]; }
            $Query = parent::MakeQuery();
            $Query = $Query::MakeTable($class_name);
            preg_match("#(.*)\\\\Tables\\\\(.*)#",get_class($class_name),$table_name);
            $table_name = $table_name[2];
            $table_name = parent::$is_have_prefix[$HashID]?parent::$TablePrefix[$HashID]."_".$table_name:$table_name;
            parent::$Tables[$HashID][$table_name]['query'] = $Query;
            parent::$Tables[$HashID][$table_name]['foreign_from'] = $class_name->ForeignFrom;
            parent::$Tables[$HashID][$table_name]['columns'] = $class_name->Columns;
            parent::$Tables[$HashID][$table_name]['short_types'] = $class_name->ShortKeyType;
            parent::$Tables[$HashID][$table_name]['primary_key'] = $class_name->PRIMARY_KEY;
        } ,self::$SubClass);
        return parent::$Tables[$HashID] ?? false;
    }
    public static function GetSetting ($class) {
        return get_class_vars($class);
    }
}
