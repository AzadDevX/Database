<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");

$Wallet = $Sql->Table("Wallet")
    ->Select("*")
    ->WHERE("ID",13)->Manage ()
    ->Update(300,"USD")
    ->Update(25000,"IRT");

var_dump($Wallet);
