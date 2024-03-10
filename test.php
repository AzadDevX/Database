<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);


$User = $Sql->Table("Users");
$Wallet = $Sql->Table("Wallet");

var_dump($Sql->Table("Users")->Wallet(2));

var_dump($User->Insert()
    ->Key("user_id")->Value(2)
    ->Key("first_name")->Value("Mohammad")
    ->Key("last_name")->Value("Azad")
        ->End());

var_dump($Wallet->Insert()
    ->Key("user_id")->Value(2)
    ->Key("USD")->Value(1000)
    ->Key("IRT")->Value("50000")
        ->End());