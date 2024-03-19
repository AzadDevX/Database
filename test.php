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
        ->Key("wallet")->Value(10000)
    ->End();

    $Users->Insert()
        ->Key("first_name")->Value("Mohammad2")
        ->Key("last_name")->Value("Azad2")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value(50000)
    ->End();

    $Users->Insert()
        ->Key("first_name")->Value("Mohammad3")
        ->Key("last_name")->Value("Azad3")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value(50000)
    ->End();
}

$Find = $Users->Select("*");

var_dump($Find->Data()
        ->Condition
            ->IF("wallet")->MoreOrEqualThan(25000)
        ->End()
        ->Update
            ->Key("wallet")->Increase(50000)
        ->Push()
->Result);
