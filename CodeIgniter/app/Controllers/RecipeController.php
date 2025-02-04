<?php

namespace App\Controllers;

use App\Models\RecipeModel;

class RecipeController extends BaseController
{
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        $RecipeModel = new RecipeModel();
        $data['recipes'] = $RecipeModel->findAll();
        return view('recipes', $data);
    }
}