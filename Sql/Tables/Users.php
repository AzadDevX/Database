<?php
class Users extends Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("user_id")->Type(Azad\Database\Types\ID::class)->Size(255);
        $this->Name("chat_id")->Type(Azad\Database\Types\BigINT::class)->Size(255);
        $this->Name("first_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("salary")->Type(Azad\Database\Types\Varchar::class)->Size(255)->Encrypter("Base64");
        $this->Name("created_at")->Type(Azad\Database\Types\CreatedAt::class);
        $this->Name("updated_time")->Type(Azad\Database\Types\UpdateAt::class);
        $this->Name("Random")->Type(Azad\Database\Types\Random::class);
        $this->Name("Random2")->Type(Azad\Database\Types\Random::class);
        $this->Name("OneLess")->Type(Azad\Database\Types\AutoLess::class);
        $this->Save ();
    }
}
