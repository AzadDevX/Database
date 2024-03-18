<?php
// get 64

enum Status {
    case active;
    case deactive;
}

var_dump(Status::active->name);