<?php

namespace Azad\Conditions;

class Conditions {
    public $Value;
    public function __construct($Value) {
        $this->Value = $Value;
    }
    public function EqualTo($x) {
        return ($this->Value == $x) ? $this : false;
    }
    public function ISNot($x) {
        return ($this->Value != $x) ? $this : false;
    }
    public function Date() {
        return new \Azad\Date\Date($this->Value);
    }
    public function Percentage($p) {
        return new Conditions(($p * $this->Value) / 100);
    }
    public function LessThan ($number) {
        return ($number > $this->Value) ? $this : false;
    }
    public function MoreThan ($number) {
        return ($number < $this->Value) ? $this : false;
    }
    public function LessOrEqualThan ($number) {
        return ($number >= $this->Value) ? $this : false;
    }
    public function MoreOrEqualThan ($number) {
        return ($number <= $this->Value) ? $this : false;
    }
    public function Between ($x , $y) {
        return ($x <= $this->Value && $y >= $this->Value) ? $this : false;
    }
    public function Have ($x) {
        if (is_array($this->Value)) {
            return (in_array($x,$this->Value)) ? $this : false;
        } elseif (is_string($this->Value)) {
            return (strpos($this->Value, $x) !== false) ? $this : false;
        }
    }
    public function NotHave ($x) {
        if (is_array($this->Value)) {
            return (!in_array($x,$this->Value)) ? $this : false;
        } elseif (is_string($this->Value)) {
            return (!strpos($this->Value, $x) !== false) ? $this : false;
        }
    }
    public function IN (array $x) {
        return (in_array($this->Value,$x)) ? $this : false;
    }
    public function NotIN (array $x) {
        return (!in_array($this->Value,$x)) ? $this : false;
    }
}

