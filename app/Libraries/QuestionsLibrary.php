<?php namespace App\Libraries;

use App\Models\UsersCurrentQuizStatsModel;

class QuestionsLibrary {

    public function getQuestion($table,$question_id) {  //zobrazi otazku
        $db = \Config\Database::connect();  
        $builder = $db->table($table)->where('id_q',$question_id);
        $query = $builder->get();
        
        foreach ($query->getResult() as $row) {
            echo $row->question;
        }
    }

    public function getRandomQuestionId() { //vrati nam random otazku
        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->where('id',session()->get('id_current_quiz_user'))->select('quiz_started'); //musim nahradit session()->get()
        $query = $builder->get();
        foreach($query->getResult() as $row) {

            if ($row->quiz_started == 0) { //kviz jeste nezacal (vygenerujeme nahodne otazky)
                $array_of_random_questions = $this->getRandomArrayOfQuestions(5); //tahle funkce nam vygeneruje pole random otazek podle jejich id

                $data =[
                    'id' => session()->get('id_current_quiz_user'),
                    'id_user' => session()->get('id'), //tady bude id uzivatele (musim nahradit session()->get('id'))
                    'id_random_questions' => implode(",", $array_of_random_questions), //ulozi random otazky ve formatu "1,4,3" atd.. a ulozi do datbaze
                    'current_question' => $array_of_random_questions[0], //ulozim current_question jako uplne prvni bvygenerovou otazku (od tehle otazku budu zacinat kviz)
                    'index_of_questions' => 0, //zaciname na nulove pozici v poli
                    'quiz_started' => 1,
                ];
                $usersCurrentQuizModel->save($data);

                return $array_of_random_questions;
            }

            if ($row->quiz_started == 1) {//kviz jiz zacal (nemuzu uz generovat nove otazky) -> musim zobrazi ty jiz vygenerovane otazky z databze
                $array_of_random_questions = $this->convertDataFromSqlStringToArray();
                return $array_of_random_questions;
            }
        }
    }

    public function getCurrentQuestionId() { //vrati id konkretni otazky (zobrazi se uzivateli)
        /*$usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $current_question_id = $usersCurrentQuizModel->findColumn('current_question'); //vrati mi konkretni id otazky u ktere jsem*/
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('current_question')->where('id',session()->get('id_current_quiz_user'));  //zase musim upravit na session()->get('id')
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            return $row->current_question;
        }

    }

    public function moveToAnotherQuestion() {  //vrati index konkretni otazky (index jak jsou ty otazky ulozene v poli) -> a zvysi o jedno
        /*$usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $index_of_question = $usersCurrentQuizModel->findColumn('index_of_questions'); //vrati mi konkretni id otazky u ktere jsem*/
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('index_of_questions')->where('id',session()->get('id_current_quiz_user'));  //zase musim upravit na session()->get('id')
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            $index_of_question = $row->index_of_questions;
        }
        $index_of_question++;
      
        $array_of_random_questions =  array();
        $array_of_random_questions = $this->convertDataFromSqlStringToArray(); //to tehle promenne se nahraji hodnoty pole ve ktere jsou ulozeny id otazek

        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $data =[
            'id' => session()->get('id_current_quiz_user'),
            'id_user' => session()->get('id'), //je to pevne nastavene musim to nahradit 'id' => session()->get('id')
            'current_question' => $array_of_random_questions[$index_of_question], //ulozim current_question jako uplne prvni bvygenerovou otazku (od tehle otazku budu zacinat kviz)
            'index_of_questions' => $index_of_question,
        ];
        $usersCurrentQuizModel->save($data);
       
    }


    public function convertDataFromSqlStringToArray() {
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('*')->where('id',session()->get('id_current_quiz_user'));  //musim nahradit session()->get('id_quiz...')
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            $values = $row->id_random_questions;
        }
        $array_of_random_questions = explode(",",$values);
        return $array_of_random_questions;
    }

    public function getRandomArrayOfQuestions($count) {
        $db = \Config\Database::connect(); 
        $builder = $db->table('questions')->orderBy('id_q', 'RANDOM')->limit($count);
        $query = $builder->get();
        $array_of_random_questions = array();
        $i=0;
        foreach( $query->getResult() as $row) {
            //return $row->id_q;  //otazku kterou vraci 
            $array_of_random_questions[$i] = $row->id_q;
            $i++;
        }
        return $array_of_random_questions;
    }

    public function showUserAnswersResults() {
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('current_question')->where('id',session()->get('id_current_quiz_user'));  //nahradit session()->get('id_quiz')
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            if($row->current_question == NULL){
                return "quiz_finished";
            }
        }

        return "quiz_still_run";
    }

    public function getCurrentIndexOfQuestion($quiz_id,$user_id){
        $db = \Config\Database::connect(); 
        $builder = $db->table('users_current_quiz_stats')->select('index_of_questions')->where('id',$quiz_id)->where('id_user',$user_id);
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            return $row->index_of_questions + 1;  //musim pricist 1 (protoze v databazi mam ulozene index pole a zacinaji od nuly)
        }

    }

    public function getTotalCountOfWholeQuizQuestions() {
        $total = $this->convertDataFromSqlStringToArray();
        return sizeof($total);
    }
    

}
 



