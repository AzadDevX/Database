<?php

namespace Azad\Conditions;

class Exception extends \Exception {
    public $Debug;
    public function __construct($message) {
        $this->Debug = $message;
    }
}

class Result {
    protected static $IFResult,$OldResut,$Logical;
}

class Conditions extends Result {
    public $Arr,$Value,$Key,$Method,$CV;
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
        $this->Key = $key;
        $this->Value = $this->Arr[$key];
        return $this;
    }
    public function EqualTo($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        parent::$IFResult = ($this->Value == $x);
        $this->AndOr();
        return $this;
    }
    public function ISNot($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        parent::$IFResult = ($this->Value != $x);
        return $this;
    }
    public function LessThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        parent::$IFResult = ($number > $this->Value);
        return $this;
    }
    public function MoreThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        parent::$IFResult = ($number < $this->Value);
        return $this;
    }
    public function LessOrEqualThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        parent::$IFResult = ($number >= $this->Value);
        return $this;
    }
    public function MoreOrEqualThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        parent::$IFResult = ($number <= $this->Value);
        return $this;
    }
    public function Between ($x , $y) {
        $this->Method = __FUNCTION__;
        $this->CV = [$x,$y];
        parent::$IFResult = ($x <= $this->Value && $y >= $this->Value);
        return $this;
    }
    public function Have ($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if (is_array($this->Value)) {
            parent::$IFResult = (in_array($x,$this->Value));
        } elseif (is_string($this->Value)) {
            parent::$IFResult = (strpos($this->Value, $x) !== false);
        }
        return $this;
    }
    public function NotHave ($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if (is_array($this->Value)) {
            parent::$IFResult = (!in_array($x,$this->Value));
        } elseif (is_string($this->Value)) {
            parent::$IFResult = (!strpos($this->Value, $x) !== false);
        }
        return $this;
    }
    public function IN (array $x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        parent::$IFResult = (in_array($this->Value,$x));
        return $this;
    }
    public function NotIN (array $x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
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
            if (parent::$IFResult == false) {
                $Value = (is_array($this->Value))?json_encode($this->Value):$this->Value;
                $CV = (is_array($this->CV))?json_encode($this->CV):$this->CV;
                //The amount of USD  300, .
                throw new Exception("The value of [".$this->Key."] is equal to ".$Value." - but you have defined (".$CV.") in the ".$this->Method);
            }
            return $Data;
        }
        return $this;
    }
    public function __destruct() {
    }
}

