<?php
namespace MyProject\Tables;
class Wallet extends \Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("user_id")->Type(\Azad\Database\Types\UserID::class)->Size(255)->Foreign("Users","user_id");
        $this->Name("USD")->Type(\Azad\Database\Types\Integer::class)->Size(255)->Default(0);
        $this->Name("IRT")->Type(\Azad\Database\Types\Integer::class)->Size(255)->Default(0);
        $this->Name("BTC")->Type(\Azad\Database\Types\Integer::class)->Size(255)->Default(0);
        $this->Save ();
    }
}
