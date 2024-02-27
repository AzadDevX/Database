<?php
namespace Azad\Database {


    class MakeTableData {
        protected static $TableData=[],$SubClass=[];
        public static function MakeTables () {
            self::$SubClass = array_values(array_filter(get_declared_classes(),fn($class_name) => is_subclass_of($class_name,"Azad\Database\MakeTable")));
            array_map(function ($class_name) {
                new $class_name();
                $Query = \Azad\Query::MakeTable($class_name,self::$TableData[$class_name]);
                self::$TableData[$class_name]['query'] = $Query;
            } ,self::$SubClass);
            return self::$TableData;
        }
        public static function GetSetting ($class) {
            return get_class_vars($class);
        }
    }
    class MakeTable extends MakeTableData {
        private $ColumnList = [],$Name;
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
        }
        public function List () {
            return parent::$TableData;
        }
    }


    class Table extends \Azad\Sql {
        public function __construct($Table_Name) {
            parent::$TableData['table_name'] = $Table_Name;
        }
        public function Select (...$Column) {
            $Column = ($Column[0] == "all") ? "*" : $Column;
            parent::$TableData['column_name'] = $Column;
            return new Table\Columns();
        }
    }
}

namespace Azad\Database\Table {
    class Columns extends \Azad\Database\Table {
        public function __construct() {
            
        }
        public function Find ($WHERE) {
            // if one result:
            return new Column\Row();
            // if more result:
            // return new \Azad\Database\Table\Column\Rows();
        }
        public function Get() {
            return $this->Fetch($this->Query(\Azad\Query::SelectQuery(parent::$TableData)));
        }
    }
}

namespace Azad\Database\Table\Column {
    class Row extends \Azad\Database\Table\Columns {
        public function __construct() { }
        public function Update($value,$key=null) {

        }
        public function Remove() {
            return new Row\Remove();
        }
    }
    class Rows extends \Azad\Database\Table\Columns {

    }
}

namespace Azad\Database\Table\Column\Row {
    class Remove extends \Azad\Database\Table\Column\Row {
        private $ConditionData;
        public function __construct() { }
        public function IF() {
            // Conditions class
            return $this;
        }
        public function do_It() { /* short if */
            if ($this->ConditionData['status'] == false) {
                // Exception
            } else {
                // Removed
            }
        }
    }
}
