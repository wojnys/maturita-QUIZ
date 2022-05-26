<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table      = 'users';

    protected $allowedFields = [
        'name',
        'email',
        'created',
        'password',
        'current_user_quiz_stats_id',
       
    ];
}