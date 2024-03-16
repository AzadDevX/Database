<?php

namespace Azad\Database\Databases;

abstract class Query {
    abstract public static function MakeTable($table_obj);
    abstract public static function Insert($data);
    abstract public static function Find($data);
    abstract public static function Edit($table_name,$new_data,$Who);
    abstract public static function Select($Column,$Table);
    abstract public static function Where($key,$value,$Conditions);
}