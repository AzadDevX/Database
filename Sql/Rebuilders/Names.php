<?php

class Names extends Azad\Rebuilders\Rebuilder {
    public static function Rebuild ($Data) {
        return strtolower($Data);
    }
}