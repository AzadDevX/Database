<?php

namespace Azad\Database;

class Database {
    protected static $DataBase;
    protected static $TablePrefix;
    protected static $is_have_prefix;
    protected static $InsertData;
    protected static $UpdateData;
    protected static $dir_prj;
    protected static $name_prj;
    protected static $IDListTable;
    protected static $Log;
    protected static $CountQuery;
    protected static $CountRamOutPut;
    protected static $CountRamInput;
    protected static $SystemConfig;

    protected static $Tables;
    protected static $Jobs;
    protected static $MyHash;


    private static function DatabaseClass () {
        return '\\Azad\\Database\\Databases\\'.self::$SystemConfig[self::$MyHash]['Database'];
    }
    protected static function MakeQuery () {
        return self::DatabaseClass ().'\\Query';
    }
    protected function Query($command) {
        if (in_array("query",self::$Log[self::$MyHash]['save'])) { self::Log(self::DateLog ()." Database Query: ".$command); }
        self::$CountQuery += 1;
        $result = self::$DataBase[self::$MyHash]->Run($command);
        if (in_array("affected_rows",self::$Log[self::$MyHash]['save'])) { self::Log(self::DateLog ()." Database Row affected: ".self::$DataBase[self::$MyHash]->affected_rows); };
        return $result->Result;
    }
    protected function Fetch($queryResult) {
        return self::$DataBase[self::$MyHash]->Fetch($queryResult);
    }
    protected static function PreparingGet($Rows,$TableName) {
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                $ColumnData = self::$Tables[self::$MyHash][$TableName]['columns'][$key];
                if ($value == null or $value == []) { continue; }
                if (isset($ColumnData['encrypter'])) {
                    $EncrypetName = $ColumnData['encrypter'];
                    $EncrypetName = self::$name_prj."\\Encrypters\\".$EncrypetName;
                    if (!class_exists($EncrypetName)) {
                        throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                    }
                    $value = $EncrypetName::Decrypt($value);
                }
                if (isset($ColumnData['enum'])) {
                    $value = $ColumnData['enum']::tryFrom($value);
                }
                if(!isset($ColumnData['enum']) && method_exists(new $ColumnData['type'],"Get")) {
                    $DB = new $ColumnData['type']();
                    $value = $DB->Get($value);
                }
                $Rows[$Row][$key] = $value;
            }
            
        }
        return $Rows;
    }


    protected static function PreparationValues ($key,$value,$table_name) {
        $TableName = $table_name;
        $ColumnData = self::$Tables[self::$MyHash][$TableName]['columns'][$key] ?? false;
        if ($ColumnData == false) {
            throw new Exception\Columns("Column ".$key." is not correctly defined (table: ".$table_name.")");
        }

        # ----- Check Enum
        if(isset($ColumnData['enum'])) {
            if (!$value instanceof $ColumnData['enum']) {
                throw new Exception\DataType("The value you entered does not match the enum set for the column.");
            }
            $value = $value->value;
        }

        # ---- is valid method (in type)
        if(method_exists($ColumnData['type'],"is_valid")) {
            $DB = new $ColumnData['type']();
            if(!$DB->is_valid($value)) {
                throw new Exception\DataType("The entered value is not acceptable for type class.");
            }
        }
        # ---- Set method (in type)
        if(method_exists($ColumnData['type'],"Set")) {
            $DB = new $ColumnData['type']();
            $value = $DB->Set($value);
        }
        # ---- Normalizer
        if (isset($ColumnData['Normalizer'])) {
            if (!is_array($value)) {
                $value = self::NormalizerResult($ColumnData['Normalizer'],$value);
            } else {
                $Normalizer = $ColumnData['Normalizer'];
                $value = \Azad\Database\Arrays::Value($value,function ($data) use ($Normalizer) {
                    return self::NormalizerResult($Normalizer,$data);
                });
            }
        }
        # ---- Encrypter
        if (isset($ColumnData['encrypter'])) {
            $EncrypetName = $ColumnData['encrypter'];
            $EncrypetName = self::$name_prj[self::$MyHash]."\\Encrypters\\".$EncrypetName;
            if (!class_exists($EncrypetName)) {
                throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
            }
            $value = $EncrypetName::Encrypt($value);
        }
        # ---- Escape String
        return self::$DataBase[self::$MyHash]->EscapeString ($value);
    }
    protected static function ArraytoObject (array $array) : object {
        return json_decode(json_encode($array));
    }

    private static function NormalizerResult($Normalizer,$data) {
        $NormalizerName = self::$name_prj[self::$MyHash]."\\Normalizers\\".$Normalizer;
        if (!class_exists($NormalizerName)) {
            throw new \Azad\Database\Exception\Load("Normalizer [$NormalizerName] does not exist");
        }
        return $NormalizerName::Normalization ($data);
    }
    protected static function Log($data) {
        $address = self::$dir_prj[self::$MyHash]."/".self::$Log[self::$MyHash]['file_name'];
        $myfile = fopen($address, "a") or die("Unable to open file!");
        fwrite($myfile, "\n". $data);
        fclose($myfile);
    }
    protected static function DateLog () {
        return "[".date("Y-m-d H:i:s")." - ".microtime(1)."].";
    }

    private function Unique ($array,$key) {
        $uniqueUsers = [];
        foreach (array_reverse($array) as $item) {
            $userId = $item[$key];
            if (!isset($uniqueUsers[$userId])) {
                $uniqueUsers[$userId] = $item;
            }
        }
        return array_values($uniqueUsers);
    }
    protected function SaveToRam ($table_name,$data) {
        $primary_key = self::$Tables[self::$MyHash][$table_name]["primary_key"];
        $OldDataForLog = self::$Tables[self::$MyHash][$table_name]['list'] ?? [];
        if (isset($primary_key)) {
            $add = array_merge(self::$Tables[self::$MyHash][$table_name]['list'] ?? [], $data);
            $unique = $this->Unique ($add,$primary_key);
            $new_data = array_values($unique);
            self::$Tables[self::$MyHash][$table_name]['where_by_primary'] = array_column($new_data,$primary_key);
            self::$Tables[self::$MyHash][$table_name]['list'] = $new_data;
            self::$CountRamInput += 1;
        }
        if (in_array("save_ram",self::$Log[self::$MyHash]['save'])) { self::Log(self::DateLog ()." \n Set data for Ram: TableName: [".$table_name."] \n WHERE PRIMARYS: ".json_encode(self::$Tables[self::$MyHash][$table_name]['where_by_primary'],128|256)." \n New List Data: ".json_encode($new_data,128|256)." \n Old List Data: ".json_encode($OldDataForLog,128|256).""); };
    }
    protected function GetFromRam ($table_name,$id) {
        $primary_key = self::$Tables[self::$MyHash][$table_name]["primary_key"];
        if (isset($primary_key)) {
            if(isset(self::$Tables[self::$MyHash][$table_name]['where_by_primary'])) {
                if (isset(self::$Tables[self::$MyHash][$table_name]['list'])) {
                    $where = self::$Tables[self::$MyHash][$table_name]['where_by_primary'];
                    $where = array_search($id,$where);
                    if ($where == false) { return false; }
                    if(isset(self::$Tables[self::$MyHash][$table_name]['list'][$where])) {
                        self::$CountRamOutPut += 1;
                        return self::$Tables[self::$MyHash][$table_name]['list'][$where];
                    }
                }
            }
        }
    }

}