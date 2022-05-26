<?php namespace App\Libraries;

use App\Models\UsersCurrentQuizStatsModel;

class OptionsLibrary {

    public function generateCorrectOptions($table,$current_quesiton_id) {
        $randomNum = rand(0,3); //genruje  4 mozne vysledky (mam pouze 4 odpoevdi) -> 0,1,2 nebo 3

        $db = \Config\Database::connect();  

        /*vygeneruje spravnou odpoved*/ 
        $builder = $db->table($table)->where('id_question',$current_quesiton_id)->select('correctOpt');
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            $correctOption = $row->correctOpt; //ulozi spravnou odpoved
        }

        /*vygeneruje spatne odpovedi*/
        $builder = $db->table($table)->where('id_question !=',$current_quesiton_id)->orderBy('correctOpt','RANDOM')->limit(3); //vygeneruje 3 spatne odpovedi
        $query = $builder->get();
        $i=0;
        foreach($query->getResult() as $row) {
            $badOpion[$i] = $row->correctOpt; //ulozi do pole odpovedi (ulozi spatne odpovedi ktere patri jinym id_question)
            $i++;
        }

        /*ulozi odpovedi do databaze -> vzdy 1 dobra odpoved a 3 spatne odpovedi*/ 
        /*podle toho jake cislo se vygeneruje podle toho umisti odpovedi do databaze */
        $usersCurrentQuiz = new UsersCurrentQuizStatsModel();
        if($randomNum == 0){
            $data = [
                'id' => session()->get('id_current_quiz_user'),
               // 'id_user' => 1,   //musim zmenit na 'id' => session()->get('id')
                'currentOptionA' => $correctOption,
                'currentOptionB' => $badOpion[0],
                'currentOptionC' => $badOpion[1],
                'currentOptionD' => $badOpion[2],
            ];
        }

        if($randomNum == 1){
            $data = [
                'id' => session()->get('id_current_quiz_user'),
                //'id_user' => 1,   //musim zmenit na 'id' => session()->get('id')
                'currentOptionA' => $badOpion[0],
                'currentOptionB' => $correctOption,
                'currentOptionC' => $badOpion[1],
                'currentOptionD' => $badOpion[2],
            ];
        }

        if($randomNum == 2){
            $data = [
                'id' => session()->get('id_current_quiz_user'),
                //'id_user' => 1,   //musim zmenit na 'id' => session()->get('id')
                'currentOptionA' => $badOpion[0],
                'currentOptionB' => $badOpion[1],
                'currentOptionC' => $correctOption,
                'currentOptionD' => $badOpion[2],
            ];
        }

        if($randomNum == 3){
            $data = [
                'id' => session()->get('id_current_quiz_user'),
                //'id_user' => 1,   //musim zmenit na 'id' => session()->get('id')
                'currentOptionA' => $badOpion[0],
                'currentOptionB' => $badOpion[1],
                'currentOptionC' => $badOpion[2],
                'currentOptionD' => $correctOption,
            ];
        }

        $usersCurrentQuiz->save($data);
    }

    public function getCurrentCorrectOption($current_quesiton_id) {
        $db = \Config\Database::connect();  

        /*vygeneruje spravnou odpoved*/ 
        $builder = $db->table('options')->where('id_question',$current_quesiton_id)->select('correctOpt');
        $query = $builder->get();
        foreach($query->getResult() as $row){
            return $row->correctOpt;
        }

    }

    public function getOption($columnOption) {
        $db = \Config\Database::connect();  
        $builder = $db->table('users_current_quiz_stats')->select($columnOption)->where('id',session()->get('id_current_quiz_user'),);
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            return $row->$columnOption;
        }
    }


}