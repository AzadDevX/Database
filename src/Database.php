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
    protected static $UpdateData;
    protected static $db_name;
    protected static $dir_prj;
    protected static $name_prj;
    protected static $IDListTable;
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
    protected static function PreparingGet($Rows,$TableName) { #Soon fixed
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                if ($value == null) { continue; }
                    if (isset(self::$TableData[$TableName]['data'][$key]['encrypter'])) {
                        $EncrypetName = self::$TableData[$TableName]['data'][$key]['encrypter'];
                        $EncrypetName = self::$name_prj."\\Encrypters\\".$EncrypetName;
                        if (!class_exists($EncrypetName)) {
                            throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                        }
                        $value = $EncrypetName::Decrypt($value);
                    }
            }
            if(method_exists(new self::$TableData[$TableName]['data'][$key]['type'],"Get")) {
                $DB = new self::$TableData[$TableName]['data'][$key]['type']();
                $value = $DB->Get($value);
            }
        }
        return $Rows;
    }
    protected static function ArraytoObject (array $array) : object {
        return json_decode(json_encode($array));
    }
}