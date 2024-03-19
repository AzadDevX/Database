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
    ->End();
}


$Find = $Users->Select("*")->WHERE("user_id",1);
$Data = $Find->LastRow();


var_dump($Data->Result['status']->name); // "Active"
var_dump($Data->Result['status']->toPersian()); // فعال
var_dump($Data->Result['status'] == MyProject\Enums\UserStatus::Active); // true

$New = $Data->Update
    ->Key("status")->Value(MyProject\Enums\UserStatus::Disable)
->Push();

var_dump($New->Result['status']->name); // "Disable"
var_dump($New->Result['status']->toPersian()); // غیرفعال
var_dump($New->Result['status'] == MyProject\Enums\UserStatus::Active); // false


?>