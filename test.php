<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);



$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",2);
$Data = $Find->LastRow();
$Data->
    Update
        ->Key("first_name")->Value("MohammadA")
    ->Push();

var_dump($Find->LastRow()->Result);

$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",2);
$Data = $Find->Data();
/*
$Data->
    Condition->
        IF("USD")->Between(300,600)
    ->End()
        ->Key("USD")->Increase(50)
            ->Push();
*/

