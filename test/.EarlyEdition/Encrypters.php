<?php

namespace Azad\Encrypters {
    abstract class Encrypter {
        abstract static public function Encrypt($Data);
        abstract static public function Decrypt($Data);
    }
}
