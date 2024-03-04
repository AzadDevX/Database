<?php
class Users extends Azad\Database\Table\Make {
    public $PRIMARY_KEY = "ID";
    public function __construct() {
        $this->Name("ID")->Type(Azad\Database\Types\Integer::class)->Size(255);
        $this->Name("first_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("salary")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Encrypter("Base64");
        $this->Save ();
    }
}
