<?php

namespace App\Controllers;

class Home extends BaseController
{
    // Default method
    public function index()
    {

        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        return view('index');
    }
    public function getUsers()
    {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll(); // Obtains all registers
        return view('user_list', ['users' => $users]);
    }
}
