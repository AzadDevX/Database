<?php

namespace MyProject\Rebuilders;
class Names extends \Azad\Database\Magic\Rebuilder {
    public static function Rebuild ($Data) {
        return strtolower($Data);
    }
}