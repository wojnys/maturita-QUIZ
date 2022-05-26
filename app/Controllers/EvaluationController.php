<?php

namespace App\Controllers;

use App\Libraries\MyCustomLibrary;
use App\Models\UserQuestionsStatsModel;
use App\Models\UserStatsModel;

class EvaluationController extends BaseController
{
    public function index()
    {
        echo view("templates/header");
        echo view('evaluation');
    }

}