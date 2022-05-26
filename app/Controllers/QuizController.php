<?php

namespace App\Controllers;
use App\Models\UsersCurrentQuizStatsModel;
use App\Libraries\AnswersLibrary;  //import knihovny
use App\Libraries\QuestionsLibrary;
use App\Libraries\OptionsLibrary;
use App\Libraries\WorkWithDatabaseLibrary;
use App\Models\UserStatsModel;

class QuizController extends BaseController
{
    public function index() {
        echo view("templates/header");
        echo view('quiz');
    }

    public function createNewQuiz() {  //vytvori novy kviz klinkutim na tlacitko
        $QuestionLibrary = new QuestionsLibrary;
        $OptionLibrary = new OptionsLibrary;
        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $WorkWithDatabaseLibrary = new WorkWithDatabaseLibrary();

        $WorkWithDatabaseLibrary->createEmptyUserStatsAnswers(); //vynuluje hodnoty v users_stats tabulce pro konkretni id uzivatele

        $data = [
            'id' => session()->get('id_current_quiz_user'),
            'id_user'=>session()->get('id'), //nahradim session()->get('id')
            'quiz_started' =>0, //hodnota 0 znamena ze chci vytvorit novy kviz
            'lockAnswer' =>0, //musim nahrat na 0
        ];

        $usersCurrentQuizModel->save($data);

        $QuestionLibrary->getRandomQuestionId();//vygeneruje mi kviz (ulozi mi hodnoty ohledne current_quiz_stats do tabulky)

        /*generuje random odpovedi pro konkretni otazky*/ 
        $current_question_id = $QuestionLibrary->getCurrentQuestionId();  //zjisti konkretni id otazky
        $OptionLibrary->generateCorrectOptions('options',$current_question_id);  //vygeneruje random odpovedi pro konkretni otazku (ridi se podle id_otazky)
        
        return redirect()->to('/quiz');
    }

    public function checkAnswer($question_id) {//provadi se kdyz klikneme na jednu z moznych 4 odpovedi 
        $AnswerLibrary = new AnswersLibrary(); //knihovna
        $OptionLibrary = new OptionsLibrary;
        $WorkWithDatabaseLibrary = new WorkWithDatabaseLibrary();

        $lock = $AnswerLibrary->getLockVariable(session()->get('id_current_quiz_user')); // to 1 musim nahradit session()->get('id_current_quiz');

        if($lock == 0) {

            $myAnswer = $_POST["myAnswer"];

            if ($AnswerLibrary->checkIfAnswerIsCorrect($myAnswer,$question_id)){
                $WorkWithDatabaseLibrary->pointsIncrease("ok");  //prida mi bod pro spravnou odpoved
                session()->setFlashdata("ok","Odpoveď byla správná");
            }
    
            else{
                $WorkWithDatabaseLibrary->pointsIncrease("bad"); //prida mi bod pro spatnou odpoved
                session()->setFlashdata("bad","Odpoveď byla špatná");  //vrati mi zpravu ze moje odpoved byla spatna
                $correctAnswer = $OptionLibrary->getCurrentCorrectOption($question_id); 
                session()->setFlashdata("correctAnswer",$correctAnswer);          //vrati mi ktera odpoved byla spravna (at si to uzivatel muze precist a zjisti kde ma chybu)
                
            }

        }
       
        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        //lock = 1;
        $lockData = [
            'id' => session()->get('id_current_quiz_user'),
            'id_user'=>session()->get('id'), //nahradim session()->get('id'), //musim nahradit session()->get('current_user_queiz_id')
            'lockAnswer' =>1, //nastavim na 1 (odpoved je zamknuta)
        ];
        $usersCurrentQuizModel->save($lockData);

        return redirect()->to('/quiz');
    }

    public function MoveToNextQuestion() {
        $QuestionLibrary = new QuestionsLibrary(); //knihovna
        $OptionLibrary = new OptionsLibrary();

        $QuestionLibrary->moveToAnotherQuestion(); //presune se na dalsi otazku
        
        /*generuje random odpovedi pro konkretni otazky*/ 
        $current_question_id = $QuestionLibrary->getCurrentQuestionId();  //zjisti konkretni id otazky
        $OptionLibrary->generateCorrectOptions('options',$current_question_id);  //vygeneruje random odpovedi pro konkretni otazku (ridi se podle id_otazky)

        if($QuestionLibrary->showUserAnswersResults() == "quiz_finished"){
            return redirect()->to('/evaluation');
        }

        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        //lock = 0;
        $lockData = [
            'id' => session()->get('id_current_quiz_user'),
            'id_user'=>session()->get('id'), //musim nahradit session()->get('id')
            'lockAnswer' =>0, //nastavim na 0 (odpoved je dostupna)
        ];
        $usersCurrentQuizModel->save($lockData);
        return redirect()->to('/quiz');
    }

}