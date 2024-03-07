<?php

require 'vendor/autoload.php';

$Sql = new Azad\Database\Connect("AzadSql");

$Wallet = $Sql->Table("Wallet")->Select("*");
$Users = $Sql->Table("Users")->Select("*");

$Users->Insert()
    ->Key("user_id")->Value(1) // Saved as 'mohammad' because the Rebuilder has been used
    ->Key("USD")->Value(10000)  // Saved as 'azad' because the Rebuilder has been used
    ->Key("IRT")->Value(800000)
->End();


$Users->Insert()
    ->Key("first_name")->Value('Mohammad') // Saved as 'mohammad' because the Rebuilder has been used
    ->Key("last_name")->Value('Azad')  // Saved as 'azad' because the Rebuilder has been used
    ->Key("address")->Value([
        "country" => [
            "fullName"=>"Iran",
            "tag"=>"IR"
        ],
        "city" => "Tehran",
        "Address" => "-"
    ])
->End();

$User = $Users->WHERE("first_name","Mohammad");

$User->Manage()->Update("azad","last_name");

?>