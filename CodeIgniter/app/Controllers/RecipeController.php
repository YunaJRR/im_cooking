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
        $perPage = 3; // Set the number of items per page

        // Get the search parameters from the request
        $title = $this->request->getVar('title'); // For Title
        $description = $this->request->getVar('description'); // For Description
        $instructions = $this->request->getVar('instructions'); // For Instructions
        $image = $this->request->getVar('image'); // For Image
 


        // Get sorting parameters from the request
        $sortField = $this->request->getVar('sortField') ?? 'id'; // Default sort field
        $sortOrder = $this->request->getVar('sortOrder') ?? 'asc'; // Default sort order

        // Build the query
        $query = $recipeModel;
        $query = $query->where('recipes.DeletionDate', null);

        // Join with users table to get the Username
        $query = $query->select('recipes.*, users.Username')
                    ->join('users', 'users.ID = recipes.UserID', 'left');

        
        if ($title) {
            $query = $query->like('Title', $title);
        }
        if ($description) {
            $query = $query->like('Description', $description);
        }

        // Apply sorting
        $query = $query->orderBy($sortField, $sortOrder);

        // Execute the query and paginate results
        $data['recipes'] = $query->paginate($perPage);
        $data['pager'] = $recipeModel->pager;

        // Store the search parameters in the data array
        $data['Title'] = $title; 
        $data['Description'] = $description; 
        $data['Instructions'] = $instructions; 
        $data['Image'] = $image;

        $data['sortField'] = $sortField; // Pass sort field to view
        $data['sortOrder'] = $sortOrder; // Pass sort order to view

        return view('recipes', $data);
    }
    public function addRecipe()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        if ((session()->get('role')) != 2 && (session()->get('role')) != 3){
            return redirect()->to('')->with('error', session()->get('role'));
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
        $recipeData = [
            'DeletionDate' => date('Y-m-d H:i:s')
        ];
        
        // Update the user to mark as deleted
        if ($recipeModel->update($id, $recipeData)) {
            return redirect()->to('/recipes')->with('success', 'Recipe  marked as deleted successfully.');
        } else {
            return redirect()->to('/recipes')->with('error', 'Failed to mark recipe as deleted.');
        }
    }

    public function exportToCsv()
    {
        try{
            $recipeModel = new RecipeModel();

            // Get the search parameters from the request
            $title = $this->request->getVar('title'); // For Title
            $description = $this->request->getVar('description'); // For Description
            $instructions = $this->request->getVar('instructions'); // For Instructions
            $image = $this->request->getVar('image'); // For Image

            // Build the query based on the search parameters
            $query = $recipeModel->where('DeletionDate', null);

            if ($title) {
                $query = $query->like('Title', $title);
            }
            if ($description) {
                $query = $query->like('Description', $description);
            }
            if ($instructions) {
                $query = $query->like('Instructions', $instructions);
            }
            if ($image) {
                $query = $query->like('Image', $image);
            }

            // Get the results
            $recipes = $query->findAll();

            // Set the headers for the CSV file
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="recipes.csv"');

            // Open the output stream
            $output = fopen('php://output', 'w');

            // Add the CSV column headers
            fputcsv($output, ['Title', 'Description', 'Instructions', 'Image']);

            // Add the user data to the CSV
            foreach ($recipes as $recipe) {
                fputcsv($output, [
                    $recipe['Title'],
                    $recipe['Description'],
                    $recipe['Instructions'],
                    $recipe['Image'],
                ]);
            }

            // Close the output stream
            fclose($output);
            exit; // Terminate the script to prevent any further output
        }catch (\Exception $e) {
            log_message('error', 'Error exporting to CSV: ' . $e->getMessage());
            return redirect()->to('/recipes')->with('error', 'An error occurred while exporting to CSV.');
        }
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