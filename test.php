<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);



$Transactions = $Sql->Table("Wallet");

$Find = $Transactions->Select("*")->WHERE("user_id",2);

$Data = $Find->LastRow();


# Update

$Data = $Data->
    Update
        ->Key("USD")->Increase(50)
            ->Push();

# Update with Condition

$Data = $Data->
    Condition
        ->IF("USD")->EqualTo(1910)
    ->End()
        ->Key("USD")->Increase(50)
            ->Push();


var_dump($Find->LastRow()->Result);

/*
$Data->
    Condition->
        IF("USD")->Between(300,600)
    ->End()
        ->Key("USD")->Increase(50)
            ->Push();
*/

