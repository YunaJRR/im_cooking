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
        return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
    }

    $favoriteModel = new FavoriteModel();
    $perPage = 3;

    // Get the search parameters from the request
    $user = $this->request->getVar('user'); // For Favorite Text
    $recipe = $this->request->getVar('recipe'); // For Favorite Text
    $date = $this->request->getVar('date'); // For Favorite Text

    // Get sorting parameters from the request
    $sortField = $this->request->getVar('sortField') ?? 'favorites.ID'; // Default sort field
    $sortOrder = $this->request->getVar('sortOrder') ?? 'asc'; // Default sort order

    // Get the current page number from the request
    $page = $this->request->getVar('page') ?? 1; // Default to page 1 if not set

    // Execute the query and paginate results
    $data['favorites'] = $favoriteModel->getFavorites($user, $perPage, $page, $sortField, $sortOrder);
    $data['pager'] = $favoriteModel->pager;

    // Store the search parameters in the data array
    $data['User'] = $user; // Corrected variable name
    $data['Recipe'] = $recipe; // Corrected variable name
    $data['Date'] = $this->request->getVar('date'); // Get the date from the request

    $data['sortField'] = $sortField; // Pass sort field to view
    $data['sortOrder'] = $sortOrder; // Pass sort order to view

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

    public function exportToCsv()
    {
        $favoriteModel = new FavoriteModel();

        // Get the search parameters from the request
        $user = $this->request->getVar('user'); // For Username
        $recipe = $this->request->getVar('recipe'); // For Recipe Title
        $date = $this->request->getVar('date'); // For Recipe Date

        // Build the query based on the search parameters
        $query = $favoriteModel->select('favorites.*, users.Username, recipes.Title')
            ->join('users', 'users.ID = favorites.UserID', 'left')
            ->join('recipes', 'recipes.ID = favorites.RecipeID', 'left')
            ->where('favorites.DeletionDate', null); // Ensure we only get non-deleted favorites

        if ($user) {
            $query = $query->like('users.Username', $user);
        }
        if ($recipe) {
            $query = $query->like('recipes.Title', $recipe);
        }
        if ($date) {
            $query = $query->like('Date', $date); // Corrected to use favorites.Date
        }

        // Get the results
        $favorites = $query->findAll();

        // Set the headers for the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="favorites.csv"');

        // Open the output stream
        $output = fopen('php://output', 'w');

        // Add the CSV column headers
        fputcsv($output, ['User', 'Recipe Title', 'Date Added']);

        // Add the favorite data to the CSV
        foreach ($favorites as $favorite) {
            fputcsv($output, [
                $favorite['Username'], // User's name
                $favorite['Title'],    // Recipe title
                $favorite['Date'],     // Date added
            ]);
        }

        // Close the output stream
        fclose($output);
        exit; // Terminate the script to prevent any further output
    }
}