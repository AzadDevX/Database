<?php

namespace MyProject\Encrypters;
class Base64 extends \Azad\Database\Magic\Encrypter {
    public static function Encrypt($Data) {
        return base64_encode($Data);
    }
    public static function Decrypt($Data) {
        return base64_decode($Data);
    }
}