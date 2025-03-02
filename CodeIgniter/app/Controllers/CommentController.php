<?php
namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\RecipeModel;

class CommentController extends BaseController
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
        
        $commentModel = new CommentModel();
        $perPage = 3;

        // Get the search parameters from the request
        $user = $this->request->getVar('user'); // For Comment Text
        $recipe = $this->request->getVar('recipe'); // For Comment Text
        $text = $this->request->getVar('text'); // For Comment Text
        $date = $this->request->getVar('date'); // For Comment Text

        // Get sorting parameters from the request
        $sortField = $this->request->getVar('sortField') ?? 'comments.ID'; // Default sort field
        $sortOrder = $this->request->getVar('sortOrder') ?? 'asc'; // Default sort order

        // Get the current page number from the request
        $page = $this->request->getVar('page') ?? 1; // Default to page 1 if not set

        // Execute the query and paginate results
        $data['comments'] = $commentModel->getComments($text, $perPage, $page, $sortField, $sortOrder);
        $data['pager'] = $commentModel->pager;

        // Store the search parameters in the data array
        $data['User'] = $user;
        $data['Recipe'] = $recipe;
        $data['Text'] = $text;
        $data['Date'] = $date;
        $data['sortField'] = $sortField; // Pass sort field to view
        $data['sortOrder'] = $sortOrder; // Pass sort order to view

        return view('comments', $data);
    }

    public function addComment()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        // Fetch recipes to display in the dropdown
        $data['recipes'] = $this->recipeModel->findAll(); // Fetch all recipes
        return view('add-comment', $data); // Pass recipes to the view
    }
    public function saveComment($id = null)
    {
        $commentModel = new CommentModel();
        // Load comment data if editing
        $data['comment'] = $id ? $commentModel->find($id) : null;

        // Fetch recipes to display in the dropdown
        $data['recipes'] = $this->recipeModel->findAll(); // Fetch all recipes

        // If editing, set the selected recipe ID
        if ($data['comment']) {
            $data['selectedRecipeID'] = $data['comment']['RecipeID'];
        }

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
    public function exportToCsv()
    {
        $commentModel = new CommentModel();

        // Get the search parameters from the request
        $user = $this->request->getVar('user'); // For Username
        $recipe = $this->request->getVar('recipe'); // For Recipe Title
        $text = $this->request->getVar('text'); // For Comment Text
        $date = $this->request->getVar('date'); // For Comment Date

        // Build the query based on the search parameters
        $query = $commentModel->select('comments.*, users.Username, recipes.Title')
            ->join('users', 'users.ID = comments.UserID', 'left')
            ->join('recipes', 'recipes.ID = comments.RecipeID', 'left')
            ->where('comments.DeletionDate', null); // Ensure we only get non-deleted comments

        if ($user) {
            $query = $query->like('users.Username', $user);
        }
        if ($recipe) {
            $query = $query->like('recipes.Title', $recipe);
        }
        if ($text) {
            $query = $query->like('comments.Text', $text);
        }
        if ($date) {
            $query = $query->like('Date', $text);
        }

        // Get the results
        $comments = $query->findAll();

        // Set the headers for the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="comments.csv"');

        // Open the output stream
        $output = fopen('php://output', 'w');

        // Add the CSV column headers
        fputcsv($output, ['User ', 'Recipe Title', 'Comment Text', 'Date Added']);

        // Add the comment data to the CSV
        foreach ($comments as $comment) {
            fputcsv($output, [
                $comment['Username'], // User's name
                $comment['Title'],    // Recipe title
                $comment['Text'],     // Comment text
                $comment['Date'],     // Date added
            ]);
        }

        // Close the output stream
        fclose($output);
        exit; // Terminate the script to prevent any further output
    }
}
?>
