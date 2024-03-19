<?php

namespace Azad\Database\Exceptions;

class Load extends \Exception {
    public function __construct($message = "", $code = 0, $load = null) {
        $this->message = "LoadError: ".$message." Loading -> ".$load;
        $this->code = $code;
    }
}

enum LoadCode {
    case Normalizer = 601;
    case Plugin = 602;
    case Encrypeter = 603;
    case Config = 604;
}

?>