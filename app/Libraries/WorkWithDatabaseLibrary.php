<?php namespace App\Libraries;

use App\Models\UsersCurrentQuizStatsModel;
use App\Models\UserStatsModel;

class WorkWithDatabaseLibrary {
    
    public function generateRandomIdOfQuizToUser($table,$min,$max) {

        $db = \Config\Database::connect(); 
        $builder = $db->table($table)->select('*');
        $query = $builder->get();
        $array_of_id = array();
        $i=0;

        foreach($query->getResult() as $row) {
            $array_of_id[$i] = $row->id;
            $i++;
        }
        
        $random = rand($min,$max);
        $num = 0;

        for($i=$num;$i<sizeof($array_of_id);$i++){
            while($random == $array_of_id[$i]){
                $num =0;
                $i=0;
                $random = rand($min,$max);
            }
            
        }

        return $random; //vraci konkretni jedinecne cislo (id meho quizu)

    }

    public function getIdOfUserQuiz($userId) {
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->where('id_user',$userId);
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            return $row->id;
        }
    }

    public function IfUserLoginForFirstTime($id_user) {

        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('id_user');
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            if($row->id_user == $id_user) {  //vrati false (uzivatel se jiz prihlasil) -> ma jiz vytvoreny quiz ID pro svuj ucet
                return false;
            }
        }

        return true; 
    }

    public function pointsIncrease($stats) {
        $userStats = new UserStatsModel();
        $data = $userStats->where('id_user',session()->get('id'))->first(); //where('current_user_quiz_stats_id',session()->get('id_current_quiz_user'))

        if ($stats == "ok") {
           $points = $data["correctAnswer"];
           $points++;

           $statsData = [
               'id' => $data["id"],
               'correctAnswer' => $points,
           ];
        }

        if ($stats == "bad") {
           $points = $data["badAnswer"];
           $points++;

           $statsData = [
                'id' => $data["id"],
                'badAnswer' => $points,
            ];
        }

        $userStats->save($statsData);
        
    }

    public function createEmptyUserStatsAnswers(){
        $userStats = new UserStatsModel();
        $data = $userStats->where('id_user',session()->get('id'))->first(); //where('current_user_quiz_stats_id',session()->get('id_current_quiz_user'))

        $dataStats = [
            'id' => $data["id"],
            'correctAnswer' => 0,
            'badAnswer' => 0,
        ];
        $userStats->save($dataStats);
    }

    public function getPoints($typeOfPoints) {
        $userStats = new UserStatsModel();
        $data = $userStats->where('id_user',session()->get('id'))->first(); //where('current_user_quiz_stats_id',session()->get('id_current_quiz_user'))
        if($typeOfPoints == "correct") {
            return $data["correctAnswer"];
        }
        if($typeOfPoints == "bad") {
            return $data["badAnswer"];
        }


    }
}