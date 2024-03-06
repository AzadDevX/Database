<?php

require 'vendor/autoload.php';

$Sql = new Azad\Database\Connect("AzadSql");

$Users = $Sql->Table("Users");
$Users = $Users->Select("*");
$User = $Users->WHERE("chat_id",123456789);

if (!$User->FirstRow ()) {
    $Users->Insert()
        ->Key("chat_id")->Value('123456789')
        ->Key("first_name")->Value('Mohammad') // Saved as 'mohammad' because the Rebuilder has been used
        ->Key("last_name")->Value('Azad')  // Saved as 'azad' because the Rebuilder has been used
        ->Key("salary")->Value('20000000') // Base64 encryption
    ->End();
}


// 10% increase to salary.
$NewSalary = $User->WorkOn("salary")->
    Tool("Percentage")
        -> Append(10)
    ->Close()
->Result();

// Update salary
$User->Manage()->Update($NewSalary,"salary");


// Get Salary
echo $User->FirstRow ()['salary'];
// Result: 22000000

# ------------------------------------------------------------------- #

echo PHP_EOL."Load Plugin:".PHP_EOL;

// Increase salary through plugins
$Sql->LoadPlugin ("IncreaseSalary",["chat_id"=>"123456789"])->Increase(10);
echo $User->FirstRow ()['salary']; // 24200000

echo PHP_EOL;

$User->Manage()->Update(9000,"OneLess");
echo $User->FirstRow ()['OneLess']; // in database: 8999 - result: 8999 - 
// The reason for -1 from the original number is the type of data type defined for example.
echo PHP_EOL;

$User->Manage()->Increase(150,"OneLess");
echo $User->FirstRow ()['OneLess']; // 9148 (8999 + 150 - 1 = 9148)
echo PHP_EOL;

$User->Manage()->Decrease(100,"OneLess");
echo $User->FirstRow ()['OneLess']; // 9047 (9148 - 100 - 1 = 9048)
echo PHP_EOL;

/*
INSERT DATA :
$Wallet = $Sql->Table("Wallet");

    $Wallet->Insert()
        ->Key("ID")->Value('100')
        ->Key("IRT")->Value('25000')
        ->Key("USD")->Value('300')
    ->End();


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