<?php
class Users extends Azad\Database\MakeTable {
    public $PRIMARY_KEY = "ID";
    public function __construct() {
        $this->Name("ID")->Type(Azad\DataType\Integer::class)->Size(255);
        $this->Name("first_name")->Type(Azad\DataType\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(Azad\DataType\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("salary")->Type(Azad\DataType\Varchar::class)->Size(255)->Encrypter("Base64");
        $this->Save ();
    }
}
