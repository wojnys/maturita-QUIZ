<?php

namespace App\Models;

use CodeIgniter\Model;

class UserStatsModel extends Model {
    protected $table      = 'users_stats';
    protected $allowedFields = [
        'id',
        'id_user',
        'correctAnswer',
        'badAnswer',
        'current_user_quiz_stats_id',
       
    ];
}