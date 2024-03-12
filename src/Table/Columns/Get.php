<?php

namespace Azad\Database\Table\Columns;

class Get extends \Azad\Database\Database {
    protected $TableName;
    protected static $query=[];
    protected static $WhereQuery;
    protected static $EncrypterStatus=[];

    protected function Get($table_name=null) {
        $TableName = (isset($table_name)) ? $table_name : $this->TableName;
        $Rows = $this->Fetch($this->Query(self::$query[$TableName]));
        $TableName = (string) $this->TableName;
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                if ($value == null and $value != []) { continue; }
                if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
                    if (!isset(self::$EncrypterStatus[$key]['status']) or self::$EncrypterStatus[$key]['status'] != "decrypted") {
                        $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
                        $EncrypetName = parent::$name_prj."\\Encrypters\\".$EncrypetName;
                        if (!class_exists($EncrypetName)) {
                            throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                        }
                        self::$EncrypterStatus[$key] = ['value'=>$value,'status'=>"decrypting"];
                        $value = $EncrypetName::Decrypt($value);
                        self::$EncrypterStatus[$key] = ['value'=>$value,'status'=>"decrypted"];
                    }
                }
                if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Get")) {
                    $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
                    $value = $DB->Get($value);
                }
            }
        }
        if (isset(parent::$IDListTable[$TableName])) {
            parent::$IDListTable[$TableName] = end($Rows) ?? [];
        }
        parent::$TableData['table_data'] = $Rows;
        return $Rows;
    }

}