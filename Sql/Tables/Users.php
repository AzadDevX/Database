<?php
class Users extends Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("user_id")->Type(Azad\Database\Types\UserID::class)->Size(255);
        $this->Name("first_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("salary")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Encrypter("Base64");
        $this->Save ();
    }
}
