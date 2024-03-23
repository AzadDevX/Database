<?php

namespace Azad\Database\Types;

/**
 * Set (value)
 * Get (value)
 * is_valid (value) : boolean
 * InsertMe (value)
 * UpdateMe (value)
 * AddToQueryTable () : string
 * $Primary : boolean
 * $SqlType : string
 */
class Init {
    public $data;
    public $Primary = false;
    public function __construct($data="") {
        $this->data = $data;
    }
}