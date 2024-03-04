<?php

class IncreaseSalary extends Azad\Database\Magick\Plugin {
    private $Database,$Data;
    public function __construct ($Database,$Data) {
        $this->Database = $Database;
        $this->Data = $Data;
    }
    public function Increase ($percentage) {
        $Users = $this->Database->Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("ID",$this->Data["ID"]);
        $NewSalary = $User->WorkOn("salary")->
            Tool("Percentage")
                -> Append($percentage)
            ->Close()
        ->Result();
        $User->Manage()->Update($NewSalary,"salary");
    }
}