<?php

class Wallet extends Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("ID")->Type(Azad\Database\Types\Integer::class)->Size(255);
        $this->Name("USD")->Type(Azad\Database\Types\Integer::class)->Size(255);
        $this->Name("IRT")->Type(Azad\Database\Types\Integer::class)->Size(255);
        $this->Save ();
    }
}
