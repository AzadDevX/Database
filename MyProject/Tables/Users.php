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
    }
    public static function Wallet ($args) {
        $Wallet = self::Table("Wallet")->Select("*");
        $Wallet = $Wallet->WHERE("user_id",$args[0]);
        return($Wallet->FirstRow());
    }
}
