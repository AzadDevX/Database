<?php

namespace AzadSql\Plugins;
class IncreaseSalary extends \Azad\Database\Magick\Plugin {
    private $Database,$Data;
    public function __construct ($Database,$Data) {
        $this->Database = $Database;
        $this->Data = $Data;
    }
    public function Increase ($percentage) {
        $Users = $this->Database->Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("chat_id",$this->Data["chat_id"]);
        $NewSalary = $User->WorkOn("salary")->
            Tool("Percentage")
                -> Append($percentage)
            ->Close()
        ->Result();
        $User->Manage()->Update($NewSalary,"salary");
    }
}