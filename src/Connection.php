<?php

namespace Azad\Database;

class Connection extends Database {

    public $Version = "v2.3.4";
    private $ProjectStartAt;
    private $MemoryUsage;
    public $HashID;

    public function __construct($class) {

        $this->HashID = sha1(rand(1,99999) + rand(1,99999) + rand(1,999999));
        parent::$MyHash = $this->HashID;

        $this->ProjectStartAt = microtime(1);
        $this->MemoryUsage = memory_get_usage();

        if (!class_exists($class)) {
            throw new Exceptions\Load("Config does not exist",Exceptions\LoadCode::Config->value,$class);
        }
        $ConfigData = new $class();

        parent::$Log[$this->HashID] = $ConfigData->Log;

        parent::$dir_prj[$this->HashID] = $ConfigData->Project["directory"];

        $this->MakeFolders(parent::$dir_prj[$this->HashID]);

        if (parent::$Log[$this->HashID]['retain_previous_data'] == false) { unlink(parent::$dir_prj[$this->HashID]."/".parent::$Log[$this->HashID]['file_name']); }
        parent::Log("-- The project is in progress --");

        parent::$name_prj[$this->HashID] = $ConfigData->Project["name"];
        parent::$TablePrefix[$this->HashID] = $ConfigData->Table['prefix'];
        parent::$is_have_prefix[$this->HashID] = parent::$TablePrefix != '';
        parent::$SystemConfig[$this->HashID] = $ConfigData->System;

        $ConnectionObj = $ConfigData->System['Database'];
        $ConnectionObj = "\\Azad\\Database\\Databases\\".$ConnectionObj."\\Database";
        $ConnectionObj = new $ConnectionObj();
        $ConnectionObj = $ConnectionObj->Connect($ConfigData->Database);
        parent::$DataBase[$this->HashID] = $ConnectionObj;

        parent::Log("System Config Ram: ".parent::$SystemConfig[$this->HashID]["RAM"]);
        $this->LoadEnums ();
        $this->LoadPlugins ();
        $this->LoadNormalizers ();
        $this->LoadEncrypters ();
        $this->LoadTables ();

    }


    public function Table($table_name) {
        parent::$MyHash = $this->HashID;
        return new Table\Init($table_name,$this->HashID);
    }
    public function NewJob () {
        $id = sha1(rand(1,9999) + rand(1,9999) + rand(1,9999));
        return new Jobs\Init($id,$this->HashID,$this);
    }

    private function MakeDir($address) {
        return !file_exists($address)?mkdir($address):false;
    }
    private function MakeFolders($dir) {
        $this->MakeDir($dir);
        $this->MakeDir($dir."/Tables");
        $this->MakeDir($dir."/Enums");
        $this->MakeDir($dir."/Encrypters");
        $this->MakeDir($dir."/Normalizers");
        $this->MakeDir($dir."/Plugins");
    }
    private function LoadTables () { // Tables maked here
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj[$this->HashID]."/Tables/*.php"));
        $TableList = \Azad\Database\Sort::TableForeign(\Azad\Database\Table\MakeINIT::MakeTables($this->HashID));
        array_map(fn($x) => $this->Query($x['query']),$TableList);
    }
    private function LoadPlugins () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj[$this->HashID]."/Plugins/*.php"));
    }
    private function LoadNormalizers () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj[$this->HashID]."/Normalizers/*.php"));
    }
    private function LoadEncrypters () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj[$this->HashID]."/Encrypters/*.php"));
    }
    private function LoadEnums () {
        array_map(fn($filename) => include_once($filename),glob(parent::$dir_prj[$this->HashID]."/Enums/*.php"));
    }
    public function LoadPlugin ($name,$data) {
        $class = parent::$name_prj[$this->HashID]."\\Plugins\\".$name;
        if (!class_exists($class)) {
            if (parent::$SystemConfig[$this->HashID]["Debug"]) {
                throw new Exceptions\Debug(__METHOD__,['directory'=>parent::$dir_prj[$this->HashID],'project_name'=>parent::$name_prj[$this->HashID]],$name);
            }
            throw new Exceptions\Load("Plugin does not exist",Exceptions\LoadCode::Plugin->value,$class);
        }
        return new $class($data);
    }

    public function CloseLog () {
        $RunTime = microtime(1) - $this->ProjectStartAt;
        $MemoryUsage = memory_get_usage() - $this->MemoryUsage;
        parent::Log("##############################");
        parent::Log("The project has been completed");
        parent::Log("statistics of the project:");
        parent::Log("RunTime: ".$RunTime."ms");
        parent::Log("ProjectName: ".parent::$name_prj[$this->HashID]);
        parent::Log("Number of queries runned: ".parent::$CountQuery);
        parent::Log("Number of Input ram: ".parent::$CountRamInput);
        parent::Log("Number of Output ram: ".parent::$CountRamOutPut);
        parent::Log("Memory Usage (Byte): ".$MemoryUsage);
        if (in_array("database",self::$Log[self::$MyHash]['save'])) {
            parent::Log("################## Database Data");
            parent::Log(json_encode(parent::$Tables[$this->HashID],128|256));
        };
    }
}
