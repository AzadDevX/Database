![AzadSqlBanner](https://github.com/AzadDevX/Database/assets/158297225/23f131f0-c061-47e3-bee9-24efae6c0e7e)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

[![GitHub issues](https://img.shields.io/github/issues/AzadDevX/Database.svg)](https://GitHub.com/AzadDevX/Database/issues/)
[![GitHub version](https://badge.fury.io/gh/AzadDevX%2FDatabase.svg)](https://github.com/AzadDevX/Database)

[![packagist](https://img.shields.io/packagist/v/AzadDevX/Database)](https://packagist.org/packages/AzadDevX/Database)
[![Github all releases](https://img.shields.io/github/downloads/AzadDevX/Database/total.svg)](https://GitHub.com/AzadDevX/Database/releases/)
[![MIT license](https://img.shields.io/badge/License-MIT-blue.svg)](https://lbesson.mit-license.org/)

# 1. Introduction

Code readability is one of the most important components of teamwork. Basic coding should be readable and understandable so that other. developers (teammates or open source) have the ability to understand faster and as a result develop faster.

Azad-Sql library has certain rules and regulations so that different projects can be written in the same way. Also, using the object-oriented method helps other members to master different parts of the project and develop without disturbing the main flow of the code.

This project is open-source and professional developers are expected to cooperate in the development of this project and grow this library together!

# 2. how does it work
AzadSql library uses Mysqli PHP library to categorize the commands and send them to Query after applying the changes, separate the data, organize it and display it in the output.

using this library you don't need to encrypt the columns manually, you don't need to write repeated commands to apply changes before storing data and receiving them, and you don't even need to evaluate the data of several different columns separately and update after receiving the result; All these processes can be done automatically.

the ability of the library in the defined function (called Magic) can wonderfully summarize your index file and developers can process data on different parts of the columns.

AzadSql developers can also develop different data types and make them available for public use. All these things will help you to ensure that your index file is in absolute order and that team members can apply their method to different parts of the project with open access.

# 3. Basic rules
you must use the object-oriented method, the file name and class name must match, and you must use the namespace of the project name.
for example:
```php
namespace MyProject\Plugins;
```
Also, each of the Magic has specific rules in design and you need to follow the rules (it will be explained later)

# 4. Initial Setup

First, you need to install the library through Composer, to do so run the following command in commandline.

```
composer require azaddevx/database
```

To use the library, you need to use autoload.php.

```php
require 'vendor/autoload.php';
```

Load the Connect class to start using the library:

```php
$Database = new Azad\Database\Connect();
```

After loading the class, you need to create a **config class** and pass it to the library.
The configuration class consists of three main properties, namely Database, Project, and Table.
These properties are based on the array.

**Database** property values: ``host`` - ``username`` - ``password`` - ``port`` - ``name``

**Project** property values: ``directory`` - ``name``

**Table** property values: ``prefix``

Below is a sample of the configuration class:

```php
<?php
class MyConfig {
    public $Database;
    public $Project;
    public $Table;
    public function __construct() {
        # -------- Database config
        $this->Database['host'] = '127.0.0.1';
        $this->Database['username'] = 'root';
        $this->Database['password'] = '';
        $this->Database['port'] = '';
        $this->Database['name'] = 'AzadSql';
        # -------- Project config
        $this->Project['directory'] = "MyProject"; # Project folder path
        # Note: this folder is not created automatically.
        $this->Project['name'] = "MyProject"; # Project name (namespace must be set to this name)
        if (!file_exists($this->Project['directory'])) { mkdir($this->Project['directory']); }
        # -------- Table config
        $this->Table['prefix'] = "mp"; # table prefixes
    }
}
```
After creating the configuration class, connect it to the main class via the ``config`` method.
```php
$Sql->Config(MyConfig::class);
```

# 5. Project folder path

After configuring the project, folders are created in the introduced folder.

```php
    MyProject/
  
      Constants/ # Soon
    
      Encrypters/
  
      Exceptions/ # Soon
    
      Plugins/
  
      Rebuilders/
    
      Tables/
```

# 6. How to create a table
To do this, enter the project folder and create a php file in the Tables folder (`MyProject\Tables\`).
> [!TIP]
> The name you choose for the file is considered as the table name.

After creating the PHP file, you need to create a namespace. ``PROJECT-NAME\Tables``

```php
<?php
namespace MyProject\Tables;
```
After creating namespace, create a class **named with the file** and inherit from the ``\Azad\Database\Table\Make`` class. for example:
```php
class Users extends \Azad\Database\Table\Make {

}
```
In the example above, we are going to create a table with the name of ``Users``.
> [!TIP]
> If you set a value in the config file for ``prefix``, the table name will be set automatically prefix, **don't do it manually**

Now, we're going to set up the database columns through ``__construct``.
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
## Methods:
```php
Name(column_name)
```

``column_name`` : Set the column name with this method. First of all، you need to specify the column name otherwise you will encounter an error.
> [!CAUTION]
> **PHP Fatal error:  ``Uncaught Azad\Database\Table\Exception``: You need to specify the column name first.**

```php
Type(class_type)
```
``class_type``: Class of data type. Classes of data types are defined in the main project (``src/Types``)

Types:

> **ArrayData**: ``Storing an array in a table (based on JSON)``

> **AutoLess**: ``Custom Datatype Sample, for Data Handling Guide``

> **BigINT**: ``No need for explanation.!``

> **Boolean**: ``No need for explanation.!``

> **Decimal**: ``No need for explanation.!``

> **Floats**: ``No need for explanation.!``

> **CreatedAt**: ``When you insert a new record, it automatically saves the time of the record``

> **ID**: ``Automatically increments numbers and is chosen as the primary key``

> **Integer**: ``No need for explanation.!``

> **Random**: ``Custom Datatype Sample, for Data Handling Guide``

> **UpdateAt**: ``When you update your record, it saves the time of the last change``

> **Varchar**: ``No need for explanation.!``

> **timestamp**: ``No need for explanation.!``

> **Decimal**: ``No need for explanation.!``

> **Token**: ``Automatic Token Generation (Based on SHA1)``

> **UserID**: This column is set to Primary and BigINT.

> [!CAUTION]
> If the set data type does not exist, you will encounter such an error.
> 
> **PHP Fatal error:  ``Uncaught Azad\Database\Table\Exception``: The 'type' value entered is not valid**

```php
Size(size)
```
``size``: Data Type Size

```php
Rebuilder(rebuilder_name) # Set a Rebuilder for Column
```
``rebuilder_name`` ``(string)`` : Rebuilder Name (The Rebuilder description is in the Magic section.)


```php
Encrypter(encrypter_name) # Set a Encrypter for Column
```
``encrypter_name`` ``(string)`` : Encrypter Name (The Encrypter description is in the Magic section.)

```php
Foreign(table_name,column_name) # constraint is used to prevent actions that would destroy links between tables.
```
``table_name`` ``(string)`` : parent table

``column_name`` ``(string)`` : parent table column name

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

## Properties:
You can also make adjustments to the data table through Properties.

```php
$Unique = [Column names];
```

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
With this feature, you can place data from multiple tables in each other.

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
> 
```php
$Transactions = $Sql->Table("Transactions");
$Find = $Transactions->Select("*")->WHERE("user_id",2);
$Transactions_Data = $Find->LastRow()->Result; # <------
return $Transactions->UserData();
```

** Result **

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

** Result **

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

** Result **

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

** Result **

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

# 6. How to insert a data
After you have created your table, you will need to select your table, which is done using the ``Table`` method.
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
## Methods:

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

# 7. How to get a value
After you have saved your table to a variable (in the previous example ``$Users``), select your data using the ``WHERE`` method

```php
$User = $Users->Select("*");
$User = $User->WHERE("first_name","Mohammad")
            ->AND("last_name","azad");
```
## Methods:

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

After setting up the where، you can get your data list in two ways.

## First Solution:
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

## Second Solution:
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
            ->Key("USD")->Increase(50)
                ->Push();
} catch (Azad\Database\Conditions\Exception $E) {
    var_dump($E->getMessage());
}

```

### Methods:
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

### Conditional Methods:
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

### How to make new Rebuilder:

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

### How to make Plugin:
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

# Library Developers Guide :fist_right:  :fist_left:

## How to make a new data types
Data types are created as object-oriented, this helps us to set specific features for each of the data types.
The folder for data types is in ``/src/types``. 

**Rules**
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
