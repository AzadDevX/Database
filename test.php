<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");

$Users = $Sql->Table("Users");

try {

    $Users->Insert()
        ->Key("ID")->Value('1743912129')
        ->Key("first_name")->Value('Mohammad')
        ->Key("last_name")->Value('Azad')
    ->End();

    $Users = $Users->Select("*");

    var_dump($Users->WHERE("ID",1743912129)->FirstRow());


    /*
    $ManageWallet = $Wallet->WHERE("ID",13)->Manage ();

    $ManageWallet->Update(400,"USD");

    $ManageWallet
        ->Condition
            ->IF("USD")->EqualTo(350)
        ->End()
            ->Update(500,"USD");

    $ManageWallet
        ->Condition
            ->IF("IRT")->EqualTo(30000)
        ->End()
            ->Update(30000,"IRT");
    */

} catch (\Azad\Conditions\Exception $e) {
    var_dump($e->Debug);
    // The value of [USD] is equal to 400 - but you have defined (350) in the EqualTo
}


/*
INSERT DATA :
$Wallet = $Sql->Table("Wallet");

    $Wallet->Insert()
        ->Key("ID")->Value('100')
        ->Key("IRT")->Value('25000')
        ->Key("USD")->Value('300')
    ->End();

*/