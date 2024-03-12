<?php

namespace Azad\Database\Table\Columns;

class Init extends Get {
    private $Where;

    public $Data;
    public $Update;
    public $Condition;

    public function __construct($table_name,$query,$Where=null) {
        $this->TableName = $table_name;
        parent::$query[$this->TableName] = $query;
        if ($Where != null) {
            $this->Where .= $Where;
        } else {
            $this->Where = null;
        }
    }
    public function WHERE ($key,$value,$Conditions="=") {
        if(method_exists(new parent::$TableData[$this->TableName]['data'][$key]['type'],"Set")) {
            $DB = new parent::$TableData[$this->TableName]['data'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?" WHERE ":throw new Exception("You are allowed to use the WHERE method only once here.");
        $this->Where .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],$this->Where);
    }
    public function AND ($key,$value,$Conditions="=") {
        if(method_exists(new parent::$TableData[$this->TableName]['data'][$key]['type'],"Set")) {
            $DB = new parent::$TableData[$this->TableName]['data'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" AND ";
        $this->Where .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],$this->Where);
    }
    public function OR ($key,$value,$Conditions="=") {
        if(method_exists(new parent::$TableData[$this->TableName]['data'][$key]['type'],"Set")) {
            $DB = new parent::$TableData[$this->TableName]['data'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" OR ";
        $this->Where .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],$this->Where);
    }
    
    public function Data () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get() ?? false;
        $this->RemovedWhereInQuery ();
        $this->Result = $Result;
        $this->Update = new Update\Rows($this->TableName,$this->Where,$Result);
        $this->Update->Data = $Result;
        $this->Condition = new \Azad\Database\Conditions\Conditional($Result,false,$this->Update);
        return $this;
    }

    public function FirstRow () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get()[0] ?? false;
        $this->RemovedWhereInQuery ();
        $this->Result = $Result;
        $this->Update = new Update\Row($this->TableName,$Result);
        $this->Condition = new \Azad\Database\Conditions\Conditional($Result,true,$this->Update);
        return $this;
    }
    public function LastRow () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Data = $this->Get();
        $this->RemovedWhereInQuery ();
        $Result = array_pop($Data) ?? false;
        $this->Result = $Result;
        $this->Update = new Update\Row($this->TableName,$Result);
        $this->Condition = new \Azad\Database\Conditions\Conditional($Result,true,$this->Update);
        return $this;
    }

    private function RemovedWhereInQuery () {
        preg_match_all("#WHERE (.*)#",parent::$query[$this->TableName],$data);
        if (isset($data[0][0])) {
            parent::$query[$this->TableName] = str_replace(" ".$data[0][0],'',parent::$query[$this->TableName]);
        }
    }
    
    public function __set($name,$value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }

    /*public function Manage () {
        if ($this->Where) {
            parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        }
        parent::$query[$this->TableName] .= $this->Where;
        $QueryResult = $this->Get();
        $Query = parent::$query[$this->TableName];
        parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");

        if(count($QueryResult) == 0) {
            $Result = false;
        } elseif (count($QueryResult) == 1) {
            $Result = new Row($this->TableName,$Query);
        } else {
            $Result = new Rows($this->TableName,$Query);
        }
        return $Result;
    }*/
    /*public function WorkOn ($Key) { 
        if ($this->Where) {
            parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        }
        parent::$query[$this->TableName] .= $this->Where;
        $QueryResult = $this->Get();
        $Query = parent::$query[$this->TableName];
        parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");

        if(count($QueryResult) == 0) {
            return false;
        } else {
            return new WorkOn($Key,null,$Query);
        }
    }*/
}