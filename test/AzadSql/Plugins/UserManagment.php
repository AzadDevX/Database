<?php

# AzadSql/Plugins/UserManagment.php

namespace AzadSql\Plugins;
class UserManagment extends \Azad\Database\Magic\Plugin {
    private $Database,$Data;
    public function __construct ($Database,$Data) {
        $this->Database = $Database;
        $this->Data = $Data;
    }
    public function ChangeFirstName ($new_first_name) {
        $Users = $this->Database->Table("Users");
        $Users = $Users->Select("*");
        $User = $Users->WHERE("user_id",$this->Data);
        $User->Manage()->Update($new_first_name,"first_name");
    }
}

?>