<?php

namespace Azad\Database\Table\Columns;

class Init extends Get {
    private $Where;
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
            parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get() ?? false;
        parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        return $Result;
    }

    public function FirstRow () {
        if ($this->Where) {
            parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get()[0] ?? false;
        parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        return $Result;
    }
    public function LastRow () {
        if ($this->Where) {
            parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Data = $this->Get();
        $Result = array_pop($Data) ?? false;
        parent::$query[$this->TableName] = rtrim(parent::$query[$this->TableName],$this->Where ?? "");
        return $Result;
    }
    
    public function Manage () {
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
    }
    public function WorkOn ($Key) { 
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
    }
}