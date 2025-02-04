<?php

namespace App\Controllers;

use App\Models\FavoriteModel;

class FavoriteController extends BaseController
{
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        $FavoriteModel = new FavoriteModel();
        $data['favorites'] = $FavoriteModel->findAll();
        return view('favorites', $data);
    }
}