<?php

namespace Azad\Database;

class Connect extends Database {
    public function __construct() { }
    public function Config ($class) {
        if (!class_exists($class)) {
            throw new Exception\Load("Config [$class] does not exist");
        }
        $ConfigData = new $class();
        $host = ($ConfigData->Database['port'] != '')?$ConfigData->Database['host'].":".$ConfigData->Database['port']:$ConfigData->Database['host'];
        $username = $ConfigData->Database["username"];
        $password = $ConfigData->Database["password"];
        $db_name = $ConfigData->Database["name"];
        parent::$DataBase = new Mysql($host, $username, $password, $db_name);
        parent::$dir_prj = $ConfigData->Project["directory"];
        parent::$name_prj = $ConfigData->Project["name"];
        parent::$TablePrefix = $ConfigData->Table['prefix'];
        parent::$is_have_prefix = parent::$TablePrefix != '';
        $this->LoadPlugins ();
        $this->LoadRebuilders ();
        $this->LoadEncrypters ();
        $this->LoadTables ();
    }
    public function Table($table_name) {
        return new Table\Init(parent::$is_have_prefix?parent::$TablePrefix."_".$table_name:$table_name);
    }
    private function MakeDir($address) {
        return !file_exists($address)?mkdir($address):false;
    }
    private function MakeFolders() {
        $dir = parent::$dir_prj;
        $this->MakeDir($dir);
        $this->MakeDir($dir."/Tables");
        $this->MakeDir($dir."/Constants");
        $this->MakeDir($dir."/Encrypters");
        $this->MakeDir($dir."/Rebuilders");
        $this->MakeDir($dir."/Plugins");
        $this->MakeDir($dir."/Exceptions");
    }
    private function LoadTables () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj."/Tables/*.php"));
        array_map(fn($x) => $this->Query($x['query']),\Azad\Database\Table\MakeINIT::MakeTables());
    }
    private function LoadPlugins () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj."/Plugins/*.php"));
    }
    private function LoadRebuilders () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj."/Rebuilders/*.php"));
    }
    private function LoadEncrypters () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj."/Encrypters/*.php"));
    }
    public function LoadPlugin ($class,$data) {
        $class = parent::$name_prj."\\Plugins\\".$class;
        if (!class_exists($class)) {
            throw new Exception\Load("Plugin [$class] does not exist");
        }
        return new $class($data);
    }
    protected function RebuilderResult ($RebuilderName,$Data) {
        $RebuilderName = parent::$name_prj."\\Rebuilders\\".$RebuilderName;
        if (!class_exists($RebuilderName)) {
            throw new Exception\Load("Rebuilder [$RebuilderName] does not exist");
        }
        return $RebuilderName::Rebuild ($Data);
    }
}
