<?php

namespace App\Controllers;

use App\Libraries\WorkWithDatabaseLibrary;
use App\Models\UserModel;
use App\Models\UsersCurrentQuizStatsModel;
use App\Models\UserStatsModel;

class UserLoginController extends BaseController
{
    public function index()
    {
        echo view("templates/header");
        helper('form');
        $data =[];
        echo view('login');
    }

    public function login() {
        echo view("templates/header");
        helper('form');
        
        $session = session();

        $userModel = new UserModel();
        $WorkWithDatabaseLibrary = new WorkWithDatabaseLibrary();
        $usersCurrentQuizModel = new UsersCurrentQuizStatsModel();
        $usersStatsModel = new UserStatsModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $userModel->where('email',$email)->first();
    
        if($data) {

            $passCheck = $data["password"];
            // $authenticatePassword = password_verify($password, $passCheck);  //porovnavani hesel
            if($passCheck == $password) {    //jebat na sivrovani (kdyz se hesla rovnaji tak nas to prihlasi )
                
                if ($WorkWithDatabaseLibrary->IfUserLoginForFirstTime($data["id_u"])) {  //ulozi data a vygeneruje ID QUIZU POUZE kdyz se jedna o prvni prihlaeni 
                    $dataQuiz = [
                        'id' =>$WorkWithDatabaseLibrary->generateRandomIdOfQuizToUser('users_current_quiz_stats',1,10000),  //id quizu mi to vygenerovalo
                        'id_user'=>$data['id_u'], //id uzivatele musim ulozit
                        'quiz_started' =>0, //hodnota 0 znamena ze chci vytvorit novy kviz
                        'lockAnswer' =>0, //musim nahrat na 0
                    ];
            
                    $usersCurrentQuizModel->insert($dataQuiz);

                    //vytvori mi to zaznam v tabulce v users_stats -> pouze pri prvnim prihlaseni uzivatele
                    $userQuizStats = [
                        'id' =>$WorkWithDatabaseLibrary->generateRandomIdOfQuizToUser('users_stats',1,10000),  //id quizu mi to vygenerovalo
                        'id_user'=>$data['id_u'], //id uzivatele musim ulozit
                        'correctAnswer' =>0, //hodnota 0 znamena ze chci vytvorit novy kviz
                        'badAnswer' =>0, //musim nahrat na 0
                    ];
            
                    $usersStatsModel->insert($userQuizStats);
                }                
                
                //ulozi mi data do session()
                $sess_data =[
                    'id' =>$data['id_u'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'id_current_quiz_user' =>$WorkWithDatabaseLibrary->getIdOfUserQuiz($data["id_u"]), //zatim to delam takhle -> musim zmenit -> musim vytahout id toho quizu a ne uzivatele
                    'isLoggedIn' => true,
                ];
                $session->set($sess_data);

               return redirect()->to('/');
            }

            else{
                echo "spatne heslo";
            }

        }

        else{
            echo "email neexistuje";
        }

    }

}