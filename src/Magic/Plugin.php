<?php

namespace Azad\Database\Magic;

abstract class Plugin extends \Azad\Database\Database {
    protected $Data;
    final public function __construct($Data) {
        $this->Data = $Data;
    }
    final protected static function Table($table_name) {
        return new \Azad\Database\Table\Init($table_name,parent::$MyHash);
    }
    final public function NewJob () {
        $id = sha1(rand(1,9999) + rand(1,9999) + rand(1,9999));
        return new \Azad\Database\Jobs\Init($id,parent::$MyHash,$this);
    }
    final protected function IncludePlugin ($name,$data) {
        $class = parent::$name_prj[parent::$MyHash]."\\Plugins\\".$name;
        if (!class_exists($class)) {
            if (parent::$SystemConfig[parent::$MyHash]["Debug"]) {
                throw new \Azad\Database\Exceptions\Debug(__METHOD__,['directory'=>parent::$dir_prj[parent::$MyHash],'project_name'=>parent::$name_prj[parent::$MyHash]],$name);
            }
            throw new \Azad\Database\Exceptions\Load("Plugin does not exist",\Azad\Database\Exceptions\LoadCode::Plugin->value,$class);
        }
        return new $class($data);
    }
}
