<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");


$Wallet = $Sql->Table("Wallet")
    ->Select("*")
        ->WHERE("ID",13)
            ->Manage ()
                ->Condition
                    ->IF("USD")->EqualTo(300)->Or("IRT")->EqualTo(25000)
                ->End()
                    ->Update(350,"USD");

var_dump($Wallet);
