<?php

namespace Azad;

class Sql {
    protected static $DataBase;
    protected static $TableData=[];

    public function __construct($host, $username, $password, $database) {
        self::$DataBase = new Database($host, $username, $password, $database);
        $this->MakeFolders();
        $this->LoadTables();
        
    }
    public function Table($table_name) {
        return new Database\Table($table_name);
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
        array_map(fn($x) => $this->Query($x['query']),\Azad\Database\MakeTableData::MakeTables());
    }
    protected function Query($command) {
        return self::$DataBase->QueryRun($command);
    }
    protected function Fetch($queryResult) {
        return self::$DataBase->FetchQuery($queryResult);
    }
}
