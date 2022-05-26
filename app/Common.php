<?php


 /*
function readOptionB($table,$question_id) { //zobrazi option B
    $db = \Config\Database::connect();
    $builder = $db->table($table)->where('id_question',$question_id);
    $query = $builder->get();

    foreach ($query->getResult() as $row) {
        echo $row->optionB;
    }

}

function readOptionC($table,$question_id) { //zobrazi option C
    $db = \Config\Database::connect();
    $builder = $db->table($table)->where('id_question',$question_id);
    $query = $builder->get();

    foreach ($query->getResult() as $row) {
    echo $row->optionC;
    }

}

function readOptionD($table,$question_id) {  //zobrazi option D
    $db = \Config\Database::connect();
    $builder = $db->table($table)->where('id_question',$question_id);
    $query = $builder->get();

    foreach ($query->getResult() as $row) {
        echo $row->optionD;
    }

}


function checkIfAnswerIsCorrect($myAnswer,$id_question,$table){  //kontrola jesli byla ma odpoved spravna
    $db = \Config\Database::connect();   
    $builder = $db->table($table)->where('id',$id_question);
    $query = $builder->get();

    foreach ($query->getResult() as $row) {
        if($myAnswer == $row->correctAnswer) {
            return "ok";
        }
    }

    return "bad";

}

function readUserCurrentQuestionId($table,$user_id) {    //vrati konkretni otazku (konkretni id otazky) na ktere prave jsme (mame zobrazenou na monitoru)
    $db = \Config\Database::connect(); 
    $builder = $db->table($table)->where(['id'],$user_id);
    $query = $builder->get();

    foreach($query->getResult() as $row) {
        return $row->id_question;  //vrati konkretni id otazky
    }

}

function getAllQuestionCount($table) {
    $db = \Config\Database::connect(); 
    $totalQuestionsCount = $db->table($table)->countAllResults(); //tahle funkce mi vrati pocet kolik mam dohromady zaznamu v databazi (kolik mam dohromady otazek)
    return $totalQuestionsCount;
}


function getQuestionsStatus($id_user,$table,$tableOfPractise) {  //tuhle funkci volam v JS (vrati mi stav otazky) -> vrati bud ("ok" nebo "bad") -> jeslti jsem otazku uhodl nebo ne
    $db = \Config\Database::connect(); 
    $builder = $db->table($table)->where(['id'],$id_user);
    $query = $builder->get();
    $array = array();
    $numberQ = 1;
    $totalQuestions = getAllQuestionCount($tableOfPractise);
    foreach($query->getResult() as $row) {
        for($i=0;$i<$totalQuestions;$i++) {
            $universal_question = "status_question".$numberQ;
            $array[$i] = $row->$universal_question;
            $numberQ++;
        }
    }


    return $array;
}

//tahle funkce se nam stara o to jeslti je uz nejaka hodnota zapsana v status_question(cislo) abychom nemohli prepisovat odpovedi ktere jsme jiz zapsali
function getValueOfCurrentQuestionStatus($id_user,$table,$currentQuestion) {
    $db = \Config\Database::connect(); 
    $builder = $db->table($table)->where('id',$id_user);
    $query = $builder->get();

    foreach($query->getResult() as $row){
        if($row->$currentQuestion =="ok" || $row->$currentQuestion =="bad" ){
            return "full"; //vrati nam ze uz je hodnota pevne zapsana 
        }
    }

    return "empty";  //vrati nam ze jsem zatim nezadali odpoved a odpoved bude ulozena do databaze
}

*/
/*function get_random_page()
{
    $this->db->order_by('id', 'RANDOM');
    or
    $this->db->order_by('rand()');
    $this->db->limit(1);
    $query = $this->db->get('pages');
    return $query->result_array();

}*/


