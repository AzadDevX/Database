![AzadSqlBanner](https://github.com/AzadDevX/Database/assets/158297225/23f131f0-c061-47e3-bee9-24efae6c0e7e)

![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

[![GitHub issues](https://img.shields.io/github/issues/AzadDevX/Database.svg)](https://GitHub.com/AzadDevX/Database/issues/)
[![GitHub version](https://badge.fury.io/gh/AzadDevX%2FDatabase.svg)](https://github.com/AzadDevX/Database)

[![packagist](https://img.shields.io/packagist/v/AzadDevX/Database)](https://packagist.org/packages/AzadDevX/Database)
[![Github all releases](https://img.shields.io/github/downloads/AzadDevX/Database/total.svg)](https://GitHub.com/AzadDevX/Database/releases/)
[![MIT license](https://img.shields.io/badge/License-MIT-blue.svg)](https://lbesson.mit-license.org/)

# 1. Introduction

One of the main goals of the project is to establish a connection with the database in a way that maintains clean code structure while ensuring program speed, despite the use of various automated capabilities and tools. Additionally, employing object-oriented methods in different parts of the project improves team productivity and helps team members gain full mastery in various project areas.

It’s worth noting that this project is still in the testing and debugging phase, and we invite programmers to join us in its development.

# 2. how does it work

This is a developing library for interacting with databases (currently only MySQL). The library can automatically encrypt data, sort it, and also execute Query commands through job definitions without losing information or making unilateral changes.

# 3. Basic rules

Folder Setup:
First, you need to create a folder structure for your project. This folder should include files related to tables, encryption, builders, and other necessary components.
Make sure to configure this folder properly.
Project Namespace:
Alongside configuring the folder, the project name (defined in the configuration class) should serve as a namespace for your project files.
This ensures that your project remains organized and that different components can be easily associated with the project.

for example:

```php
namespace MyProject\Plugins;
```

Remember that this project is still in the testing and debugging phase, so it’s essential to collaborate with fellow developers to enhance its functionality. If you have any further questions or need assistance, feel free to ask!

# 4. Initial Setup

First, install the azaddevx/database library using Composer. To do this, run the following command in your terminal:

```console
composer require azaddevx/database
```

Next, load the library using the autoload.php file:

```php

require 'vendor/autoload.php';

// Load the Connect class to start using the library:
$Database = new Azad\Database\Connection(CONFIG CLASS);
```

Now, to begin, you need to create a **configuration class**. The components of the configuration are explained below.

After creating this class, pass it directly to the Connect class.

**Database**: In this section, enter the database connection information. In the future, depending on the type of database you have, the information set for this feature may differ. Currently, this feature includes four elements named as follows:

``host``: Your database host

``username``: The username used for connection

``password``: Your database password

``name``: The name of the database you intend to connect to

**Project**: In this section, your project information including the project name, project folder, etc., is set up

which includes two elements: ``directory`` and ``name``

**Table**: This feature contains the general settings related to the data table. Currently, it includes an element named prefix. The prefix of the data table can essentially serve as a signature for all data tables, placed before the actual name and separated from the main table name by an underscore (_).

**Log**: This feature is useful when you are debugging your project. The logs save all the operations performed by the library in an insecure file (you must handle the security settings yourself), and you can view the commands executed by the library. It includes the following three elements:

- ``file_name``: The name of the file where the logs are saved.

- ``save``: The data you need to save in this file, which includes the following elements:
  - **query**: Saves the query commands.
  - **affected_rows**: Saves the number of changes applied after executing a query.
  - **get_ram**: Saves the data received from the system’s RAM.
  - **save_ram**: Saves the data stored in the system’s RAM.
  - **jobs**: Saves the data related to jobs.
  - **database**: Saves all the stored data related to databases.

- ``retain_previous_data``: A boolean type, if true, previous logs are retained in the file.

**System**: This feature pertains to the main system of the library, which includes two elements: ``RAM`` and ``Database``.

``RAM``: This feature stores the data received from the database in the system’s RAM, eliminating the need for resending queries to retrieve them again. This process enhances the speed of the project but configuring it is optional.

``Database``: The type of database you intend to connect to; currently, only Mysql is available.

Below is a sample of the configuration class:

```php
<?php
class MySqlConfig {
    public $Database,$Project,$Table,$Log,$System;
    public function __construct() {
        # -------- Database config
        $this->Database['host'] = '127.0.0.1';
        $this->Database['username'] = 'root';
        $this->Database['password'] = '';
        $this->Database['port'] = '';
        $this->Database['name'] = 'AzadSql';


        # -------- Project config
        $this->Project['directory'] = __DIR__."/MyProject";
        $this->Project['name'] = "MyProject";
        if (!file_exists($this->Project['directory'])) { mkdir($this->Project['directory']); }


        # -------- Table config
        $this->Table['prefix'] = "mp";


        # -------- Log
        $this->Log['file_name'] = "Database.log";
        $this->Log['save'] = ['query','affected_rows','get_ram','jobs'];
        // save_ram , database
        $this->Log['retain_previous_data'] = false;


        # -------- System
        $this->System['RAM'] = true;
        # On average 25% speed increase if activated!
        $this->System['Database'] = 'Mysql';
    }
}
```

After you have configured the config class, pass it to the main library class.

For example:

```php
$Database = new Azad\Database\Connection(MySqlConfig::class);
```

# 5. Main Project Folders

After a successful connection, these folders will be created in the directory set by you (in configuration class):

```php
    MyProject/
  
      Enums/ # Soon
    
      Encrypters/
  
      Exceptions/ # Soon
    
      Plugins/
  
      Rebuilders/
    
      Tables/
```

# 6. How to Create a Data Table

To do this, enter the Tables folder in your project folder (`MyProject\Tables\`). This folder contains files that are used to create a data table.

To do this, enter the project folder and create a php file in the Tables folder 
> [!NOTE]
> the file name and class name **must match**. Also, make sure to use the **namespace** that includes **your project name and the term Tables** (``PROJECT-NAME\Tables``). Finally, ensure that you inherit from the ``\Azad\Database\Table\Make`` class.

```php
<?php
namespace MyProject\Tables;
class Users extends \Azad\Database\Table\Make {

}
```

In the above example, we are creating a data table named **Users** in a file called Users.php

(``PROJECT-NAME\Tables\Users.php``).

Now it’s time to configure the data table. The table settings are done in the class constructor (``__construct``).

First, pay attention to the following example:

```php
<?php
namespace MyProject\Tables;
class Users extends \Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("user_id")->Type(\Azad\Database\Types\ID::class)->Size(255);
        $this->Name("first_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255);
        $this->Name("last_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255);
        $this->Name("created_at")->Type(\Azad\Database\Types\CreatedAt::class);
        $this->Name("updated_time")->Type(\Azad\Database\Types\UpdateAt::class);
        $this->Save ();
    }
}
```

It is clear that the columns of the data table are added line by line. Using the ``Name`` method, we specify the name of the data table column, and with ``Type``, we determine the type of data we intend to store in it. Finally, with ``Size``, we set the size of the data. This is the simplest way to create columns for a data table. However, the data column settings are not limited to these three methods.

## Methods

```php
Name(column_name)
```

``column_name`` : The column name is set in this method. The input to this method is a string and it needs to be called in the most initial state. Other methods that are explained later require the name defined by this method.
> [!CAUTION]
> **PHP Fatal error:  ``Uncaught Azad\Database\Table\Exception``: You need to specify the column name first.**

```php
Type(class_type)
```

``class_type``: This method determines the data type of the column. Below is a list of data types available.
> [!NOTE]
> that the input parameter must be a class from ``\Azad\Database\Types\``

Types:

- ``ArrayData``: Takes data as an array input and outputs it as an array, but it is stored in the database as JSON.

- ``AutoLess``: This data type is provided as a guide for other team programmers (explained at the end of the documentation).

- ``BigINT``: No need for explanation.!

- ``Boolean``: No need for explanation.!

- ``Decimal``: No need for explanation.!

- ``Floats``: No need for explanation.!

- ``CreatedAt``: Does not require manual value assignment during insertion; this column stores the time when the row was created.

- ``ID``: Used to determine user IDs, it uses the n+1 algorithm and selects the column as the primary key.

- ``Integer``: No need for explanation.!

- ``Random``: This data type is provided as a guide for other team programmers (explained at the end of the documentation).

- ``UpdateAt``: Similar to CreatedAt, it does not require value assignment and stores the time of the last edit of a row.

- ``Varchar``: No need for explanation.!

- ``timestamp``: No need for explanation.!

- ``Decimal``: No need for explanation.!

- ``Token``: Creates a unique identifier, useful for creating APIs.

- ``UserID``: Selected as the primary key and is of the BigINT type."

> [!CAUTION]
> If the set data type does not exist, you will encounter such an error.
> **PHP Fatal error:  ``Uncaught Azad\Database\Table\Exception``: The 'type' value entered is not valid**

```php
Size(size)
```

``size``: Data Type Size

```php
Rebuilder(rebuilder_name) # Set a Rebuilder for Column
```

``rebuilder_name`` ``(string)`` : Rebuilder Name

**What are Rebuilders**? Rebuilders help you to standardize the appearance of your data. For example, they can make all letters lowercase (this aids in data evaluation and extraction)

In the Magic section, this feature has been elaborated upon in detail

```php
Encrypter(encrypter_name) # Set a Encrypter for Column
```

``encrypter_name`` ``(string)`` : Encrypter Name

**What is an Encryptor?** This feature encodes the data before storage and decrypts it upon retrieval. With this, you can easily encrypt a column and automatically access the decrypted data when displaying it.

This feature is explained in detail in the Magic section.

```php
Foreign(table_name,column_name) # constraint is used to prevent actions that would destroy links between tables.
```

``table_name`` ``(string)`` : Main table name

``column_name`` ``(string)`` : Main column name

```php
Null () # set default to Null
```

```php
NotNull () # This means that you cannot insert a new record, or update a record without adding a value to this field
```

```php
Default ($string) # Set a default value for column
```

```php
Save() # After setting all columns, call this method
```

## Properties

Also, you can use the following Properties.

```php
$Unique = [Column names];
```

This ensures that the set columns do not repeat in other rows.

for example:

```php
<?php
namespace MyProject\Tables;
class Users extends \Azad\Database\Table\Make {
    public $Unique = ["first_name","last_name"];
    public function __construct() {
        $this->Name("user_id")->Type(\Azad\Database\Types\ID::class)->Size(255);
        $this->Name("first_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("created_at")->Type(\Azad\Database\Types\CreatedAt::class);
        $this->Name("updated_time")->Type(\Azad\Database\Types\UpdateAt::class);
        $this->Save ();
    }
}
```

## Correlation of tables data

This capability creates a link between tables, allowing the user to find themselves in another table without the need for a repeated search

Manual mode:

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result;
$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",$Transactions_Data['user_id']);
$UsersData = $Find->LastRow()->Result;
return $UsersData["first_name"];  #mohammad
```

Use of Tables Correlation:

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result;
return $Transactions->UserData()->first_name; #mohammad
```

### How to set up correlation?

First, you need to specify the parent table with ``IndexCorrelation`` method. the data from this table will be stored in a global variable after receiving the data.

```php
    public function __construct() {
        $this->Name("user_id")->Type(\Azad\Database\Types\ID::class)->Size(255);
        $this->Name("first_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("address")->Type(\Azad\Database\Types\ArrayData::class)->Rebuilder("Names")->Encrypter("Base64");
        $this->Name("created_at")->Type(\Azad\Database\Types\CreatedAt::class);
        $this->Name("updated_time")->Type(\Azad\Database\Types\UpdateAt::class);
        $this->Save ();
        $this->IndexCorrelation(); # <--------
    }
```

Now using the internal ``Correlation`` method to get the data from the second table and define it in a **static** function.

```php
    public static function Wallet () {
        return self::Correlation("user_id","Wallet","user_id")[0] ?? false;
    }
```

```php
Correlation($OriginColumn,$table_name,$column)
```

``OriginColumn`` : Column name to be evaluated using (use PRIMARY column here)

``table_name`` : Destination Table Name

``column`` : The name of the column to which the OriginColumn data is sent

``Correlation`` data output is an **array** of all found data.

> [!IMPORTANT]
> You need to extract your data before using correlation, this rule is set to prevent heavy library processing

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result; # <------
return $Transactions->UserData();
```

**Result:**

```php
object(stdClass)#38 (6) {
  ["user_id"]=>
  string(1) "2"
  ["first_name"]=>
  string(8) "mohammad"
  ["last_name"]=>
  string(4) "azad"
  ["address"]=>
  NULL
  ["created_at"]=>
  string(19) "2024-03-10 15:48:25"
  ["updated_time"]=>
  string(19) "2024-03-10 15:48:25"
}
```

Second example :

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
// $Transactions_Data = $Find->LastRow()->Result;
return $Transactions->UserData();
```

**Result:**

```php
bool(false)
```

> [!WARNING]
> Risk of data mismatch in case of lack of attention

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result;
$Find = $Transactions->Select("*")->WHERE("user_id",3); # <---- user id changed!
# Although the user ID has changed, but no operation has been carried out on it.
return $Transactions->UserData();
```

**Result:**

```php
object(stdClass)#38 (6) {
  ["user_id"]=>
  string(1) "2"
  ["first_name"]=>
  string(8) "mohammad"
  ["last_name"]=>
  string(4) "azad"
  ["address"]=>
  NULL
  ["created_at"]=>
  string(19) "2024-03-10 15:48:25"
  ["updated_time"]=>
  string(19) "2024-03-10 15:48:25"
}
```

Second example (No problem) :

```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result;
$Find = $Transactions->Select("*")->WHERE("user_id",3);
$Transactions_Data = $Find->LastRow()->Result; # Perform operations on the found data, the global variable is updated!!!
return $Transactions->UserData();
```

**Result:**

```php
object(stdClass)#38 (6) {
  ["user_id"]=>
  string(1) "3"
  ["first_name"]=>
  string(16) "hypothetical_name"
  ["last_name"]=>
  string(20) "hypothetical_lastname"
  ["address"]=>
  NULL
  ["created_at"]=>
  string(19) "2024-03-12 15:56:34"
  ["updated_time"]=>
  string(19) "2024-03-12 15:56:34"
}
```

# 6. How do we import data into a data table?

After the data table configuration is complete, to start injecting data, select your desired data table using the ``Table`` method.

The output of this method is another class with methods like ``Select`` and ``Insert`` and functions that you have defined in the library class. Currently, we use the ``Insert`` method to inject data.

for example:

```php
$Database = new Azad\Database\Connect("AzadSql");
$Users = $Database->Table("Users");
```

After selecting the table, insert your data into the table using the ``Insert`` method

```php
$Database = new Azad\Database\Connect("AzadSql");
$Users = $Database->Table("Users");

$Users->Insert()
    ->Key("first_name")->Value('Mohammad') // Saved as 'mohammad' because the Rebuilder has been used
    ->Key("last_name")->Value('Azad')  // Saved as 'azad' because the Rebuilder has been used
->End();
```

## Methods

```php
Key(column_name)
```

``column_name`` : Set the name of the table column in this field.

```php
Value(data)
```

``data`` : The data you want to consider for this column.

```php
End(); # After completing the list of columns and their values, call this method
```

In summary, after selecting the data table, there are two methods for adding data: the ``Key`` method, which is for the name of the data column, and the ``Value`` method, which specifies the value of this column.

Finally, after determining the data, the data injection process begins with the ``End()`` command. If you need the ID identifier of your data, the output of End is actually the last id added to the data table.

## How can I check if a row exists?

Sometimes it’s necessary to verify whether the data you intend to add to a database might encounter duplication issues. To achieve this, we use the RowExists method to check whether a row exists in our data table or not

```php
$Table->RowExists("column_name","value"); // true | false
```

In the example below, we examine whether the name ``Mohammad`` has been registered for the column **first_name** in the **Users data table** or not. The output of this method is true (meaning it exists) and false (meaning it does not exist).

After ensuring that this data has not been saved in our table, we add it to the table (this prevents errors related to unique and primary_key)."

```php
$Users = $Sql->Table("Users");
if(!$Users->RowExists("first_name","Mohammad")){
    $Users->Insert()
        ->Key("first_name")->Value("Mohammad")
        ->Key("last_name")->Value("Azad")
        ->Key("wallet")->Value(10000)
    ->End();
}
```

## Using enums

Before using enums, it’s necessary to talk about their application. What is an enum?
These constants are typically used to make code more readable and maintainable by replacing numeric codes with descriptive names.
Enums make your code more understandable by allowing you to use meaningful names instead of magic numbers.
Enums provide compile-time type checking, preventing invalid values from being assigned to variables.
Enums group related constants, which can be helpful when you have a set of related values that should be considered together.

To start using enums, you need to follow a few steps:

### 1. Creating a New Enum

Navigate to the enums folder within your project’s directory and create a file with the name of your enum. In this example, we are using UserStatus (```MyProject/Enums/UserStatus.php```).

Then, specify that this is a class for an enum by using ```namespace MyProject\Enums;```

> [!NOTE]
> This step is not mandatory but helps in making your code more readable).

Next, create an enum using the ```enum``` keyword. [PHP Document](https://www.php.net/manual/en/language.types.enumerations.php)

Consider the following example where we have set two states for the status of our users’ accounts, Active and Inactive:

```php
enum UserStatus {
    case Active;
    case Inactive;
}
```

You can also assign values to each of the expressions, but note that you must specify the data type. For instance, if you consider the number 1 for Active, you must set that this is an enum of type int:

```php
enum UserStatus : int {
    case Active = 1;
    case Inactive = 2;
}
```

> [!NOTE]
> However, it is not mandatory to set a value for each case, as the library will automatically operate according to your enum.

### 2.Data Table Settings

Open the data table file of your choice.
After setting the name, use the Enum method from the name method.
Look at the example below:

```php
$this->Name("status")->Enum(\MyProject\Enums\UserStatus::class);
```

After assigning the Enum, the process of saving and retrieving data will change.

To add a row and also to update it, you must definitely use the defined enum. In the example below, you will see a sample of adding data.

```php
$Users = $Sql->Table("Users");
if(!$Users->RowExists("first_name","Mohammad")){
    $Users->Insert()
        ->Key("first_name")->Value("Mohammad")
        ->Key("last_name")->Value("Azad")
        ->Key("status")->Value(MyProject\Enums\UserStatus::Active) # <--------
        ->Key("wallet")->Value(10000)
    ->End();
}
```

Now, when you want to retrieve the value of ```status```, the output of the ```Result``` will be the same as the defined enum.

Pay attention to the example below

```php
$Find = $Users->Select("*")->WHERE("user_id",1);
$Data = $Find->LastRow();
return $Data->Result['status']->name; // "Active"
```

You can also add functions to your enum. For example, we have added a function to translate the status into Persian.

```php
<?php

namespace MyProject\Enums;

enum UserStatus {
    case Active;
    case Inactive;
    public function toPersian () {
        return match ($this->name) {
            "Active" => "قعال",
            "Inactive" => "غیرفعال"
        };
    }
}
```

Now, when we want to display the user’s status in Persian, we do it like this:

```php
return $Data->Result['status']->toPersian(); // فعال
```

# 7. How do we extract a row or multiple rows from a data table?

Here, we will work with another method called ``Select``. This method get the column whose data you need; if you want the data of all columns, use the asterisk (*).

After selecting your column, you can search for a specific row/rows with the ``WHERE``, ``AND``, ``OR``.

Pay attention to the example below.

```php
$Users = $Database->Table("Users")->Select("*");
$User = $Users->WHERE("first_name","Mohammad")
                    ->AND("last_name","azad");
```

## Methods

```php
WHERE(column_name,value)
```

``column_name`` : The name of the column you want to get your data with its value.

``value`` : Column value.

```php
AND(column_name,value) # Logical Operators (and - &&)
```

``column_name`` : The name of the column you want to get your data with its value.

``value`` : Column value.

```php
OR(column_name,value) # Logical Operators (or - ||)
```

``column_name`` : The name of the column you want to get your data with its value.

``value`` : Column value.

After completing the data search with WHERE clauses, you can access the data using these three methods:

``Data()``: This includes all the retrieved data.

``FirstRow()``: Displays the first retrieved data.

``LastRow()``: Displays the last retrieved data.

The output of all three methods consists of three attributes:

**Result**: The retrieved data.

**Update**: Used for updating or editing data.

**Condition**: Contains conditional expression methods.

Currently, we intend to extract data, so we’ll use the ``Result`` attribute.

## Rows

```php
$User->Data ()->Result;
```

This method displays the list of all found data. for example:

```php
array(1) {
  [0]=>
  array(5) {
    ["user_id"]=>
    string(1) "1"
    ["first_name"]=>
    string(8) "mohammad"
    ["last_name"]=>
    string(4) "azad"
    ["created_at"]=>
    string(19) "2024-03-06 17:49:10"
    ["updated_time"]=>
    string(19) "2024-03-06 17:51:20"
  }
}
```

## Row

```php
$User->FirstRow ()->Result;
# OR
$User->LastRow ()->Result;
```

This method get the first/last data found.

```php
array(5) {
  ["user_id"]=>
  string(1) "1"
  ["first_name"]=>
  string(8) "mohammad"
  ["last_name"]=>
  string(4) "azad"
  ["created_at"]=>
  string(19) "2024-03-06 17:49:10"
  ["updated_time"]=>
  string(19) "2024-03-06 17:51:20"
}
```

# 8. How to Update a row

To update one or more columns, after selecting Row (via ``FirstRow`` | ``LastRow`` | ``Data``) Use the ``Update`` properties.

```php
$User->LastRow ()
    ->Update
        ....
    ->Push();
```

> [!IMPORTANT]
> if the output of ``$User`` is more than one value, All of them will be updated.

**Example:**

```php
$Users = $Sql->Table("Users");
$Find = $Users->Select("*")->WHERE("user_id",2);
$Data = $Find->LastRow();
$Data->
    Update
        ->Key("first_name")->Value("Mohammad")
    ->Push();
```

Now, to update the value of a column we have three methods:

## Methods:
```php
Value(new_value)
```

``new_value`` : Set a new value in this parameter

```php
Increase(number)
```

``number`` : The number you want to **add to the previous value**. ``(value + number = new_value)``

```php
Decrease(number)
```

``number`` : The number you want to **subtract to the previous value**. ``(number - value = new_value)``

## Condition

You can also use conditional commands to update your data.
Examples: (In this example, all columns of USD that range from 300 to 600 are added to 50 values.)

```php
try {
    $Users = $Sql->Table("Users");
    $Find = $Users->Select("*");
    $Data = $Find->Data();
    $Data->
        Condition->
            IF("USD")->Between(300,600)
        ->End()
            ->Update
                ->Key("USD")->Increase(50)
            ->Push();
} catch (Azad\Database\Conditions\Exception $E) {
    var_dump($E->getMessage());
}

```

### Methods

```php
IF (column_name)
```

``column_name`` : Column name

```php
And (column_name) # Logical Operators (and - &&)
```

``column_name`` : Column name

```php
Or (column_name) # Logical Operators (or - ||)
```

``column_name`` : Column name

### Conditional Methods

```php
EqualTo(x) # The defined column is equal to the value of x
ISNot(x) # The defined column does not equal the value of x
LessThan(x) # The column is defined as less than x.
MoreThan(x) # The column is defined as more than x.
LessOrEqualThan(x) # If the value of the column is less than or equal to the value of x.
MoreOrEqualThan(x) # If the value of the column is greater than or equal to the value of x.
Between(x , y) # The value of the column is between x and y - ( x <= value && y >= value)
Have(x) # If there is x in the column value - (Used for arrays and strings)
NotHave(x) # If there is no x in the column value - (Used for arrays and strings)
IN(array x) # If x exists in the data of a column.
NotIN(array x) # If there is no x in the data of a column،
```

# Functionals

Functional functions, (which are located in the main project) to expedite work. This part is still developing. (``src\Functional``)

# Magic

## Rebuilders

In a simple sense, it means sorting data. Use Rebuilder when you plan to store data regularly in the database.

``tEhRAn -> Tehran``

The data is sent to rebuilders before being saved, then the rebuilder stores it in the database after the changes are made.

< **Data -> Rebuilder -> New Data -> Save** >

### How to make new Rebuilder

To do this, enter the project folder and create a php file in the Rebuilders folder (``AzadSql\Rebuilders\x.php``)
In this example, we use the name "Names", using Rebuilder "Names" to store the user's names as case lower 

(``x.php -> Names.php``)

**Rules:**

1. Similar to the table structure, the file name needs to be the same as the class name.
2. Use the namespace. ``ProjectName\Rebuilders``
3. Inherit from ``\Azad\Database\Magic\Rebuilder``
4. Create a method called ``Rebuild`` as **static** and set only one parameter for its input. ``Rebuild ($Data)``
5. The end. The output of ``Rebuild`` is stored in the table and ``$Data`` is the data that is intended to be stored in the table

$Data -> Names::Rebuild($Data) -> Save

```php
<?php
# Names.php
namespace MyProject\Rebuilders;
class Names extends \Azad\Database\Magic\Rebuilder {
    public static function Rebuild ($Data) {
        return strtolower($Data);
    }
}
```

And in the table you want to do for the column you want:

```php
    ...
        $this->Name("first_name")
            ->Type(\Azad\Database\Types\Varchar::class)
            ->Size(255)
            ->Rebuilder("Names"); # <------
        $this->Name("last_name")
            ->Type(\Azad\Database\Types\Varchar::class)
            ->Size(255)
            ->Rebuilder("Names"); # <------
    ...
```

## Encrypters

If you intend to store vital data, use this method!

Data encryption is done automatically and you don't need to decrypt and encrypt continuously.

Data is encrypted before it is stored and decrypted after it is received.

Data -> Encrypt -> Save

Get -> Decrypt -> Data

### How to make new Encrypter:

To do this, enter the project folder and create a php file in the Encrypters folder (``AzadSql\Encrypters\x.php``)
In this example, we use the name "Base64", Using Base64 Encrypter، encrypts your data to base64 before storing it and decrypts when receives it.

(``x.php -> Base64.php``)

**Rules:**

1. Similar to the table structure, the file name needs to be the same as the class name.
2. Use the namespace. ``ProjectName\Encrypters``
3. Inherit from ``\Azad\Database\Magic\Encrypter``
4. Create a method called ``Encrypt`` as **static** and set only one parameter for its input. ``Encrypt($Data)``
5. Create a method called ``Decrypt`` as **static** and set only one parameter for its input. ``Decrypt($Data)``
6. The end.

Example:

```php
<?php

namespace MyProject\Encrypters;
class Base64 extends \Azad\Database\Magic\Encrypter {
    public static function Encrypt($Data) {
        return base64_encode($Data);
    }
    public static function Decrypt($Data) {
        return base64_decode($Data);
    }
}
```

And in the table you want to do for the column you want:

```php
    ...
        $this->Name("password")
            ->Type(\Azad\Database\Types\Varchar::class)
            ->Size(255)
            ->Encrypter("Base64"); # <------
    ...
```

## Plugins

The plugin has access to all database data. It can receive data and change them. The processes are done inside the plug-in class.
Plugins help you improve your teamwork and also publish plugins on the web
Plugins help you process the data in another folder and access its defined methods in the main file.

### How to make Plugin

To do this, enter the project folder and create a php file in the Plugins folder (``MyProject\Plugins\x.php``)

**Rules:**

1. Similar to the table structure, the file name needs to be the same as the class name.
2. Use the namespace. ``ProjectName\Plugins``
3. Inherit from ``\Azad\Database\Magic\Plugin``

``self::Table(X)`` : Select a table to work on data.

``$this->Data`` : This value is **set during coding**, the data needed for the plugin is placed in this section

Example of making a plugin:

```php
<?php

namespace MyProject\Plugins;

class UserManagment extends \Azad\Database\Magic\Plugin {
    public function ChangeFirstName ($new_first_name) {
        $Users = self::Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("user_id",$this->Data->Result['user_id']);
        $User->LastRow()->
            Update
                ->Key("first_name")->Value($new_first_name)
            ->Push();
    }
}

?>
```

You can also import another plugin through the ``IncludePlugin`` method.

```php
<?php

namespace MyProject\Plugins;
class ChangeName extends \Azad\Database\Magic\Plugin {
    public function ChangeName ($new_first_name) {
        $UserManagment = $this->IncludePlugin("UserManagment",$this->Data);
        $UserManagment->ChangeFirstName ($new_first_name);
    }
}

?>
```

Example of loading a plugin:

```php
<?php
#  index.php

require 'vendor/autoload.php'; // load librarys

$Sql = new Azad\Database\Connect("AzadSql"); // load AzadSql

$Users = $Sql->Table("Users"); // Select table
$Users = $Users->Select("*"); //Select Columns

$User = $Users->WHERE("first_name","Mohammad")
            ->And("last_name","azad"); // Find User

$UserManagment = $Sql->LoadPlugin ("UserManagment",$User->LastRow()); // Load Plugin

$UserManagment->ChangeFirstName("Mohammad2"); // Use plugin methods

var_dump($User->LastRow()->Result); // Get new data
```

```php
array(5) {
  ["user_id"]=>
  string(1) "5"
  ["first_name"]=>
  string(9) "mohammad2"
  ["last_name"]=>
  string(4) "azad"
  ["created_at"]=>
  string(19) "2024-03-07 02:30:18"
  ["updated_time"]=>
  string(19) "2024-03-07 13:05:13"
}
```

## Jobs

An interesting and very efficient feature. Have you ever experienced that after transferring funds between two users, one user’s balance decreases but the second user’s balance does not increase? It’s true, you might have been entangled in consecutive and complex conditions, but here we have a better solution.

Using this feature, all data are evaluated before execution, listed in order, and if the execution of one of them encounters a problem, the previous data is recovered, and no data is changed.

Pay attention to the example below

```php
try {

    $Job1 = $Sql->NewJob();

    # Job1 -> Find (VALUE_PRIMARY_KEY) -> From (TABLE_NAME)
    $User1 = $Job1->Find(1)->From("Users");
    $User1_Wallet = $User1->Result['wallet'];
    $User2 = $Job1->Find(2)->From("Users");
    $User2_Wallet = $User2->Result['wallet'];

    $Amount = 10000;

    # Job1 -> Table (TABLE_NAME) -> SELECT (COLUMN_NAME) -> To (NEW_VALUE) -> Who? (UserObject)
    $Job1->Table("Users")->Select("wallet")->To($User1_Wallet + $Amount)->Who($User1);
    $Job1->Table("Users")->Select("wallet")->To($User2_Wallet - $Amount)->Who($User2);

    if ($User2_Wallet < $Amount) {
        $Job1->Exception(new ExceptionJob("User 2 does not have enough inventory",-1));
    }

    $Job1->Start();

} catch (ExceptionJob $E) {
    $message = match ($E->getCode()) {
        -1 => "User 2 you do not have enough balance, please recharge your account.",
        default => "There is a problem, please try it later."
    };
    print($message);
}
```

In the above example, we intend to transfer 10,000 monetary units between two users.

The clean architecture of this feature is as follows:

First, define the users whose data you need.

Then, define the variables you require.

Now, start operations on the data.

Finally, check the data before starting the job.

In the first step, it is necessary to define a new job, which is done with the ``NewJob`` method.

```PHP
$Job1 = $Sql->NewJob();
```

After defining a Job to receive data from a table (in the example above, ``Users``), use the following structure:

```PHP
$Job1->Find(VALUE_PRIMARY_KEY)->From(TABLE_NAME);
```

The input for the ``Find`` method is actually the value of the Primary Key column. The job automatically detects which column is the Primary Key and uses the value of this method to evaluate with the Primary column.

Then, using the ``From`` method, specify which table the data belongs to.

> [!Note]
> that in the Job, do not manually change the data at all; we need the previous data and the new data, and this requires properly writing the jobs.

In the next step, to save the update command, we use the following structure:

```PHP
$Job1->Table(TABLE_NAME)->SELECT(COLUMN_NAME)->To(NEW_VALUE)->Who?(UserObject);
```

In the ``Table`` method, enter the name of the table you intend to work with its rows. For the ``Select`` input, enter the name of the column you intend to edit the data of (in the example above, amount).

Now, define what the new input should be with the ``To`` method. Finally, using the ``Who`` method, specify which user you are editing (use the variables defined in the previous step by the ``Find`` method).

Now, finally, we can evaluate the data before starting the commands, and in case of a problem, use the Exception method. 
> [!Note]
> that the input for this method must definitely be the ``ExceptionJob`` class.

After the complete configuration of the Job, the commands are executed in the defined order using the ``Start`` command.

# Library Developers Guide :fist_right:  :fist_left:

## How to make a new data types

Data types are created as object-oriented, this helps us to set specific features for each of the data types.
The folder for data types is in ``/src/types``.

**Rules:**

1. The file name is equal to the class name.
2. use the namespace ``Azad\Database\Types``
3. Inheriting from Init

Class components are divided into two sets of properties and methods.

### Properties

``$SqlType`` : A required properties that needs to be defined as public.
The value of these properties is sent to sql as a data type.
BIGInt example:

```php
<?php
namespace Azad\Database\Types;
class BigINT extends Init {
    public $SqlType = "BIGINT";
}
# CREATE TABLE table_name ( column [$SqlType] );
```

``$Primary`` : A boolean value، if true is defined، this column is automatically treated as primary key
ID example:

```php
<?php
namespace Azad\Database\Types;
class ID extends Init {
    public $SqlType = "BIGINT";
    public $Primary = true;
    public function AddToQueryTable () {
        return "AUTO_INCREMENT";
    }
}
```

### Methods

```php
AddToQueryTable ()
```

Adds a new value in SQL after the data type
CreatedAt example:

```php
<?php
namespace Azad\Database\Types;
class CreatedAt extends Init {
    public $SqlType = "timestamp";
    public function AddToQueryTable () {
        return "DEFAULT CURRENT_TIMESTAMP";
    }
}
# CREATE TABLE table_name ( column [$SqlType] [AddToQueryTable ()] );
```

-------------------

```php
InsertMe()
```

After the user uses Insert for the first, this value is considered for the column.

```php
UpdateMe()
```

After the user updates **one of their columns**, the column defined by this method changes to the output of this method.

Random example:

```php
<?php
namespace Azad\Database\Types;
class Random extends Init {
    public $SqlType = "INT";
    public function InsertMe() {
        return 12345;
    }
    public function UpdateMe() {
        return rand(1,100);
    }
}
```

-------------------

```php
Set($value)
```

After the user intends to store a data, the data is sent to this method and its output is replaced as new data.

``$value`` : The value that is being stored in the database

AutoLess example:

```php
<?php
namespace Azad\Database\Types;
class AutoLess extends Init {
    public $SqlType = "INT";
    public function InsertMe() {
        return 9999;
    }
    public function Set($value) {
        return $value - 1;
    }
}
```

-------------------

```php
Get($value)
```

After the programmer intended to get his data from the table column, the table column data was first sent to this method and its output was set as output data.

``$value`` : The value that is being stored in the database

ArrayData example:

```php
<?php
namespace Azad\Database\Types;
class ArrayData extends Init {
    public $SqlType = "JSON";
    public function Set($value) {
        return json_encode($value);
    }
    public function Get($value) {
        return json_decode($value,1);
    }
}
```
