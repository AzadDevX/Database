<?php

namespace Azad\Database\Table;

class Make extends MakeINIT {
        private $ColumnList = [],$Name,$ShortKeyType;
        public $PRIMARY_KEY = null;
        final protected function Save () {
            $table_name = isset(self::$TablePrefix)?self::$TablePrefix."_".get_class($this):get_class($this);
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
            $this->ColumnList[$this->Name]['type'] = new $type();
            $this->ShortKeyType[$this->Name] = new $type();
            return $this;
        }
        protected function Size($size) {
            $this->ColumnList[$this->Name]['size'] = $size;
            return $this;
        }
        protected function Rebuilder($name) {
            $this->ColumnList[$this->Name]['rebuilder'] = $name;
            return $this;
        }
        protected function Encrypter($name) {
            $this->ColumnList[$this->Name]['encrypter'] = $name;
            return $this;
        }
        public function List () {
            return parent::$TableData;
        }
    }