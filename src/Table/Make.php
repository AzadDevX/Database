<?php

namespace Azad\Database\Table;

class Make extends MakeINIT {
        private $ColumnList = [],$Name;
        public $PRIMARY_KEY = null;
        final protected function Save () {
            parent::$TableData[get_class($this)] = $this->ColumnList;
        }
        protected function Name($name) {
            $this->Name = $name;
            $this->ColumnList[$name] = [];
            return $this;
        }
        protected function Type($type) {
            $this->ColumnList[$this->Name]['type'] = new $type();
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