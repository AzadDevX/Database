<?php

namespace Azad\Database\Table\Columns;

class Get extends \Azad\Database\Database {
    protected $TableName;
    protected static $query=[];
    protected static $WhereQuery;

    protected function Get($table_name=null) {
        $TableName = (isset($table_name)) ? $table_name : $this->TableName;
        $Rows = $this->Fetch($this->Query(self::$query[$TableName]));
        $TableName = (string) $this->TableName;
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                if ($value == null) {
                    continue;
                }
                if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
                    $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
                    $EncrypetName = parent::$name_prj."\\Encrypters\\".$EncrypetName;
                    if (!class_exists($EncrypetName)) {
                        throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                    }
                    $value = $EncrypetName::Decrypt($value);
                }
                if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Get")) {
                    $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
                    $value = $DB->Get($value);
                }
            }
        }
        parent::$TableData['table_data'] = $Rows;
        return $Rows;
    }

}