<?php

namespace Azad\Database\built_in;

class Exception extends \Exception {}

class Time {
    public $timestamp;
    public function __construct(int $timestamp) {
        $this->timestamp = $timestamp;
    }

    public function AddMinutes (int $minutes) {
        if ($minutes < 0) { throw new Exception("The entered value should not be less than zero."); }
        $this->timestamp += $minutes * 60;
        return $this;
    }
    public function AddHours (int $hours) {
        if ($hours < 0) { throw new Exception("The entered value should not be less than zero."); }
        $this->timestamp += ($hours * 60) * 60;
        return $this;
    }
    public function AddDays (int $Days) {
        if ($Days < 0) { throw new Exception("The entered value should not be less than zero."); }
        $this->timestamp += (($Days * 60) * 60) * 24;
        return $this;
    }
    public function AddMonths (int $months) {
        if ($months < 0) { throw new Exception("The entered value should not be less than zero."); }
        $this->timestamp += ((($months * 60) * 60) * 24) * 30;
        return $this;
    }
    public function AddYears (int $Years) {
        if ($Years < 0) { throw new Exception("The entered value should not be less than zero."); }
        $this->timestamp += (((($Years * 60) * 60) * 24) * 30) * 12;
        return $this;
    }
    /**
     * To calculate the remaining time until the time passed to the first parameter.
     * If the output is zero, it means that the specified time as the first parameter has passed.
     */
    public function LeftUntil (int $timestamp,object &$format=null) {
        $calc = $this->timestamp - $timestamp;
        if ($calc < 0) { return 0; }
        $format = $this->Calculate($calc);
        return $calc;
    }
    /**
     * It is used to calculate the amount of time that has passed relative to the time given to the first parameter.
     * If the output is zero, it means that the given time has not yet been reached
     */
    public function HowLongAgo (int $timestamp = null,object &$format=null) {
        $timestamp = $timestamp ?? time();
        $calc = $timestamp - $this->timestamp;
        if ($calc < 0) { return 0; }
        $format = $this->Calculate($calc);
        return $calc;
    }
    
    private function Calculate (int $secound) : object {
        $number = $secound;
        $years = floor($number / 31104000);
        $number = $number - (31104000 * $years);
        $months = floor($number / 2592000);
        $number = $number - (2592000 * $months);
        $days = floor($number / 86400);
        $number = $number - (86400 * $days);
        $hours = floor($number / 3600);
        $number = $number - (3600 * $hours);
        $minutes = floor($number / 60);
        $number = $number - (60 * $minutes);
        return Arrays::ToObject(['Years'=>$years,'Months'=>$months,'Days'=>$days,'Hours'=>$hours,'Minutes'=>$minutes,'Secounds'=>$number]);
    }
}