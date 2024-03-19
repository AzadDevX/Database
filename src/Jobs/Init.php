<?php

namespace Azad\Database\Jobs;
class Init extends \Azad\Database\Database {

    private $id,$hash,$main_class;

    public function __construct($id,$hash,$main_class) {
        $this->id = $id;
        $this->hash = $hash;
        $this->main_class = $main_class;
    }
    public function Find($uid) {
        return new Find($this->hash,$uid,$this->main_class);
    }
    public function Class($class_name) {
        // ->Call(table_name)->Pass();
    }
    public function Table ($table_name) {
        return new Table($this->id,$this->hash,$table_name);
    }
    public function Start () {
        foreach (parent::$Jobs[$this->hash][$this->id]['jobs'] as $Data) {
            $column_name = $Data['column_name'];
            $new_value = $Data['new_value'];
            $job = $Data['job'];
            if ($job == 'update') {
                try {
                    parent::$Jobs[$this->hash][$this->id]['recovery'][] = $Data;
                    $Table = $this->main_class->Table($Data['table_class']);
                    $User = $Table->Select("*")->WHERE($Data['primary_column'],$Data['primary_value'])->LastRow();
                    $Result = $User->Update->Key($column_name)->Value($new_value)->Push();
                    if ($Result == false) {
                        return $this->Recovery();
                    }
                    if (in_array("jobs",self::$Log[self::$MyHash]['save'])) {
                        $Table = $Data['table_name'];
                        parent::Log(parent::DateLog ()." Jobs: ".$column_name." [".$Table."] -> ".$Data['primary_value']." = ".$Data['primary_column']." Updated To ".$new_value);
                    }
                } catch (\Throwable $Error) {
                    return $this->Recovery();
                }
            }
        }
        return true;
    }
    private function Recovery() {
        foreach(array_reverse(parent::$Jobs[$this->hash][$this->id]['recovery']) as $Data) {
            $DataChanged = $Data['column_name'];
            $Table = $this->main_class->Table($Data['table_class']);
            $User = $Table->Select("*")->WHERE($Data['primary_column'],$Data['primary_value'])->LastRow();
            $User->Update->Key($DataChanged)->Value($Data['old_value'])->Push();
            if (in_array("jobs",self::$Log[self::$MyHash]['save'])) {
                parent::Log(parent::DateLog ()." Jobs Recovery: ".$DataChanged." [".$Data['table_name']."] -> ".$Data['primary_value']." = ".$Data['primary_column']." Recovery To ".$Data['old_value']);
            }
        }
        return false;
    }
    public function Exception (\Azad\Database\Exceptions\Jobs $Error) {
        throw $Error;
    }
}


class Find extends Init {
    private $uid,$hash,$main_class;
    public function __construct($hash, $uid,$main_class) {
        $this->hash = $hash;
        $this->uid = $uid;
        $this->main_class = $main_class;
    }
    public function From ($table_name) {
        $Table = $this->main_class->Table($table_name);
        $table_name =  parent::$is_have_prefix[parent::$MyHash]?parent::$TablePrefix[parent::$MyHash]."_".$table_name:$table_name;
        $primary_key = self::$Tables[$this->hash][$table_name]["primary_key"];
        return $Table->Select("*")->WHERE($primary_key,$this->uid)->LastRow();
    }
}



class Table extends Init {
    private $hash,$table_name,$id;
    public function __construct($id,$hash,$table_name) {
        $this->hash = $hash;
        $this->table_name = $table_name;
        $this->id = $id;
    }
    public function Select ($column_name) {
        return new Select($this->id,$this->hash,$this->table_name,$column_name);
    }
}

class Select extends Init {
    private $hash,$table_name,$column_name,$id;
    public function __construct($id,$hash,$table_name,$column_name) {
        $this->hash = $hash;
        $this->table_name = $table_name;
        $this->column_name = $column_name;
        $this->id = $id;
    }
    public function To ($new_value) {
        return new To($this->id,$this->hash,$this->table_name,$this->column_name,$new_value);
    }
}

class To extends Init {
    private $hash,$table_name,$column_name,$new_value,$id;

    public function __construct($id,$hash,$table_name,$column_name,$new_value) {
        $this->hash = $hash;
        $this->table_name = $table_name;
        $this->column_name = $column_name;
        $this->id = $id;
        $this->new_value = $new_value;
    }
    public function Who ($user_id) {
        return new SaveJob($this->id,$this->hash,$this->table_name,$this->column_name,$this->new_value,$user_id);
    }
}

class SaveJob extends Init {
    public function __construct($id,$hash,$table_name,$column_name,$new_value,$user_obj) {
        $table_class = $table_name;
        $table_name = parent::$is_have_prefix[$hash]?parent::$TablePrefix[$hash]."_".$table_name:$table_name;
        $FindUser = self::$Tables[$hash][$table_name]["primary_key"];
        $primary_value = $user_obj->Result[$FindUser];
        parent::$Jobs[$hash][$id]['jobs'][] =
        [
            'table_name'=>$table_name,
            'table_class'=>$table_class,
            'column_name'=>$column_name,
            'new_value'=>$new_value,
            'primary_column'=>$FindUser,
            'primary_value'=>$primary_value,
            'old_value'=>$user_obj->Result[$column_name],
            'job'=>'update',
        ];
    }
}