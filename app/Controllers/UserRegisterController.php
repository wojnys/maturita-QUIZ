<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserRegisterController extends BaseController
{
    public function index()
    {
        echo view("templates/header");
        helper('form');
        $data =[];
        echo view('register',$data);
    }

    public function registration(){
        helper('form');
        echo view("templates/header");
       
       $userModel = new UserModel();

        $rules = $this->validate([
            'name'=>[
                'rules'=>'required|max_length[20]|min_length[3]',
                'errors' => [
                    'required' => 'Toto pole je povinné',
                    'max_length' => 'Toto pole obsahuje příliš mnoho znaků',
                    'min_length' => 'Toto pole obsahuje málo znaků '
                ],
            ],

            'email'=>[ 
                'rules'=> 'required|is_unique[users.email]',
                'errors' => [
                    'required' => 'Toto pole je povinné',
                    'is_unique' => 'Ten {field} již existuje',
                ],
            ],

            'password'=>[
                    'rules'=>'required|max_length[50]|min_length[5]',
                'errors' => [
                    'required' => 'Toto pole je povinné',
                    'max_length' => 'Toto pole obsahuje příliš mnoho znaků',
                    'min_length' => 'Toto pole obsahuje příliš málo znaků '
                ],
            ],

            'confPass'=>[
                'rules'=>'required|matches[password]',
                'errors' => [
                    'required' => 'Toto pole je povinné',
                    'matches' => 'Hesla se neshodují',
                ],
            ],
        ]);
        

        if(!$rules) {
            echo view('register',[
                'validation'=>$this->validator,
            ]);
        }
        //kdyz je vse ok tak se ulozi data o uzivateli do databaze
        else{
            $dataUser = [
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'created' =>date('Y-m-d'),
                'password' => $this->request->getVar('password'),
               ];
               $userModel->save($dataUser);

        }
            
    
    }
       
      
}

