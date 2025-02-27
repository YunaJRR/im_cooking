<?php

namespace App\Controllers;

use App\Models\ProfileModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        return view('profile');
    }
}