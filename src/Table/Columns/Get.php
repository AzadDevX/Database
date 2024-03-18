<?php

namespace Azad\Database\Table\Columns;

class Get extends \Azad\Database\Database {
    protected $TableName;
    protected static $query=[];
    protected static $WhereQuery;
    protected static $EncrypterStatus=[];

    private function FindPrimaryKeyWhereInQuery ($table_name,$query) {
        $primary_key = parent::$Tables[parent::$MyHash][$table_name]["primary_key"];
        preg_match_all("#".$primary_key." = '(.*)'#",$query,$data);
        return $data[1][0] ?? false;
    }

    protected function Get($table_name=null) {
        $TableName = (isset($table_name)) ? $table_name : $this->TableName;
        $TableName = (string) $this->TableName;
        if(parent::$SystemConfig[parent::$MyHash]['RAM'] == true) {
            $PrimaryKeyWhere = $this->FindPrimaryKeyWhereInQuery ($TableName,self::$query[$TableName]);
            if ($PrimaryKeyWhere) {
                $Data = $this->GetFromRam ($TableName,$PrimaryKeyWhere);
                if ($Data) {
                    if (in_array("get_ram",parent::$Log[parent::$MyHash]['save'])) { parent::Log(parent::DateLog ()." Get data from Ram: TableName: [".$TableName."] ".parent::$Tables[parent::$MyHash][$TableName]["primary_key"]." = ".$PrimaryKeyWhere); };
                    return [$Data];
                }
            }
        }

        $Rows = $this->Fetch($this->Query(self::$query[$TableName]));
        $Rows = parent::PreparingGet($Rows,$TableName);
        if (isset(parent::$IDListTable[$TableName])) {
            parent::$IDListTable[$TableName] = end($Rows) ?? [];
        }
        parent::$Tables[parent::$MyHash][$table_name]['data'] = $Rows;
        if(parent::$SystemConfig[parent::$MyHash]['RAM'] == true) {
            $this->SaveToRam ($TableName,$Rows);
        }
        return $Rows;
    }

    protected static function where_data($data,$TableName) {
        $new = [];
        foreach ($data as $key=>$value) {
            $ColumnData = self::$Tables[self::$MyHash][$TableName]['columns'][$key];
            if ($value == null or $value == [] or $value == '') {
                continue;
            }
            if (isset($ColumnData['encrypter'])) {
                $EncrypetName = $ColumnData['encrypter'];
                $EncrypetName = self::$name_prj[parent::$MyHash]."\\Encrypters\\".$EncrypetName;
                if (!class_exists($EncrypetName)) {
                    throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                }
                $value = $EncrypetName::Encrypt($value);
            }
            if (isset($ColumnData['enum'])) {
                $value = \Azad\Database\Enums::EnumToValue($TableName,$key,$value);
            }
            if(isset($ColumnData['type']) && method_exists($ColumnData['type'],"Set")) {
                $DB = new $ColumnData['type']();
                $value = $DB->Set($value);
            }
            $new[$key] = $value;
        }
        return $new;
    }
}