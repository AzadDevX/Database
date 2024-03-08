<?php

namespace Azad\Database\Magic;

abstract class Plugin extends \Azad\Database\Database {
    protected $Data;
    final public function __construct($Data) {
        $this->Data = $Data;
    }
    final protected static function Table($table_name) {
        return new \Azad\Database\Table\Init(self::$is_have_prefix?self::$TablePrefix."_".$table_name:$table_name);
    }
    final protected function IncludePlugin ($PluginName,$Data) {
        $PluginName = parent::$name_prj."\\Plugins\\".$PluginName;
        if (!class_exists($PluginName)) {
            throw new \Azad\Database\Exception\Load("Plugin [$PluginName] does not exist");
        }
        return new $PluginName($Data);
    }
}
