<?php

namespace App\Controllers;

use App\Models\FavoriteModel;
use App\Models\RecipeModel;

class FavoriteController extends BaseController
{
    protected $recipeModel;
    public function __construct()
    {
        $this->recipeModel = new RecipeModel(); // Load RecipeModel
    }
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to(uri: 'sign-in')->with('error', 'You must be logged in to access this page.');
        }

        $favoriteModel = new FavoriteModel();
        $perPage = 3;

        // Get the search parameters from the request
        $user = $this->request->getVar('user'); // For Favorite Text
        $recipe = $this->request->getVar('recipe'); // For Favorite Text

        // Build the query based on the search parameters
        $query = $favoriteModel;
        $query->select('favorites.*, users.Username, recipes.Title')
          ->join('users AS u1', 'u1.ID = favorites.UserID', 'left')
          ->join('recipes AS r1', 'r1.ID = favorites.RecipeID', 'left');

        if ($user) {
            $query->like('users.Username', $user); // Correctly filter by Username
        }
        if ($recipe) {
            $query->like('recipes.Title', $recipe); // Correctly filter by Recipe Title
        }


        // Execute the query and paginate results
        $data['favorites'] = $favoriteModel->getFavorites($user, $perPage);
        $data['pager'] = $favoriteModel->pager;

        // Store the search parameters in the data array
        $data['User'] = $user;
        $data['Recipe'] = $recipe;

        return view('favorites', $data);
    }
    public function addFavorite()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        // Fetch favorites to display in the dropdown
        $data['recipes'] = $this->recipeModel->findAll(); // Fetch all recipes
        return view('add-favorite', $data); // Pass recipes to the view
    }
    public function saveFavorite($id = null)
    {
        $favoriteModel = new FavoriteModel();
        // Load comment data if editing
        $data['favorite'] = $id ? $favoriteModel->find($id) : null;

        // Fetch recipes to display in the dropdown
        $data['recipes'] = $this->recipeModel->findAll(); // Fetch all recipes


        if ($this->request->getMethod() == 'POST') {
            // Validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'RecipeID' => 'required|integer', // Assuming RecipeID is an integer
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Show validation errors
                $data['validation'] = $validation;
                // Render the view with the validation errors and favorites
                return view('add-favorite', $data);
            } else {
                // Prepare form data
                $favoriteData = [
                    'UserID' => session()->get('id'), // Assuming you store user ID in session
                    'RecipeID' => $this->request->getPost('RecipeID'),
                    'Text' => $this->request->getPost('text'),
                    'Date' => date('Y-m-d H:i:s'),
                ];

                if ($id) {
                    // Update existing favorite
                    $favoriteModel->update($id, $favoriteData);
                    $message = 'Favorite updated successfully';
                } else {
                    // Create new favorite
                    $favoriteModel->save($favoriteData);
                    $message = 'Favorite created successfully';
                }
                // Redirect to the list with a success message
                return redirect()->to('/favorites')->with('success', $message);
            }
        }

        // If not a POST request, just render the view with the favorites
        return view('add-favorite', $data);
    }
    public function delete($id)
    {
        $favoriteModel = new FavoriteModel();
        $favoriteData = [
            'DeletionDate' => date('Y-m-d H:i:s')
        ];
        
        // Update the user to mark as deleted
        if ($favoriteModel->update($id, $favoriteData)) {
            return redirect()->to('/favorites')->with('success', 'User  marked as deleted successfully.');
        } else {
            return redirect()->to('/favorites')->with('error', 'Failed to mark user as deleted.');
        }
    }

}