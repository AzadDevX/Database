<?php

namespace Azad\Database\Conditions;

class Exception extends \Exception {
    public $Debug;
    public function __construct($message) {
        $this->Debug = $message;
    }
}