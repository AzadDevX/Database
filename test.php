<?php

require 'vendor/autoload.php';

$Sql = new Azad\Database\Connect("AzadSql");

$Wallet = $Sql->Table("Wallet");
$Users = $Sql->Table("Users");

$Wallet->Insert()
    ->Key("user_id")->Value(1) // Saved as 'mohammad' because the Rebuilder has been used
    ->Key("USD")->Value(10000)  // Saved as 'azad' because the Rebuilder has been used
    ->Key("IRT")->Value(800000)
->End();

$User = $Users->Select("*")->WHERE("first_name","Mohammad");

$User->Manage()->Update("azad2","last_name");

$User = $Users->Select("*")->WHERE("last_name","azad2");

?>