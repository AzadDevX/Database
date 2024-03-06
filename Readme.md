![AzadSqlBanner](https://github.com/Azad1324/Sql/assets/158297225/23f131f0-c061-47e3-bee9-24efae6c0e7e)
# 1. Introduction

Code readability is one of the most important components of teamwork. Basic coding should be readable and understandable so that other developers (teammates or open source) have the ability to understand faster and as a result develop faster.

Azad-Sql library has certain rules and regulations so that different projects can be written in the same way. Also, using the object-oriented method helps other members to master different parts of the project and develop without disturbing the main flow of the code.

This project is open-source and professional developers are expected to cooperate in the development of this project and grow this library together!

# 2. how does it work
AzadSql library uses Mysqli PHP library to categorize the commands and send them to Query after applying the changes, separate the data, organize it and display it in the output.

using this library you don't need to encrypt the columns manually, you don't need to write repeated commands to apply changes before storing data and receiving them, and you don't even need to evaluate the data of several different columns separately and update after receiving the result; All these processes can be done automatically.

the ability of the library in the defined function (called Magick) can wonderfully summarize your index file and developers can process data on different parts of the columns.

AzadSql developers can also develop different data types and make them available for public use. All these things will help you to ensure that your index file is in absolute order and that team members can apply their method to different parts of the project with open access.

# 3. Basic rules
The name of your database is considered as the name of your project.
At the beginning, after the initial execution, a folder with the same name as the database name (or the project name) is created.
In this folder you can define plugins, rebuilders and encoders. To create any of these items, you must use the object-oriented method, the file name and class name must match, and you must use the namespace of the project path.
for example:
```php
namespace MyProject\Plugins;
```
Also, each of the Magick has specific rules in design and you need to follow the rules (it will be explained later)

# 4. Initial Setup

First, you need to install the library through Composer, to do so run the following command in commandline.

```
composer require azad1324/sql
```

To use the library, you need to use autoload.php.

```php
require 'vendor/autoload.php';
```

Load the Connect class to start using the library:

```php
$Database = new Azad\Database\Connect("AzadSql");
```
> [!NOTE]
> In the example above, the project name and database name is `AzadSql`.

# 5. Project Configuration

After successfully executing the code, a project root has created a folder named with the project name that contains these folders:

    AzadSql/
  
      Constants/
    
      Encrypters/
  
      Exceptions/
    
      Plugins/
  
      Rebuilders/
    
      Tables/
    
      .ASql.ini

The database configuration is located in the `.ASql.ini` file.
> [!CAUTION]
> The config file is made with permission 0600, but still requires security measures.

```INI
[Database]
    host = 127.0.0.1
    port =
    username = root
    password =
[Table]
    prefix =
[Project]
    name = AzadSql
```

Make changes to this file and then save.

# 6. How to create a table
To do this, enter the project folder and create a php file in the Tables folder (`AzadSql\Tables\`).
> [!TIP]
> The name you choose for the file is considered as the table name.

After creating the PHP file, you need to create a namespace. ``ProjectName\Tables``

```php
<?php
namespace AzadSql\Tables;
```
After creating namespace, create a class **named with the file** and inherit from the ``\Azad\Database\Table\Make`` class. for example:
```php
class Users extends \Azad\Database\Table\Make {

}
```
In the example above, we are going to create a table with the name of ``Users``.
> [!TIP]
> If you set a value in the config file for ``prefix``, the table name will be set automatically, **don't do it manually**

Now, we're going to set up the database columns through ``__construct``.
```php
<?php
namespace AzadSql\Tables;
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

> **AutoLess**: ``Custom Datatype Sample, for Data Handling Guide``

> **BigINT**: ``No need for explanation.!``

> **CreatedAt**: ``When you insert a new record, it automatically saves the time of the record``

> **ID**: ``Automatically increments numbers and is chosen as the primary key``

> **Integer**: ``No need for explanation.!``

> **Random**: ``Custom Datatype Sample, for Data Handling Guide``

> **UpdateAt**: ``When you update your record, it saves the time of the last change``

> **Varchar**: ``No need for explanation.!``

> **timestamp**: ``No need for explanation.!``

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
``rebuilder_name`` ``(string)`` : Rebuilder Name (The Rebuilder description is in the Magick section.)


```php
Encrypter(encrypter_name) # Set a Encrypter for Column
```
``encrypter_name`` ``(string)`` : Encrypter Name (The Encrypter description is in the Magick section.)

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
namespace AzadSql\Tables;
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

# 6. How to insert a data
After you have created your table, you will need to select your table, which is done using the ``Table`` method.
for example:
```php
$Database = new Azad\Database\Connect("AzadSql");
$Users = $Database->Table("Users");
```
Now, it is time to select a columns(!), of course, to insert a data, you don't need to select a column
```php
$Database = new Azad\Database\Connect("AzadSql");
$Users = $Database->Table("Users")->Select("*");
```
After selecting the columns(!), insert your data into the table using the ``Insert`` method
```php
$Database = new Azad\Database\Connect("AzadSql");
$Users = $Database->Table("Users")->Select("*");

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
$User = $Users->WHERE("first_name","Mohammad")
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
$User->Get ();
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
$User->FirstRow ();
```

This method get the first data found.

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
To update a column, use the ``Manage`` method after you have used ``Where``
```php
$User->Manage()
```
This method has other methods within itself.
> [!IMPORTANT]
> if the output of where is more than one value, you need to use the ``First()`` method.
> ```php
> $User->First()->Manage()
> ```
> Otherwise, use the Manage directly.
> ```php
> $User->Manage()
> ```

> [!NOTE]
> To clarify the description, we put the Manage method in another variable
```php
$UserManage = $User->Manage();
```
Now, to update the value of a column we have three methods:
## Methods:
```php
Update(new_value,column_name)
```

``new_value`` : Set a new value in this parameter

``column_name`` : The name of the column you want to update

**Example**

```php
$UserManage->Update("md","first_name")
           ->Update("az","last_name");
$User->FirstRow ();
```
Result:
```php
array(5) {
  ["user_id"]=>
  string(1) "1"
  ["first_name"]=>
  string(2) "md"
  ["last_name"]=>
  string(2) "az"
  ["created_at"]=>
  string(19) "2024-03-07 02:30:18"
  ["updated_time"]=>
  string(19) "2024-03-07 02:53:40"
}
```

```php
Increase(number,column_name)
```

``number`` : The number you want to **add to the previous value**. ``(value + number = new_value)``

``column_name`` : The name of the column you want to update

```php
Decrease(number,column_name)
```

``number`` : The number you want to **add to the previous value**. ``(number - value = new_value)``

``column_name`` : The name of the column you want to update
