<?php

require 'vendor/autoload.php';

$Sql = new Azad\Database\Connect("AzadSql");

$Users = $Sql->Table("Users");
$Users = $Users->Select("*");
$Users->Insert()
    ->Key("first_name")->Value('Mohammad') // Saved as 'mohammad' because the Rebuilder has been used
    ->Key("last_name")->Value('Azad')  // Saved as 'azad' because the Rebuilder has been used
    ->Key("address")->Value([
        "country" => "Iran",
        "city" => "Tehran",
        "Address" => "-"
    ])
->End();

$User = $Users->WHERE("first_name","Mohammad")
            ->And("last_name","Azad");

var_dump($User->FirstRow());

?>