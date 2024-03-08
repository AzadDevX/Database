<?php

require 'vendor/autoload.php';

$Sql = new Azad\Database\Connect("AzadSql");

/*
$Wallet->Insert()
    ->Key("user_id")->Value(1)
    ->Key("USD")->Value(10000)
    ->Key("IRT")->Value(800000)
->End();

$Users->Insert()
    ->Key("first_name")->Value("Mohammad") 
    ->Key("last_name")->Value("Azad")
->End();

$Users->Insert()
    ->Key("first_name")->Value("Mohammad2") 
    ->Key("last_name")->Value("AzadKrde")
->End();
*/

$User = $Sql->Table("Users")->Select("*")->WHERE("first_name","Mohammad")->And('user_id',1);
$User2 = $Sql->Table("Users")->Select("*")->WHERE("first_name","Mohammad2")->And('user_id',3);

var_dump($User->Data());
$User->Manage()->Update("azad_M1232","last_name");
var_dump($User->Data());

var_dump($User2->Data());
$User2->Manage()->Update("azad_MR213","last_name");
var_dump($User2->Data());