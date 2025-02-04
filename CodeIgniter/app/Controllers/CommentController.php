<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentController extends BaseController
{
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        $CommentModel = new CommentModel();
        $data['comments'] = $CommentModel->findAll();
        return view('comments', $data);
    }
}