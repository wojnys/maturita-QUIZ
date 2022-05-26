<?php

namespace App\Controllers;

class UserAccount extends BaseController
{
    public function logout()
    {
        echo view("templates/header");
        session()->destroy();
        echo view('/');
    }
}