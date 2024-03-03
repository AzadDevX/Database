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
        public $Insert;
        protected static $InsertSetting=[];
        public function __construct($Table_Name) {
            parent::$TableData['table_name'] = $Table_Name;
        }
        public function Select (...$Column) {
            parent::$TableData['column_name'] = $Column;
            parent::$Query = \Azad\Query::SelectQuery(parent::$TableData);
            return new Table\Columns();
        }
        public function Insert ($if_not_exists=true) {
            self::$InsertSetting["if_not_exists"] = $if_not_exists;
            return new Table\Insert();
        }
    }
}

namespace Azad\Database\Table {
    class AzadException extends \Exception {

    }
    class Insert extends \Azad\Database\Table {
        public function __construct() { }
        public function Key ($Key) {
            $this->Insert["key"][] = $Key;
            return $this;
        }
        public function Value ($Value) {
            $this->Insert["value"][] = "'$Value'";
            return $this;
        }
        public function End() {
            try {
                $Table = parent::$TableData['table_name'];
                $Data = $this->Insert;
                return $this->Query(\Azad\Query::Insert ($Table,$Data));
            } catch (\Azad\SqlException $e) {
                if (parent::$InsertSetting["if_not_exists"] == true) {
                    return false;
                } else {
                    throw new \Azad\SqlException($e->getMessage());
                }
            }
        }
    }
    class Columns extends \Azad\Database\Table {
        public $IFResult=true;
        public $IF;
        public function __construct() { }
        public function WHERE ($key,$value,$Conditions="=") {
            parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?" WHERE ":throw new AzadException("You are allowed to use the WHERE method only once here.");
            parent::$Query .= \Azad\Query::MakeWhere($key,$value,$Conditions);
            return new $this;
        }
        public function AND ($key,$value,$Conditions="=") {
            parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?throw new AzadException("First, you need to use the WHERE method."):" AND ";
            parent::$Query .= \Azad\Query::MakeWhere($key,$value,$Conditions);
            return new $this;
        }
        public function OR ($key,$value,$Conditions="=") {
            parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?throw new AzadException("First, you need to use the WHERE method."):" OR ";
            parent::$Query .= \Azad\Query::MakeWhere($key,$value,$Conditions);
            return new $this;
        }
        public function Get() {
            $data = $this->Fetch($this->Query(parent::$Query));
            parent::$TableData['table_data'] = $data;
            return $data;
        }
        public function FirstRow () {
            return $this->Get()[0];
        }
        public function Manage () {
            $QueryResult = $this->Get();
            if(count($QueryResult) == 0) {
                return false;
            } elseif (count($QueryResult) == 1) {
                return new Column\Row();
            } else {
                return new Column\Rows();
            }
        }
        public function WorkOn ($Key) {
            $QueryResult = $this->Get();
            if(count($QueryResult) == 0) {
                return false;
            } else {
                return new Column\WorkOn($Key);
            }
        }
        public function CheckQuery() {
            return parent::$Query;
        }
    }
    /*class LogicalOperators extends Columns {
        private $data = [];
        public function __get($Logical) {
            parent::$Query .= " $Logical ";
            return $this;
        }
    }*/
}

namespace Azad\Database\Table\Column {
    class WorkOn extends \Azad\Database\Table\Columns {
        private $Value;
        private $Key;
        public function __construct($Value,$is_key=true) {
            if ($is_key == true) {
                $this->Key = $Value;
                $this->Value = $this->Get()[0][$Value];
            } else {
                $this->Value = $Value;
            }
        }
        public function Tool ($Tool) {
            include_once("DataTools/".$Tool.".php");
            $Tool = '\\Azad\\DataTools\\'.$Tool;
            return new $Tool($this->Value,$this);
        }
        public function Result () {
            return $this->Value;
        }

    }

    class Row extends \Azad\Database\Table\Columns {
        public $Condition,$QueryResult;
        public function __construct() {
            $this->QueryResult = $this->Get();
            $this->Condition = new \Azad\Conditions\Conditions($this->QueryResult[0],$this);
        }
        public function Update($value,$key=null) {
            if ($this->IFResult == false) {
                return false;
            }
            $key = ($key == null)?((parent::$TableData['column_name'][0] != "*") ? parent::$TableData['column_name'][0] : throw new \Azad\Database\Table\AzadException("Column not set.")):$key;
            $Result = ($this->Query(\Azad\Query::UpdateQuery(parent::$TableData,$value,$key)) == true)?$this:false;
            $this->QueryResult = $this->Get();
            $this->Condition = new \Azad\Conditions\Conditions($this->QueryResult[0],$this);
            return $Result;
        }
        public function Remove() {
            return new Row\Remove();
        }
    }
    class Rows extends \Azad\Database\Table\Columns {
        public $QueryResult;
        public function ToArray () {
            $this->QueryResult = $this->Get();
            return $this->QueryResult;
        }
        public function First () {
            return new Row();
        }
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