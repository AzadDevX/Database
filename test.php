<?php


require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connection(MySqlConfig::class);

$Users = $Sql->Table("Users");
$ID = $Users->Insert()
    ->Key("first_name")->Value("F_".rand(1,99999))
    ->Key("last_name")->Value("L_".rand(1,99999))
->End();

$Find = $Users->Select("*")->WHERE("user_id",$ID);
$Data = $Find->LastRow();

$Result1 = $Data
    ->Update
        ->Key("first_name")->Value("Moha424d32wew32")
        ->Key("last_name")->Value("A4224d13rwr331")
    ->Push()
->Result;

$Sql->CloseLog ();

/*
for ($i=0; $i < 50; $i++) {

    $Users = $Sql->Table("Users");
    $ID = $Users->Insert()
        ->Key("first_name")->Value("F_".rand(1,99999))
        ->Key("last_name")->Value("L_".rand(1,99999))
    ->End();



    $Data
        ->Update
            ->Key("first_name")->Value("F_".rand(1,99999))
            ->Key("last_name")->Value("L_".rand(1,99999))
        ->Push()
    ->Result;

}


$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",2);
$Data = $Find->LastRow();

$Save1 = $Data
    ->Update
        ->Key("first_name")->Value("sfafjbs")
        ->Key("last_name")->Value("Azad")
    ->Push()
->Result;


$Users = $Sql->Table("Users");
$Find = $Users->Select("*");
$Data = $Find->Data();

$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",3);
$Data = $Find->Data();

$Find = $Users->Select("*")->WHERE("user_id",2);
$Data = $Find->Data();

$Find = $Users->Select("*")->WHERE("user_id",3);
$Data = $Find->LastRow();
$Save1 = $Data
    ->Update
        ->Key("first_name")->Value("MohammadReza")
        ->Key("last_name")->Value("gH")
    ->Push()
->Result;


*/