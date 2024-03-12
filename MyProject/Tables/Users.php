<?php
namespace MyProject\Tables;
class Users extends \Azad\Database\Table\Make {
    public $Unique = ["first_name","last_name"];

    public function __construct() {
        $this->Name("user_id")->Type(\Azad\Database\Types\ID::class)->Size(255);
        $this->Name("first_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("last_name")->Type(\Azad\Database\Types\Varchar::class)->Size(255)->Rebuilder("Names");
        $this->Name("address")->Type(\Azad\Database\Types\ArrayData::class)->Rebuilder("Names")->Encrypter("Base64");
        $this->Name("created_at")->Type(\Azad\Database\Types\CreatedAt::class);
        $this->Name("updated_time")->Type(\Azad\Database\Types\UpdateAt::class);
        $this->Save ();
        $this->IndexCorrelation();
    }
    public static function Wallet () {
        return self::Correlation("user_id","Wallet","user_id")[0];
    }
    public static function Transactions () {
        return self::Correlation("user_id","Transactions","user_id");
    }
}
