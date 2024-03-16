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
    public function EndJob () {
        foreach (parent::$Jobs[$this->hash][$this->id]['jobs'] as $Data) {
            $column_name = $Data['column_name'];
            $new_value = $Data['new_value'];
            $user = $Data['user'];
            $job = $Data['job'];
    
            if ($job == 'update') {
                try {
                    $Result = $user->Update->Key($column_name)->Value($new_value)->Push();
                    echo $column_name." changed to ".$new_value.PHP_EOL;
                    parent::$Jobs[$this->hash][$this->id]['recovery'][] = ['column_name'=>$column_name,'user'=>$user];
                } catch (\Throwable $Error) {
                    return $this->Recovery();
                }
            }
        }
        return true;
    }
    private function Recovery() {
        foreach(parent::$Jobs[$this->hash][$this->id]['recovery'] as $Data) {
            $OldData = $Data['user']->Result;
            $DataChanged = $Data['column_name'];
            $FindOldData = $OldData[$DataChanged];
            $Data['user']->Update->Key($DataChanged)->Value($FindOldData);
            echo $DataChanged." Backed to ".$FindOldData;
        }
        return false;
    }
    public function Exception (Exception $Error) {
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
        parent::$Jobs[$hash][$id]['jobs'][] =
        [
            'table_name'=>$table_name,
            'column_name'=>$column_name,
            'new_value'=>$new_value,
            'user'=>$user_obj,
            'job'=>'update'
        ];
    }
}