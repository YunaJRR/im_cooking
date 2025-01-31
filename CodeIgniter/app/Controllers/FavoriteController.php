<?php

namespace App\Controllers;

use App\Models\FavoriteModel;

class FavoriteController extends BaseController
{
    public function index()
    {
        $FavoriteModel = new FavoriteModel();
        $data['favorites'] = $FavoriteModel->findAll();
        return view('favorites', $data);
    }
}