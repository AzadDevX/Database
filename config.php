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
        $this->Project['directory'] = "MyProject";
        $this->Project['name'] = "MyProject";
        if (!file_exists($this->Project['directory'])) { mkdir($this->Project['directory']); }
        # -------- Table config
        $this->Table['prefix'] = "mp";
    }
}