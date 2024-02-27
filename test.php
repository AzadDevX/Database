<?php

include_once("Repos/Load.php");

$Sql = new Azad\Sql('127.0.0.1','root','',"AzadSql");

$Data = $Sql->Table("Users")->Select("first_name")->Get();