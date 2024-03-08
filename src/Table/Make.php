<?php

namespace Azad\Database\Table;

class Make extends \Azad\Database\Database {
        private $ColumnList = [],$Name,$ShortKeyType;
        public $PRIMARY_KEY = null;
        public $Unique = [];
        final protected function Save () {
            $table_name = str_replace(parent::$name_prj."\\Tables\\",'',get_class($this));
            $table_name = parent::$is_have_prefix?parent::$TablePrefix."_".$table_name:$table_name;
            parent::$TableData[$table_name]['data'] = $this->ColumnList;
            parent::$TableData[$table_name]['short'] = $this->ShortKeyType;
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
        public function List () {
            return parent::$TableData;
        }
    }