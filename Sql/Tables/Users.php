<?php
class Users extends Azad\Database\MakeTable {
    public function __construct() {
        $this->Name("ID")->Type(Azad\DataType\Integer::class)->Size(255);
        $this->Name("first_name")->Type(Azad\DataType\Varchar::class)->Size(255);
        $this->Name("last_name")->Type(Azad\DataType\Varchar::class)->Size(255);
        $this->Save ();
    }
}
