<?php

namespace MyProject\Plugins;
class IncreaseSalary extends \Azad\Database\Magic\Plugin {
    public function Increase ($percentage) {
        $Users = self::Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("user_id",$this->Data["user_id"]);
        $NewSalary = $User->WorkOn("salary")->
            Tool("Percentage")
                -> Append($percentage)
            ->Close()
        ->Result();
        $User->Manage()->Update($NewSalary,"salary");
    }
    public function ChangeName ($new_first_name) {
        $UserManagment = $this->IncludePlugin("UserManagment",$this->Data);
        $UserManagment->ChangeFirstName ($new_first_name);
        echo "Name was changed!".PHP_EOL;
    }
}