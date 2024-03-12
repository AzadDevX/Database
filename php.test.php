<?php

class Correlation {
    public $OriginColumn,$table_name,$column;
    public function Check() {
        var_dump($this->OriginColumn,$this->table_name,$this->column);
    }
}




class Test {
    public function Wallet () {
        $Correlation = new Correlation();
        $Correlation->OriginColumn = "user_id";
        $Correlation->table_name = "Users";
        $Correlation->column = "id";
        $Correlation->Check();
    }
}



$test = new Test();
$test->Wallet();
