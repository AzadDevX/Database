<?php

namespace Azad\Database\Databases;

abstract class Database {
    public $Database,$LastID,$affected_rows,$Result;
    abstract function __construct();
    abstract function Connect($config);
    abstract function Run($query);
    abstract function EscapeString($string);
    abstract function Fetch ($data);
}