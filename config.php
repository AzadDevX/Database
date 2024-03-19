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
        $this->Project['directory'] = __DIR__."\MyProject";
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
        $this->System['Debug'] = true;
        # Before the final release, set this value to true.
        # Although it will keep the processor busy, it provides guidance for debugging!
    }
}