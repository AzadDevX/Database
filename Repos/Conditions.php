<?php

namespace Azad\Conditions;

class Result {
    protected static $IFResult;
    protected static $OldResut;
    protected static $Logical;
}

class Conditions extends Result {
    public $Arr,$Value;
    public $Return;

    public $ReturnToClass;
    public function __construct($Arr,$ReturnToClass=null) {
        $this->ReturnToClass = $ReturnToClass;
        $this->Arr = $Arr;
    }
    public function AndOr() {
        if (isset(parent::$OldResut)) {
            parent::$IFResult = (parent::$Logical == "and") ? parent::$IFResult && parent::$OldResut : parent::$IFResult || parent::$OldResut;
        }
    }
    public function IF ($key) {
        $this->Value = $this->Arr[$key];
        return $this;
    }
    public function EqualTo($x) {
        parent::$IFResult = ($this->Value == $x);
        $this->AndOr();
        return $this;
    }
    public function ISNot($x) {
        parent::$IFResult = ($this->Value != $x);
        return $this;
    }
    /*public function Date() {
        parent::$IFResult = new \Azad\Date\Date($this->Value);
        return $this;
    }*/
    public function Percentage($p) {
        return new Conditions(($p * $this->Value) / 100);
    }
    public function LessThan ($number) {
        parent::$IFResult = ($number > $this->Value);
        return $this;
    }
    public function MoreThan ($number) {
        parent::$IFResult = ($number < $this->Value);
        return $this;
    }
    public function LessOrEqualThan ($number) {
        parent::$IFResult = ($number >= $this->Value);
        return $this;
    }
    public function MoreOrEqualThan ($number) {
        parent::$IFResult = ($number <= $this->Value);
        return $this;
    }
    public function Between ($x , $y) {
        parent::$IFResult = ($x <= $this->Value && $y >= $this->Value);
        return $this;
    }
    public function Have ($x) {
        if (is_array($this->Value)) {
            parent::$IFResult = (in_array($x,$this->Value));
        } elseif (is_string($this->Value)) {
            parent::$IFResult = (strpos($this->Value, $x) !== false);
        }
        return $this;
    }
    public function NotHave ($x) {
        if (is_array($this->Value)) {
            parent::$IFResult = (!in_array($x,$this->Value));
        } elseif (is_string($this->Value)) {
            parent::$IFResult = (!strpos($this->Value, $x) !== false);
        }
        return $this;
    }
    public function IN (array $x) {
        parent::$IFResult = (in_array($this->Value,$x));
        return $this;
    }
    public function NotIN (array $x) {
        parent::$IFResult = (!in_array($this->Value,$x));
        return $this;
    }
    public function And ($key) {
        $this->Value = $this->Arr[$key];
        parent::$OldResut = parent::$IFResult;
        parent::$Logical = "and";
        return $this;
    }
    public function Or ($key) {
        $this->Value = $this->Arr[$key];
        parent::$OldResut = parent::$IFResult;
        parent::$Logical = "or";
        return $this;
    }
    public function End () {
        parent::$OldResut = null;
        if (isset($this->ReturnToClass)) {
            $Data = new $this->ReturnToClass();
            $Data->IFResult = parent::$IFResult;
            return $Data;
        }
        return parent::$IFResult;
    }
}

