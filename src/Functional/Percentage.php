<?php

namespace Azad\Database\Functional;

class Percentage extends Init {
    private $Value,$Class;
    public function __construct ($Value,$Class) {
        $this->Value = $Value;
        $this->Class = $Class;
    }
    public function Append ($Percentage) {
        $this->Value += ($Percentage * $this->Value) / 100;
        return $this;
    }
    public function Close () {
        return new $this->Class($this->Value,false);
    }
}