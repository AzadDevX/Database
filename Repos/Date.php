<?php

namespace Azad\Date;
class MakeTime {
    public static function Now () {
        return microtime(true);
    }
    public static function Secound ( $Time ) {
        return $Time;
    }
    public static function Minutes ( $Time ) {
        return $Time * 60;
    }
    public static function Hours ( $Time ) {
        return ($Time * 60) * 60;
    }
    public static function Day ( $Time ) {
        return (($Time * 60) * 60) * 24;
    }
    public static function Month ( $Time ) {
        return ((($Time * 60) * 60) * 24) * 30;
    }
    public static function Year ( $Time ) {
        return (((($Time * 60) * 60) * 24) * 30) * 12;
    }
}

class Date {
    public $Passed,$NotPassed,$Date;
    public function __construct($Date) {
        $this->Passed = new PassedDate($Date);
        $this->NotPassed = new DidNotPassDate($Date);
        $this->Date = $Date;
    }
    public function Between ( $from , $to ) {
        return ($this->Date > $from && $this->Date < $to);
    }
}

class PassedDate {
    private $Date;
    public function __construct($Date) { $this->Date = $Date; }
    public function Least ($Time) {
        return $this->Difference () >= $Time;
    }
    public function NoMoreThan ($Time) {
        return $this->Difference () <= $Time;
    }
    public function Difference () {
        return MakeTime::Now() - $this->Date;
    }
}

class DidNotPassDate {
    private $Date;
    public function __construct($Date) { $this->Date = $Date; }
    public function LessThan ($Time) {
        return $this->Difference () < $Time;
    }
    public function MoreThan ($Time) {
        return $this->Difference () > $Time;
    }
    public function Difference () {
        return $this->Date - MakeTime::Now();
    }
}
