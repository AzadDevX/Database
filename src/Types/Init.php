<?php

namespace Azad\Database\Types;


class Init {
    public $data;
    public $Primary = false;
    public function __construct($data="") {
        $this->data = $data;
    }
}