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
                    $EncryptName = $ColumnData['encrypter'];
                    $EncryptClass = self::$name_prj[self::$MyHash]."\\Encrypters\\".$EncryptName;
                    if (!class_exists($EncryptClass)) {
                        if (self::$SystemConfig[self::$MyHash]["Debug"]) {
                            throw new Exceptions\Debug(__METHOD__,['directory'=>self::$dir_prj[self::$MyHash],'project_name'=>self::$name_prj[self::$MyHash]],$EncryptName);
                        }
                        throw new Exceptions\Load("Encrypter does not exist",Exceptions\LoadCode::Encrypeter->value,$EncryptName);
                    }
                    $value = $EncryptClass::Decrypt($value);
                }
                if (isset($ColumnData['enum'])) {
                    $value = Enums::ValueToEnum($TableName,$key,$value);
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


    protected static function PreparationValues ($key,$value,$table_name) { #Disable Soon
        $TableName = $table_name;
        $ColumnData = self::$Tables[self::$MyHash][$TableName]['columns'][$key] ?? false;
        if ($ColumnData == false) {
            throw new Exceptions\Row("Column ".$key." is not correctly defined (table: ".$table_name.")");
        }

        # ----- Check Enum
        if(isset($ColumnData['enum'])) {
            $value = Enums::EnumToValue($table_name,$key,$value);
        }

        # ---- is valid method (in type)
        if(isset($ColumnData['type']) && method_exists($ColumnData['type'],"is_valid")) {
            $DB = new $ColumnData['type']();
            if(!$DB->is_valid($value)) {
                throw new Exceptions\DataType("The entered value is not acceptable for type class.");
            }
        }
        # ---- Set method (in type)
        if(isset($ColumnData['type']) && method_exists($ColumnData['type'],"Set")) {
            $DB = new $ColumnData['type']();
            $value = $DB->Set($value);
        }
        # ---- Normalizer
        if (isset($ColumnData['Normalizer'])) {
            if (!is_array($value)) {
                $value = self::NormalizerResult($ColumnData['Normalizer'],$value);
            } else {
                $Normalizer = $ColumnData['Normalizer'];
                $value = \Azad\Database\built_in\Arrays::Value($value,function ($data) use ($Normalizer) {
                    return self::NormalizerResult($Normalizer,$data);
                });
            }
        }
        # ---- Encrypter
        if (isset($ColumnData['encrypter'])) {
            $EncryptName = $ColumnData['encrypter'];
            $EncryptClass = self::$name_prj[self::$MyHash]."\\Encrypters\\".$EncryptName;
            if (!class_exists($EncryptClass)) {
                if (self::$SystemConfig[self::$MyHash]["Debug"]) {
                    throw new Exceptions\Debug(__METHOD__,['directory'=>self::$dir_prj[self::$MyHash],'project_name'=>self::$name_prj[self::$MyHash]],$EncryptName);
                }
                throw new Exceptions\Load("Encrypter does not exist",Exceptions\LoadCode::Encrypeter->value,$EncryptName);
            }
            $value = $EncryptClass::Encrypt($value);
        }
        # ---- Escape String
        return self::$DataBase[self::$MyHash]->EscapeString ($value);
    }
    protected static function ArraytoObject (array $array) : object {
        return json_decode(json_encode($array));
    }

    protected static function NormalizerResult($Normalizer,$data) {
        $NormalizerClass = self::$name_prj[self::$MyHash]."\\Normalizers\\".$Normalizer;
        if (!class_exists($NormalizerClass)) {
            if (self::$SystemConfig[self::$MyHash]["Debug"]) {
                throw new Exceptions\Debug(__METHOD__,['directory'=>self::$dir_prj[self::$MyHash],'project_name'=>self::$name_prj[self::$MyHash]],$Normalizer);
            }
            throw new Exceptions\Load("Normalizer does not exist",Exceptions\LoadCode::Normalizer->value,$Normalizer);
        }
        return $NormalizerClass::Normalization ($data);
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
            if (!isset($item[$key])) {
                return false;
            }
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
            if (!$unique) { return false; }
            $new_data = array_values($unique);
            self::$Tables[self::$MyHash][$table_name]['where_by_primary'] = array_column($new_data,$primary_key);
            self::$Tables[self::$MyHash][$table_name]['list'] = $new_data;
            self::$CountRamInput += 1;
        }
        if (isset(self::$SystemConfig[self::$MyHash]["Cache"]) && file_exists(self::$SystemConfig[self::$MyHash]["Cache"])) {
            $data = serialize(self::$Tables[self::$MyHash]);
            file_put_contents(self::$SystemConfig[self::$MyHash]["Cache"],$data);
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