<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentController extends BaseController
{
    public function index()
    {
        $CommentModel = new CommentModel();
        $data['comments'] = $CommentModel->findAll();
        return view('comments', $data);
    }
}