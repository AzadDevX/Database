<?php


require 'vendor/autoload.php';
include_once("config.php");

use Azad\Database\Connection;
use Azad\Database\built_in\Time as Time;

$Sql = new Connection(MySqlConfig::class);

$Users = $Sql->Table("Users");
for ($i=6; $i < 10000; $i++) { 
    $Find = $Users->Select("*")->Where("user_id",$i);
    $Find->LastRow()->Result;
}

$Sql->Close();

/*
$Sql->LoadPlugin("MyUsers",[]);

$Users = $Sql->Table("Users");

if(!$Users->RowExists("first_name","Mohammad")){
    $Users->Insert()
        ->Key("first_name")->Value("Mohammad")
        ->Key("last_name")->Value("Azad")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value(10000)
    ->End();

    $Users->Insert()
        ->Key("first_name")->Value("Mohammad2")
        ->Key("last_name")->Value("Azad2")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value(50000)
    ->End();

    $Users->Insert()
        ->Key("first_name")->Value("Mohammad3")
        ->Key("last_name")->Value("Azad3")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active)
        ->Key("wallet")->Value(50000)
    ->End();
}

$Find1 = $Users->Select("*")->Where("user_id",1);
$Find2 = $Users->Select("*")->Where("user_id",2);
$Find3 = $Users->Select("*")->Where("user_id",3);

var_dump($Find1->LastRow()->Result);
var_dump($Find2->LastRow()->Result);
var_dump($Find3->LastRow()->Result);




/*
// Update Timestamp: 1710868190
$date = new Time($Timestamp);
$passed_time = $date->HowLongAgo(time(),$data);
echo "Passed secound: ".$passed_time.PHP_EOL;
// Passed secound: 366309 (secound)
var_dump($data);

object(stdClass)#40 (6) {
  ["Years"]=>
  int(0)
  ["Months"]=>
  int(0)
  ["Days"]=>
  int(4)
  ["Hours"]=>
  int(5)
  ["Minutes"]=>
  int(45)
  ["Secounds"]=>
  int(9)
}
    4 days, 5 hours, 45 minutes, and 9 seconds have passed since the last update.


$date->AddYears(1);
$date->AddMonths(3);
$date->AddHours(13);
echo "New Timestamp: ".$date->timestamp.PHP_EOL;
// New Timestamp: 1749794990

$until_time = $date->LeftUntil (time(),$until_data);
echo "Time Left Until now: ".$until_time.PHP_EOL;
// Time Left Until now: 38560491 (secound)
var_dump($until_data);

object(stdClass)#41 (6) {
  ["Years"]=>
  int(1)
  ["Months"]=>
  int(2)
  ["Days"]=>
  int(26)
  ["Hours"]=>
  int(7)
  ["Minutes"]=>
  int(14)
  ["Secounds"]=>
  int(51)
}
*/