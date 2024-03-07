<?php

namespace Azad\Database\Table\Columns;

class Init extends Get {
    public function __construct($table_name,$query) {
        $this->TableName = $table_name;
        parent::$query[$this->TableName] = $query;
    }
    public function WHERE ($key,$value,$Conditions="=") {
        parent::$query[$this->TableName] .= (strpos(parent::$query[$this->TableName], "WHERE") === false)?" WHERE ":throw new Exception("You are allowed to use the WHERE method only once here.");
        parent::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName]);
    }
    public function AND ($key,$value,$Conditions="=") {
        parent::$query[$this->TableName] .= (strpos(parent::$query[$this->TableName], "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" AND ";
        parent::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName]);
    }
    public function OR ($key,$value,$Conditions="=") {
        parent::$query[$this->TableName] .= (strpos(parent::$query[$this->TableName], "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" OR ";
        parent::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName]);
    }
    
    public function FirstRow () {
        return $this->Get()[0] ?? false;
    }
    public function Manage () {
        $QueryResult = $this->Get();
        if(count($QueryResult) == 0) {
            return false;
        } elseif (count($QueryResult) == 1) {
            return new Row($this->TableName,parent::$query[$this->TableName]);
        } else {
            return new Rows($this->TableName,parent::$query[$this->TableName]);
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