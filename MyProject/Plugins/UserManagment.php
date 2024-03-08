<?php

namespace MyProject\Plugins;
class UserManagment extends \Azad\Database\Magic\Plugin {
    public function ChangeFirstName ($new_first_name) {
        $Users = self::Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("user_id",$this->Data['user_id']);
        $User->Manage()->Update($new_first_name,"first_name");
    }
}

