<?php

class Names extends Azad\Database\Magick\Rebuilder {
    public static function Rebuild ($Data) {
        return strtolower($Data);
    }
}