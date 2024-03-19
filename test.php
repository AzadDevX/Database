<?php


require 'vendor/autoload.php';
include_once("config.php");

use Azad\Database\Connection;

$Sql = new Connection(MySqlConfig::class);

$Sql->LoadPlugin("MyUsers",[]);

$Users = $Sql->Table("Users");

if(!$Users->RowExists("first_name","Mohammad")){
    $Users->Insert()
        ->Key("first_name")->Value("Mohammad")
        ->Key("last_name")->Value("Azad")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value("50000")
    ->End();
}

$Find = $Users->Select("*")->WHERE("user_id",1);
var_dump($Find->LastRow()->Result);
var_dump($Find->LastRow()->Update->Key("wallet")->Increase(5000)->Push()->Update->Key("wallet")->Increase(5000)->Push()->Result);