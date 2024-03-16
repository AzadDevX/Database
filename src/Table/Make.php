<?php

namespace Azad\Database\Table;

class Make extends \Azad\Database\Database {
    private $Name;

    public $Columns = [];
    public $ShortKeyType;
    public $ForeignFrom;
    public $PRIMARY_KEY = null;
    public $Unique = [];
    public $Prefix;

    private static $TableName;

    final public function Save () {
        $table_name = str_replace(parent::$name_prj[parent::$MyHash]."\\Tables\\",'',get_class($this));
        $table_name = isset($this->Prefix)?$this->Prefix."_".$table_name:$table_name;
        //parent::$Tables[parent::$MyHash][$table_name]['data'] = $this->Columns;
        //parent::$Tables[parent::$MyHash][$table_name]['short'] = $this->ShortKeyType;
        //parent::$Tables[parent::$MyHash][$table_name]['foreign_from'] = $this->ForeignFrom ?? false;
        self::$TableName = $table_name;
    }

    final protected function Name($name) {
        $this->Name = $name;
        $this->Columns[$name] = [];
        $this->ShortKeyType[$name] = null;
        return $this;
    }
    final protected function Type($type) {
        if (!$this->Name) {
            throw new Exception("You need to specify the column name first.");
        }
        if (!class_exists($type)) {
            throw new Exception("The 'type' value entered is not valid");
        }
        $this->Columns[$this->Name]['type'] = new $type();
        $this->ShortKeyType[$this->Name] = new $type();
        return $this;
    }
    final protected function Size($size) {
        if (!$this->Name) {
            throw new Exception("You need to specify the column name first.");
        }
        $this->Columns[$this->Name]['size'] = $size;
        return $this;
    }
    final protected function Rebuilder($name) {
        if (!$this->Name) {
            throw new Exception("You need to specify the column name first.");
        }
        $this->Columns[$this->Name]['rebuilder'] = $name;
        return $this;
    }
    final protected function Encrypter($name) {
        if (!$this->Name) {
            throw new Exception("You need to specify the column name first.");
        }
        $this->Columns[$this->Name]['encrypter'] = $name;
        return $this;
    }
    final public function Foreign ($table_name,$column,$prefix=true) {
        if ($prefix == true) {
            $table_name = parent::$is_have_prefix[parent::$MyHash]?parent::$TablePrefix[parent::$MyHash]."_".$table_name:$table_name;
        }
        $this->ForeignFrom = $table_name;
        $this->Columns[$this->Name]['foreign'] = ['table'=>$table_name,'column'=>$column];
        return $this;
    }
    final public function Null () {
        $this->Columns[$this->Name]['default'] = 'NULL';
        return $this;
    }
    final public function NotNull () {
        $this->Columns[$this->Name]['not_null'] = true;
        return $this;
    }
    final public function Default ($string) {
        $this->Columns[$this->Name]['default'] = $string;
        return $this;
    }
    final public static function Table($table_name) {
        return new \Azad\Database\Table\Init($table_name,'');
    }
    final public function IndexCorrelation() {
        parent::$IDListTable[self::$TableName] = [];
    }
    final static public function Get($table,$prefix=true) {
        if ($prefix == true) {
            $table = parent::$is_have_prefix[parent::$MyHash]?parent::$TablePrefix[parent::$MyHash]."_".$table:$table;
        }
        return json_decode(json_encode(parent::$IDListTable[$table])) ?? false;
    }
    final public static function Correlation($OriginColumn,$table_name,$column) {
        $this_table_name = str_replace(parent::$name_prj[parent::$MyHash]."\\Tables\\",'',static::class);
        $this_table_name = parent::$is_have_prefix[parent::$MyHash]?self::$TablePrefix[parent::$MyHash]."_".$this_table_name:$this_table_name;
        $LastUser = self::Get($this_table_name,false);
        if (isset($LastUser->$OriginColumn)) {
            $Where = $LastUser->$OriginColumn;
            $Wallet = self::Table($table_name);
            $Select = $Wallet->Select("*");
            $Where = $Select->WHERE($column,$Where);
            return json_decode(json_encode($Where->Data()->Result));
        }
        return false;
    }
    
}