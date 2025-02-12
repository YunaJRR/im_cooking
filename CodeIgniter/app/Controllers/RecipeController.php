<?php
namespace App\Controllers;

use App\Models\RecipeModel;

/**
 * RecipeController: 
 */
class RecipeController extends BaseController
{
    public function index()
{
    $session = session()->get('role');
    if (!$session) {
        return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
    }

    
    
    $recipeModel = new RecipeModel();
    $perPage = 3;

    // Get the search parameters from the request
    $title = $this->request->getVar('title'); // For Title
    $description = $this->request->getVar('description'); // For Description

    // Execute the query and paginate results
    $data['recipes'] = $recipeModel->getRecipes($title, $perPage);
    $data['pager'] = $recipeModel->pager;

    // Store the search parameters in the data array
    $data['Title'] = $title; 
    $data['Description'] = $description; 

    return view('recipes', $data);
}
    public function addRecipe()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        return view('add-recipe'); // Load and return the form for adding users.
    }
    public function saveRecipe($id = null)
    {
        $recipeModel = new RecipeModel();
        // Load recipe data if editing
        $data['recipe'] = $id ? $recipeModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'title'       => 'required|min_length[3]|max_length[100]', 
                'description' => 'required|min_length[3]|max_length[255]', 
                'instructions' => 'required|min_length[3]', 
                'image'       => 'permit_empty|valid_url'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Show validation errors
                $data['validation'] = $validation;
            } else {
                // Prepare form data
                $recipeData = [
                    'CreationDate' => date('Y-m-d H:i:s'),
                    'Title'        => $this->request->getPost('title'),
                    'Description'  => $this->request->getPost('description'),
                    'Instructions' => $this->request->getPost('instructions'),
                    'Image'        => $this->request->getPost('image'),
                    'UserID'       => session()->get('id')
                ];

                if ($id) {
                    // Update existing recipe
                    $recipeModel->update($id, $recipeData);
                    $message = 'Recipe updated successfully';
                } else {
                    // Create new recipe
                    $recipeModel->save($recipeData);
                    $message = 'Recipe created successfully';
                }
                // Redirect to the list with a success message
                return redirect()->to('/recipes')->with('success', $message);
            }
        }
        return view('add-recipe', $data);
    }

    public function delete($id)
    {
        $recipeModel = new RecipeModel();
        $recipeModel->delete($id); // Directly delete the recipe
        return redirect()->to('/recipes')->with('success', 'Recipe deleted successfully.');
    }
}

























/**
     * This function capitalizes the first letter of each word in a given string.
     *
     * @param string $title The title to capitalize.
     * @return string The capitalized title.
     */
    function capitalizeTitle($title)
    {
        return ucwords($title); 
    }