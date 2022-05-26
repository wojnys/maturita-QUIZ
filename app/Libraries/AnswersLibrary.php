<?php namespace App\Libraries;

class AnswersLibrary {

    public function checkIfAnswerIsCorrect($myAnswer,$question_id){  //kontroluje jeslti je odpoved spravna neba spatna
        $db = \Config\Database::connect();  
        $builder = $db->table('options')->where('id_question',$question_id);
        $query = $builder->get();
    
        foreach($query->getResult() as $row){
            if($row->correctOpt == $myAnswer) {
                return true;  //zpravna odpoved
            }
        }
        return false;  //spatna odpoved
    }

    public function getLockVariable($id_current_quiz) {
        $db = \Config\Database::connect();  
        $builder = $db->table('users_current_quiz_stats')->select('lockAnswer')->where('id',$id_current_quiz);
        $query = $builder->get();
        foreach($query->getResult() as $row) {
            return $row->lockAnswer;
        }
    }

}