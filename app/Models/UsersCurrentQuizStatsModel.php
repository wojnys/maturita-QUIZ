<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersCurrentQuizStatsModel extends Model {
    protected $table      = 'users_current_quiz_stats';
    //protected $primaryKey = 'id_user';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'id_user',
        'current_question',
        'id_random_questions',
        'index_of_questions',
        'currentOptionA',
        'currentOptionB',
        'currentOptionC',
        'currentOptionD',
        'lockAnswer',
        'quiz_started',
    ];
}

