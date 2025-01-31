<?php

namespace App\Controllers;

class Home extends BaseController
{
    // Método por defecto
    public function index(): string
    {
        return view('index');
    }
    public function getUsers()
    {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll(); // Obtiene todos los registros
        return view('user_list', ['users' => $users]);
    }
}
