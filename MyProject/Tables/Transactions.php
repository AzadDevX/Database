<?php
namespace MyProject\Tables;
class Transactions extends \Azad\Database\Table\Make {
    public function __construct() {
        $this->Name("id")->Type(\Azad\Database\Types\ID::class)->Size(255);
        $this->Name("user_id")->Type(\Azad\Database\Types\BigINT::class)->Size(255)->Foreign("Users","user_id");
        $this->Name("amount")->Type(\Azad\Database\Types\Integer::class)->Size(255)->Default(0);
        $this->Save ();
        $this->IndexCorrelation();
    }
    public static function UserData () {
        return self::Correlation("user_id","Users","user_id")[0] ?? false;
    }
}
