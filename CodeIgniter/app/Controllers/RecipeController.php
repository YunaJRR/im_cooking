<?php

namespace App\Controllers;

use App\Models\RecipeModel;

class RecipeController extends BaseController
{
    public function index()
    {
        $RecipeModel = new RecipeModel();
        $data['recipes'] = $RecipeModel->findAll();
        return view('recipes', $data);
    }
}