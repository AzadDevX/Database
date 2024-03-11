<?php

require 'vendor/autoload.php';
include_once("config.php");

$Sql = new Azad\Database\Connect();
$Sql->Config(MyConfig::class);

$Users = $Sql->Table("Users");
$Select = $Users->Select("*");
$Where = $Select->WHERE("user_id",2);
$Where->FirstRow();

var_dump($Users->Wallet()->USD); # 1000

