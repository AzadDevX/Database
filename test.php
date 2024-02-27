<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");

$Users = $Sql->Table("Users")
    ->Select("all")
    ->WHERE("first_name","mohammad")
        ->AND("last_name","azad")
            ->OR("ID",12,"!=")
    ->MyQuery ();

var_dump($Users);


$Wallet = $Sql->Table("Wallet")
    ->Select("USD")
    ->WHERE("ID",13)
    ->MyQuery ();
    
var_dump($Wallet);
