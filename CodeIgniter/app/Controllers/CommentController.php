<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentController extends BaseController
{
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to(uri: 'sign-in')->with('error', 'You must be logged in to access this page.');
        }
        $commentModel = new CommentModel();
        $perPage = 3;

        // Get the search parameters from the request
        $user = $this->request->getVar('user'); // For Comment Text
        $recipe = $this->request->getVar('recipe'); // For Comment Text
        $text = $this->request->getVar('text'); // For Comment Text

        // Build the query based on the search parameters
        $query = $commentModel;
        $query->select('comments.*, users.Username, recipes.Title')
          ->join('users AS u1', 'u1.ID = comments.UserID', 'left')
          ->join('recipes AS r1', 'r1.ID = comments.RecipeID', 'left');

        if ($user) {
            $query->like('users.Username', $user); // Correctly filter by Username
        }
        if ($recipe) {
            $query->like('recipes.Title', $recipe); // Correctly filter by Recipe Title
        }
        if ($text) {
            $query->like('comments.Text', $text); // Correctly filter by Comment Text
        }

        // Execute the query and paginate results
        $data['comments'] = $commentModel->getComments($text, $perPage);
        $data['pager'] = $commentModel->pager;

        // Store the search parameters in the data array
        $data['User'] = $user;
        $data['Recipe'] = $recipe;
        $data['Text'] = $text;

        return view('comments', $data);
    }

    public function addComment()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        $CommentModel = new CommentModel();
        $data['comments'] = $CommentModel->findAll();
        return view('comments', $data);
    }
    public function saveComment($id = null)
    {
        $commentModel = new CommentModel();
        // Load comment data if editing
        $data['comment'] = $id ? $commentModel->find($id) : null;

        // Fetch recipes to display in the dropdown
        $data['recipes'] = $this->recipeModel->findAll(); // Fetch all recipes

        if ($this->request->getMethod() == 'POST') {
            // Validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'text' => 'required|min_length[1]|max_length[500]', // Adjust max length as needed
                'RecipeID' => 'required|integer', // Assuming RecipeID is an integer
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Show validation errors
                $data['validation'] = $validation;
                // Render the view with the validation errors and recipes
                return view('add-comment', $data);
            } else {
                // Prepare form data
                $commentData = [
                    'UserID' => session()->get('id'), // Assuming you store user ID in session
                    'RecipeID' => $this->request->getPost('RecipeID'),
                    'Text' => $this->request->getPost('text'),
                    'Date' => date('Y-m-d H:i:s'),
                ];

                if ($id) {
                    // Update existing comment
                    $commentModel->update($id, $commentData);
                    $message = 'Comment updated successfully';
                } else {
                    // Create new comment
                    $commentModel->save($commentData);
                    $message = 'Comment created successfully';
                }
                // Redirect to the list with a success message
                return redirect()->to('/comments')->with('success', $message);
            }
        }

        // If not a POST request, just render the view with the recipes
        return view('add-comment', $data);
    }

    public function delete($id)
    {
        $commentModel = new CommentModel();
        $commentModel->delete($id);
        return redirect()->to('/comments')->with('success', 'Comment deleted successfully.');
    }
}
?>
