<?php

namespace Azad\Database\Conditions;

class Conditional extends Init {
    public $Arr,$Value,$Key,$Method,$CV,$Return,$ReturnToClass;
    private $SingleData,$ResultTo;
    public function __construct($Arr,$SingleData=true,$ResultTo) {
        $this->Arr = $Arr;
        $this->ResultTo = $ResultTo;
        $this->SingleData = $SingleData;
    }
    private function AndOr() {
        if (isset(parent::$OldResut)) {
            parent::$IFResult = (parent::$Logical == "and") ? parent::$IFResult && parent::$OldResut : parent::$IFResult || parent::$OldResut;
        }
    }
    public function IF ($key) {
        $this->Key = $key;
        if ($this->SingleData == true) {
            $this->Value = $this->Arr[$key];
        }
        return $this;
    }
    public function EqualTo($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value == $x);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                return $data[$this->Key] == $x;
            });
        }
        $this->AndOr();
        return $this;
    }
    public function ISNot($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value != $x);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                return $data[$this->Key] != $x;
            });
        }
        return $this;
    }
    public function LessThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value < $number);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($number) {
                return $data[$this->Key] < $number;
            });
        }
        return $this;
    }
    public function MoreThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value > $number);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($number) {
                return $data[$this->Key] > $number;
            });
        }
        return $this;
    }
    public function LessOrEqualThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value <= $number);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($number) {
                return $data[$this->Key] <= $number;
            });
        }
        return $this;
    }
    public function MoreOrEqualThan ($number) {
        $this->Method = __FUNCTION__;
        $this->CV = $number;
        if ($this->SingleData == true) {
            parent::$IFResult = ($this->Value >= $number);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($number) {
                return $data[$this->Key] >= $number;
            });
        }
        return $this;
    }
    public function Between ($x , $y) {
        $this->Method = __FUNCTION__;
        $this->CV = [$x,$y];
        if ($this->SingleData == true) {
            parent::$IFResult = ($x <= $this->Value && $y >= $this->Value);
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x,$y) {
                return ($x <= $data[$this->Key] && $y >= $data[$this->Key]);
            });
        }
        return $this;
    }
    public function Have ($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            if (is_array($this->Value)) {
                parent::$IFResult = (in_array($x,$this->Value));
            } elseif (is_string($this->Value)) {
                parent::$IFResult = (strpos($this->Value, $x) !== false);
            };
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                if (is_array($data[$this->Key])) {
                    return (in_array($x,$data[$this->Key]));
                } elseif (is_string($data[$this->Key])) {
                    return (strpos($data[$this->Key], $x) !== false);
                }
            });
        }
        return $this;
    }
    public function NotHave ($x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            if (is_array($this->Value)) {
                parent::$IFResult = (!in_array($x,$this->Value));
            } elseif (is_string($this->Value)) {
                parent::$IFResult = (!strpos($this->Value, $x) !== false);
            };
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                if (is_array($data[$this->Key])) {
                    return (!in_array($x,$data[$this->Key]));
                } elseif (is_string($data[$this->Key])) {
                    return (!strpos($data[$this->Key], $x) !== false);
                }
            });
        }
        return $this;
    }
    public function IN (array $x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            parent::$IFResult = (in_array($this->Value,$x));
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                return (in_array($data[$this->Key],$x));
            });
        }
        return $this;
    }
    public function NotIN (array $x) {
        $this->Method = __FUNCTION__;
        $this->CV = $x;
        if ($this->SingleData == true) {
            parent::$IFResult = (!in_array($this->Value,$x));
        } else {
            $this->Arr = array_filter($this->Arr,function ($data) use ($x) {
                return (!in_array($data[$this->Key],$x));
            });
        }
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
        if ($this->SingleData == true) {
            if (parent::$IFResult == false) {
                $Value = (is_array($this->Value))?json_encode($this->Value):$this->Value;
                $CV = (is_array($this->CV))?json_encode($this->CV):$this->CV;
                $this->ResultTo->IFResult = false;
                throw new \Azad\Database\Exceptions\Conditional("The value of [".$this->Key."] is equal to ".$Value." - but you have defined (".$CV.") in the ".$this->Method);
            }
            $this->ResultTo->IFResult = true;
            return $this->ResultTo;
        }
        if ($this->SingleData == false) {
            $this->ResultTo->Data = $this->Arr ?? [];
        }
        return $this->ResultTo;
    }
}