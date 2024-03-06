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
