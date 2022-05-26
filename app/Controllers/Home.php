<?php

namespace App\Controllers;

use App\Libraries\MyCustomLibrary;
use App\Models\UserQuestionsStatsModel;
use App\Models\UserStatsModel;

class Home extends BaseController
{
    public function index()
    {
        echo view("templates/header");
        echo view('welcome_message');
    }

}
