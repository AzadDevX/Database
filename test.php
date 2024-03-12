<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);



$Transactions = $Sql->Table("Wallet");

$Find = $Transactions->Select("*");

$Data = $Find->Data();

$Data->
    Condition->
        IF("USD")->Between(300,600)
    ->End()
        ->Key("USD")->Increase(50)
            ->Push();


