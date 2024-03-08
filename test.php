<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);


$User = $Sql->Table("Users");

$User->Insert()
    ->Key("user_id")->Value(1)
    ->Key("first_name")->Value("Mohammad")
    ->Key("last_name")->Value("Azad")
        ->End();

$FindUser = $User->Select("*")->WHERE("user_id",1);

var_dump($FindUser->FirstRow());

$IS = $Sql->LoadPlugin("IncreaseSalary",$FindUser->FirstRow());

$IS->ChangeName ("Mohammad2");

var_dump($FindUser->FirstRow());