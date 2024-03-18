<?php


enum UserStatus : string {
    case Active;
}


// var_dump($data instanceof UserStatus);
if (!isset(UserStatus::cases()[0])) {
    echo "your enum class is not defined case";
} elseif (!isset(UserStatus::cases()[0]->value)) {
    echo "you must set type for your enum class";
} else {
    var_dump(gettype(UserStatus::cases()[0]->value));
    var_dump(enum_exists(UserStatus::class));
}
