<?php

namespace Azad\Database\Table;

class Init extends \Azad\Database\Connect {
    public $Insert;
    protected static $InsertSetting=[];
    public function __construct($Table_Name) {
        parent::$TableData['table_name'] = $Table_Name;
    }
    public function Select (...$Column) {
        parent::$TableData['column_name'] = $Column;
        parent::$Query = \Azad\Database\Query::SelectQuery(parent::$TableData);
        return new Columns\Init();
    }
    public function Insert ($if_not_exists=true) {
        self::$InsertSetting["if_not_exists"] = $if_not_exists;
        return new Insert();
    }
}