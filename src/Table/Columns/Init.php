<?php

namespace Azad\Database\Table\Columns;

class Init extends \Azad\Database\Table\Init {
    public $IFResult=true;
    public $IF;
    public function __construct() { }
    public function WHERE ($key,$value,$Conditions="=") {
        parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?" WHERE ":throw new Exception("You are allowed to use the WHERE method only once here.");
        parent::$Query .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this;
    }
    public function AND ($key,$value,$Conditions="=") {
        parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" AND ";
        parent::$Query .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this;
    }
    public function OR ($key,$value,$Conditions="=") {
        parent::$Query .= (strpos(parent::$Query, "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" OR ";
        parent::$Query .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this;
    }
    public function Get() {
        $Rows = $this->Fetch($this->Query(parent::$Query));
        $TableName = parent::$TableData['table_name'];
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                if (isset(parent::$TableData[$TableName][$key]['encrypter'])) {
                    $EncrypetName = parent::$TableData[$TableName][$key]['encrypter'];
                    $value = $EncrypetName::Decrypt($value);
                    $Rows[$Row][$key] = $value;
                }
            }
        }
        parent::$TableData['table_data'] = $Rows;
        return $Rows;
    }
    public function FirstRow () {
        return $this->Get()[0];
    }
    public function Manage () {
        $QueryResult = $this->Get();
        if(count($QueryResult) == 0) {
            return false;
        } elseif (count($QueryResult) == 1) {
            return new Row();
        } else {
            return new Rows();
        }
    }
    public function WorkOn ($Key) {
        $QueryResult = $this->Get();
        if(count($QueryResult) == 0) {
            return false;
        } else {
            return new WorkOn($Key);
        }
    }
}