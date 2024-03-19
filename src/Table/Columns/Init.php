<?php

namespace Azad\Database\Table\Columns;

class Init extends Get {
    private $Where,$Hash;

    public $Data;
    public $Update;
    public $Condition;

    public function __construct($table_name,$query,$hash,$Where=null) {
        $this->TableName = $table_name;
        parent::$MyHash = $hash;
        parent::$query[$this->TableName] = $query;
        if ($Where != null) {
            $this->Where .= $Where;
        } else {
            $this->Where = null;
        }
    }
    public function WHERE ($key,$value,$Conditions="=") {
        if(method_exists(new parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type'],"Set")) {
            $DB = new parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?" WHERE ":throw new \Azad\Database\Exceptions\Structure("You are allowed to use the WHERE method only once here.");
        $this->Where .= parent::MakeQuery()::Where($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],parent::$MyHash,$this->Where);
    }
    public function AND ($key,$value,$Conditions="=") {
        if(method_exists(parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type'],"Set")) {
            $DB = parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?throw new \Azad\Database\Exceptions\Structure("First, you need to use the WHERE method."):" AND ";
        $this->Where .= parent::MakeQuery()::Where($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],parent::$MyHash,$this->Where);
    }
    public function OR ($key,$value,$Conditions="=") {
        if(method_exists(parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type'],"Set")) {
            $DB = parent::$Tables[parent::$MyHash][$this->TableName]['columns'][$key]['type']();
            $value = $DB->Set($value);
        }
        $this->Where .= (strpos($this->Where ?? "", "WHERE") === false)?throw new \Azad\Database\Exceptions\Structure("First, you need to use the WHERE method."):" OR ";
        $this->Where .= parent::MakeQuery()::Where($key,$value,$Conditions);
        return new $this($this->TableName,parent::$query[$this->TableName],parent::$MyHash,$this->Where);
    }
    
    public function Data () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get(parent::$MyHash) ?? false;
        if ($Result == false) {
            throw new \Azad\Database\Exceptions\Row("The data searched does not exist in the table. Table Name: ".$this->TableName." Search Query: ".$this->Where);
        }
        $this->RemovedWhereInQuery ();
        return new ReturnRows($this->TableName,$Result,parent::$MyHash);
    }

    public function FirstRow () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Result = $this->Get(parent::$MyHash)[0] ?? false;
        if ($Result == false) {
            throw new \Azad\Database\Exceptions\Row("The data searched does not exist in the table. Table Name: ".$this->TableName." Search Query: ".$this->Where);
        }
        $this->RemovedWhereInQuery ();
        return new ReturnRow($this->TableName,$Result,parent::$MyHash);
    }
    public function LastRow () {
        if ($this->Where) {
            $this->RemovedWhereInQuery ();
        }
        parent::$query[$this->TableName] .= $this->Where;
        $Data = $this->Get(parent::$MyHash);
        $this->RemovedWhereInQuery ();
        $Result = array_pop($Data) ?? false;
        if ($Result == false) {
            throw new \Azad\Database\Exceptions\Row("The data searched does not exist in the table. Table Name: ".$this->TableName." Search Query: ".$this->Where);
        }
        return new ReturnRow($this->TableName,$Result,parent::$MyHash);
    }

    private function RemovedWhereInQuery () {
        preg_match_all("#WHERE (.*)#",parent::$query[$this->TableName],$data);
        if (isset($data[0][0])) {
            parent::$query[$this->TableName] = str_replace(" ".$data[0][0],'',parent::$query[$this->TableName]);
        }
    }
    
}