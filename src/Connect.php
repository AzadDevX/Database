<?php

namespace Azad\Database;

/*

include 'plugin/main.php';

$newNamespaces = get_current_namespaces(); 
*/

/*
$ini = 
*/

class Connect {
    protected static $DataBase,$TablePrefix, $ProjectName, $Query, $TableData=[],$is_have_prefix;
    private $config,$ProjectDir,$MagickList,$db_name;

    public function __construct($db_name) {
        $this->ProjectDir = $db_name;
        self::$ProjectName = $db_name;
        $this->db_name = $db_name;
        $this->MakeFolders ();
        $this->MakeConfig ();
        $this->config = $this->Config ();
        $this->ProjectDir = $this->config['Project']['name'];
        self::$ProjectName = $this->config['Project']['name'];
        if (!filter_var($this->config['Database']['host'],FILTER_VALIDATE_IP)) {
            throw new Exception\DataType("The value entered for the host must be an IP");
        }
        $host = ($this->config['Database']['port'] != '')?$this->config['Database']['host'].":".$this->config['Database']['port']:$this->config['Database']['host'];
        $username = $this->config['Database']['username'];
        $password = $this->config['Database']['password'];
        self::$DataBase = new Mysql($host, $username, $password, $db_name);
        self::$TablePrefix = $this->config['Table']['prefix'];
        self::$is_have_prefix = self::$TablePrefix != '';
        $this->LoadPlugins ();
        $this->LoadRebuilders ();
        $this->LoadEncrypters ();
        $this->LoadTables ();
    }
    private function WriteINI($config, $section, $file) {
        $content = "[$section] \n";
        foreach ($config as $key => $value) {
            $content .= "$key = $value \n";
        }
        file_put_contents($file, $content, FILE_APPEND) !== false;
        chmod($file,0600);
    }
    private function MakeConfig () {
        $filename = $this->ProjectDir."/.ASql.ini";
        if (!file_exists($filename)) {
            $this->WriteINI(["host" => '127.0.0.1',"port" => '',"username" => 'root',"password" => ''],"Database",$filename);
            $this->WriteINI(["prefix" => ''],"Table",$filename);
            $this->WriteINI(["name" => $this->db_name],"Project",$filename);
        }
    }
    private function Config () {
        return parse_ini_file($this->ProjectDir.'/.ASql.ini',true);
    }
    public function Table($table_name) {
        return new Table\Init(self::$is_have_prefix?self::$TablePrefix."_".$table_name:$table_name);
    }
    private function MakeDir($address) {
        return !file_exists($address)?mkdir($address):false;
    }
    private function MakeFolders() {
        $this->MakeDir($this->ProjectDir);
        $this->MakeDir($this->ProjectDir."/Tables");
        $this->MakeDir($this->ProjectDir."/Constants");
        $this->MakeDir($this->ProjectDir."/Encrypters");
        $this->MakeDir($this->ProjectDir."/Rebuilders");
        $this->MakeDir($this->ProjectDir."/Plugins");
        $this->MakeDir($this->ProjectDir."/Exceptions");
    }
    private function LoadTables () {
        array_map(fn($filename) => include_once($filename),glob($this->ProjectDir."\Tables\*.php"));
        array_map(fn($x) => $this->Query($x['query']),\Azad\Database\Table\MakeINIT::MakeTables(self::$TablePrefix));
    }
    private function LoadPlugins () {
        array_map(fn($filename) => include_once($filename),glob($this->ProjectDir."\Plugins\*.php"));
    }
    private function LoadRebuilders () {
        array_map(fn($filename) => include_once($filename),glob($this->ProjectDir."\Rebuilders\*.php"));
    }
    private function LoadEncrypters () {
        array_map(fn($filename) => include_once($filename),glob($this->ProjectDir."\Encrypters\*.php"));
    }
    protected function Query($command) {
        try {
            return self::$DataBase->QueryRun($command);
        } catch (\mysqli_sql_exception $E) {
            throw new Exception\SQLQuery($E->getMessage());
        }
    }
    protected function Fetch($queryResult) {
        return self::$DataBase->FetchQuery($queryResult);
    }
    public function LoadPlugin ($class,$data) {
        $class = self::$ProjectName."\\Plugins\\".$class;
        if (!class_exists($class)) {
            throw new Exception\Load("Plugin [$class] does not exist");
        }
        return new $class($this,$data);
    }
    protected function RebuilderResult ($RebuilderName,$Data) {
        $RebuilderName = self::$ProjectName."\\Rebuilders\\".$RebuilderName;
        if (!class_exists($RebuilderName)) {
            throw new Exception\Load("Rebuilder [$RebuilderName] does not exist");
        }
        return $RebuilderName::Rebuild ($Data);
    }
}