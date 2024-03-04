<?php

namespace Azad\Database;

class Connect {
    protected static $DataBase;
    protected static $TableData=[];
    protected static $Query;

    public function __construct($host, $username, $password, $database) {
        self::$DataBase = new Mysql($host, $username, $password, $database);
        $this->MakeFolders ();
        $this->LoadTables ();
        $this->LoadPlugins ();
        $this->LoadRebuilders ();
        $this->LoadEncrypters ();
    }
    public function Table($table_name) {
        return new Table\Init($table_name);
    }
    private function MakeDir($address) {
        return !file_exists($address)?mkdir($address):false;
    }
    private function MakeFolders() {
        $this->MakeDir("Sql");
        $this->MakeDir("Sql/Tables");
        $this->MakeDir("Sql/Constants");
        $this->MakeDir("Sql/Encrypters");
        $this->MakeDir("Sql/Rebuilders");
        $this->MakeDir("Sql/Plugins");
        $this->MakeDir("Sql/Exceptions");
    }
    private function LoadTables () {
        array_map(fn($filename) => include_once($filename),glob("Sql\Tables\*.php"));
        array_map(fn($x) => $this->Query($x['query']),\Azad\Database\Table\MakeINIT::MakeTables());
    }
    private function LoadPlugins () {
        array_map(fn($filename) => include_once($filename),glob("Sql\Plugins\*.php"));
    }
    private function LoadRebuilders () {
        array_map(fn($filename) => include_once($filename),glob("Sql\Rebuilders\*.php"));
    }
    private function LoadEncrypters () {
        array_map(fn($filename) => include_once($filename),glob("Sql\Encrypters\*.php"));
    }
    protected function Query($command) {
        try {
            return self::$DataBase->QueryRun($command);
        } catch (\mysqli_sql_exception $E) {
            throw new Exception($E->getMessage());
        }
    }
    protected function Fetch($queryResult) {
        return self::$DataBase->FetchQuery($queryResult);
    }
    public function LoadPlugin ($class,$data) {
        return new $class($this,$data);
    }
    protected function RebuilderResult ($RebuilderName,$Data) {
        return $RebuilderName::Rebuild ($Data);
    }
}
