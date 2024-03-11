<?php

namespace Azad\Database\Table;

class Make extends \Azad\Database\Database {
        private $ColumnList = [],$Name,$ShortKeyType,$ForeignFrom;
        public $PRIMARY_KEY = null;
        public $Unique = [];

        private static $TableName;
        final protected function Save () {
            $table_name = str_replace(parent::$name_prj."\\Tables\\",'',get_class($this));
            $table_name = parent::$is_have_prefix?parent::$TablePrefix."_".$table_name:$table_name;
            parent::$TableData[$table_name]['data'] = $this->ColumnList;
            parent::$TableData[$table_name]['short'] = $this->ShortKeyType;
            parent::$TableData[$table_name]['foreign_from'] = $this->ForeignFrom ?? false;
            self::$TableName = $table_name;
        }

        protected function Name($name) {
            $this->Name = $name;
            $this->ColumnList[$name] = [];
            $this->ShortKeyType[$name] = null;
            return $this;
        }
        protected function Type($type) {
            if (!$this->Name) {
                throw new Exception("You need to specify the column name first.");
            }
            if (!class_exists($type)) {
                throw new Exception("The 'type' value entered is not valid");
            }
            $this->ColumnList[$this->Name]['type'] = new $type();
            $this->ShortKeyType[$this->Name] = new $type();
            return $this;
        }
        protected function Size($size) {
            if (!$this->Name) {
                throw new Exception("You need to specify the column name first.");
            }
            $this->ColumnList[$this->Name]['size'] = $size;
            return $this;
        }
        protected function Rebuilder($name) {
            if (!$this->Name) {
                throw new Exception("You need to specify the column name first.");
            }
            $this->ColumnList[$this->Name]['rebuilder'] = $name;
            return $this;
        }
        protected function Encrypter($name) {
            if (!$this->Name) {
                throw new Exception("You need to specify the column name first.");
            }
            $this->ColumnList[$this->Name]['encrypter'] = $name;
            return $this;
        }
        public function Foreign ($table_name,$column,$prefix=true) {
            if ($prefix == true) {
                $table_name = parent::$is_have_prefix?parent::$TablePrefix."_".$table_name:$table_name;
            }
            $this->ForeignFrom = $table_name;
            $this->ColumnList[$this->Name]['foreign'] = ['table'=>$table_name,'column'=>$column];
        }
        public function Null () {
            $this->ColumnList[$this->Name]['default'] = 'NULL';
            return $this;
        }
        public function NotNull () {
            $this->ColumnList[$this->Name]['not_null'] = true;
            return $this;
        }
        public function Default ($string) {
            $this->ColumnList[$this->Name]['default'] = $string;
            return $this;
        }
        public function List () {
            return parent::$TableData;
        }
        final public static function Table($table_name) {
            return new \Azad\Database\Table\Init($table_name);
        }
        final public function GlobalME() {
            parent::$IDListTable[self::$TableName] = [];
        }
        final static public function Get($table,$prefix=true) {
            if ($prefix == true) {
                $table = parent::$is_have_prefix?parent::$TablePrefix."_".$table:$table;
            }
            return json_decode(json_encode(parent::$IDListTable[$table])) ?? false;
        }
        final public static function Correlation($OriginColumn,$table_name,$column) {
            $this_table_name = str_replace(parent::$name_prj."\\Tables\\",'',static::class);
            $this_table_name = parent::$is_have_prefix?self::$TablePrefix."_".$this_table_name:$this_table_name;
            $LastUser = self::Get($this_table_name,false);
            if (isset($LastUser->$OriginColumn)) {
                $Where = $LastUser->$OriginColumn;
                $Wallet = self::Table($table_name);
                $Select = $Wallet->Select("*");
                $Where = $Select->WHERE($column,$Where);
                return json_decode(json_encode($Where->FirstRow()));
            }
            return false;
        }
    }