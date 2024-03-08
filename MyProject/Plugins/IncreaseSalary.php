<?php

namespace MyProject\Plugins;
class ChangeName extends \Azad\Database\Magic\Plugin {
    public function ChangeName ($new_first_name) {
        $UserManagment = $this->IncludePlugin("UserManagment",$this->Data);
        $UserManagment->ChangeFirstName ($new_first_name);
    }
}