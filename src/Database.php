<?php

namespace Azad\Database;

class Database {
    protected static $DataBase;
    protected static $Config;
    protected static $TableData=[];
    protected static $Query;
    protected static $TablePrefix;
    protected static $ProjectName;
    protected static $is_have_prefix;
    protected static $InsertData;
    protected static $db_name;
    protected static $dir_prj;
    protected static $name_prj;
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
}