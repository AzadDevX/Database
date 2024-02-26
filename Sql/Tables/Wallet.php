<?php

class Wallet extends Azad\Database\MakeTable {
    public function __construct() {
        $this->Name("ID")->Type(Azad\DataType\Integer::class)->Size(255);
        $this->Name("USD")->Type(Azad\DataType\Integer::class)->Size(255);
        $this->Name("IRT")->Type(Azad\DataType\Integer::class)->Size(255);
        $this->Save ();
    }
}
