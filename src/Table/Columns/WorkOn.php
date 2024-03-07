<?php

namespace Azad\Database\Table\Columns;

class WorkOn extends Get {
    private $Value;
    private $Key;
    public function __construct($Value,$is_key=true) {
        if ($is_key == true) {
            $this->Key = $Value;
            $this->Value = $this->Get()[0][$Value];
        } else {
            $this->Value = $Value;
        }
    }
    public function Tool ($Tool) {
        $Tool = '\\Azad\\Database\\Functional\\'.$Tool;
        if(!class_exists($Tool)) {
            throw new \Azad\Database\Exception\Load("Functional [$Tool] does not exist");
        }
        return new $Tool($this->Value,$this);
    }
    public function Result () {
        return $this->Value;
    }

}

?>