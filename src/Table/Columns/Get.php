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
            if ($value == null or $value == [] or $value == '' or $value == 'null') {
                continue;
            }
            if($ColumnData['type']?->Primary == true) {
                $new[$key] = $value;
                break;
            }
            $value = self::Encrypt ($TableName,$key,$value);
            $value = \Azad\Database\Enums::EnumToValue($TableName,$key,$value);
            $value = self::SetDataType ($TableName,$key,$value);
            $new[$key] = $value;
        }
        return $new;
    }

    protected function PreparingNewData (string $table,array $data,
        bool $encrypt=true,
        bool $set_datatype=true,
        bool $venum=false,
        bool $enumv=false,
        bool $check_valid=true,
        bool $normalizer=true,
    ) : array {
        foreach($data as $key=>$value) {
            if ($value == null) { continue; }
            $value = $check_valid == true?self::is_valid_data ($table,$key,$value):$value;
            $value = $set_datatype == true?self::SetDataType ($table,$key,$value):$value;
            $value = $normalizer == true?self::Normalizer ($table,$key,$value):$value;
            $value = $venum == true?\Azad\Database\Enums::ValueToEnum($table,$key,$value):$value;
            $value = $enumv == true?\Azad\Database\Enums::EnumToValue($table,$key,$value):$value;
            $value = $encrypt == true?self::Encrypt ($table,$key,$value):$value;
            $data[$key] = $value;
        }
        return $data;
    }

    private static function Encrypt ($table,$column,$value) {
        $ColumnData = self::$Tables[self::$MyHash][$table]['columns'][$column];
        if (isset($ColumnData['encrypter'])) {
            $EncryptName = $ColumnData['encrypter'];
            $EncryptClass = self::$name_prj[self::$MyHash]."\\Encrypters\\".$EncryptName;
            if (!class_exists($EncryptClass)) {
                if (self::$SystemConfig[self::$MyHash]["Debug"]) {
                    throw new \Azad\Database\Exceptions\Debug(__METHOD__,['directory'=>self::$dir_prj[self::$MyHash],'project_name'=>self::$name_prj[self::$MyHash]],$EncryptName);
                }
                throw new \Azad\Database\Exceptions\Load("Encrypter does not exist",\Azad\Database\Exceptions\LoadCode::Encrypeter->value,$EncryptName);
            }
            $value = $EncryptClass::Encrypt($value);
        }
        return $value;
    }
    private static function SetDataType ($table,$column,$value) {
        $ColumnData = self::$Tables[self::$MyHash][$table]['columns'][$column];
        if(isset($ColumnData['type']) && method_exists($ColumnData['type'],"Set")) {
            $DB = new $ColumnData['type']();
            $value = $DB->Set($value);
        }
        return $value;
    }
    private static function is_valid_data ($table,$column,$value) {
        $ColumnData = self::$Tables[self::$MyHash][$table]['columns'][$column];
        if(isset($ColumnData['type']) && method_exists($ColumnData['type'],"is_valid")) {
            $DB = new $ColumnData['type']();
            if(!$DB->is_valid($value)) {
                throw new \Azad\Database\Exceptions\DataType("The entered value is not acceptable for type class.");
            }
        }
        return $value;
    }
    private static function Normalizer ($table,$column,$value) {
        $ColumnData = self::$Tables[self::$MyHash][$table]['columns'][$column];
        if (isset($ColumnData['Normalizer'])) {
            if (!is_array($value)) {
                $value = parent::NormalizerResult($ColumnData['Normalizer'],$value);
            } else {
                $Normalizer = $ColumnData['Normalizer'];
                $value = \Azad\Database\Arrays::Value($value,function ($data) use ($Normalizer) {
                    return parent::NormalizerResult($Normalizer,$data);
                });
            }
        }
        return $value;
    }
}