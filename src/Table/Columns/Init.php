<?php

namespace Azad\Database\Table\Columns;

class Init extends \Azad\Database\Table\Init {
    public $IFResult=true;
    public $IF;
    private $TableName;
    private static $query=[];
    public function __construct($table_name,$query) {
        $this->TableName = $table_name;
        self::$query[$this->TableName] = $query;
    }
    public function WHERE ($key,$value,$Conditions="=") {
        self::$query[$this->TableName] .= (strpos(self::$query[$this->TableName], "WHERE") === false)?" WHERE ":throw new Exception("You are allowed to use the WHERE method only once here.");
        self::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,self::$query[$this->TableName]);
    }
    public function AND ($key,$value,$Conditions="=") {
        self::$query[$this->TableName] .= (strpos(self::$query[$this->TableName], "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" AND ";
        self::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,self::$query[$this->TableName]);
    }
    public function OR ($key,$value,$Conditions="=") {
        self::$query[$this->TableName] .= (strpos(self::$query[$this->TableName], "WHERE") === false)?throw new Exception("First, you need to use the WHERE method."):" OR ";
        self::$query[$this->TableName] .= \Azad\Database\Query::MakeWhere($key,$value,$Conditions);
        return new $this($this->TableName,self::$query[$this->TableName]);
    }
    public function Get($table_name=null) {
        $TableName = (isset($table_name)) ? $table_name : $this->TableName;
        $Rows = $this->Fetch($this->Query(self::$query[$TableName]));
        $TableName = (string) $this->TableName;
        foreach ($Rows as $Row => $Data) {
            foreach ($Data as $key=>$value) {
                if (isset(parent::$TableData[$TableName]['data'][$key]['encrypter'])) {
                    $EncrypetName = parent::$TableData[$TableName]['data'][$key]['encrypter'];
                    $EncrypetName = parent::$ProjectName."\\Encrypters\\".$EncrypetName;
                    if (!class_exists($EncrypetName)) {
                        throw new \Azad\Database\Exception\Load("Encrypter [$EncrypetName] does not exist");
                    }
                    $value = $EncrypetName::Decrypt($value);
                }
                if(method_exists(new parent::$TableData[$TableName]['data'][$key]['type'],"Get")) {
                    $DB = new parent::$TableData[$TableName]['data'][$key]['type']();
                    $value = $DB->Get($value);
                }
                $Rows[$Row][$key] = $value;
            }
        }
        parent::$TableData['table_data'] = $Rows;
        return $Rows;
    }
    public function FirstRow () {
        return $this->Get()[0] ?? false;
    }
    public function Manage () {
        $QueryResult = $this->Get();
        if(count($QueryResult) == 0) {
            return false;
        } elseif (count($QueryResult) == 1) {
            return new Row($this->TableName,self::$query[$this->TableName]);
        } else {
            return new Rows($this->TableName,self::$query[$this->TableName]);
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