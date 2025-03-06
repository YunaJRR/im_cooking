<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Controllers\Views;

/**
 * UserController: 
 */
class UserController extends BaseController
{
    
    public function addUser()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        if ((session()->get('role')) != 2){
            return redirect()->to('')->with('error', 'You are not allowed here...');
        }
        return view('add-user'); // Load and return the form for adding users.
    }
    public function index()
    {
        $session = session()->get('role');
        if (!$session) {
            return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
        }
        if ((session()->get('role')) != 2) {
            return redirect()->to('')->with('error', 'You are not allowed here...');
        }

        $userModel = new UserModel();
        $perPage = 3;

        $currentPage = $this->request->getVar('page') ?? 1; // Default to page 1 if not set
        $data['currentPage'] = $currentPage; // Pass the current page to the view

        // Get the search parameters from the request
        $name = $this->request->getVar('name'); // For Username
        $firstname = $this->request->getVar('firstname'); // For Firstname
        $lastname = $this->request->getVar('lastname'); // For Lastname
        $email = $this->request->getVar('email'); // For Email

        // Get sorting parameters from the request
        $sortField = $this->request->getVar('sortField') ?? 'id'; // Default sort field
        $sortOrder = $this->request->getVar('sortOrder') ?? 'asc'; // Default sort order

        // Build the query based on the search parameters
        $query = $userModel;
        $query = $query->where('DeletionDate', null);

        if ($name) {
            $query = $query->like('Username', $name);
        }
        if ($firstname) {
            $query = $query->like('Firstname', $firstname);
        }
        if ($lastname) {
            $query = $query->like('Lastname', $lastname);
        }
        if ($email) {
            $query = $query->like('Email', $email);
        }

        // Apply sorting
        $query = $query->orderBy($sortField, $sortOrder);

        // Execute the query and paginate results
        $data['users'] = $query->paginate($perPage);
        $data['pager'] = $userModel->pager;

        // Store the search parameters in the data array
        $data['Username'] = $name; 
        $data['Firstname'] = $firstname; 
        $data['Lastname'] = $lastname; 
        $data['Email'] = $email; 

        // Store sorting parameters in the data array
        $data['sortField'] = $sortField;
        $data['sortOrder'] = $sortOrder;

        return view('users', $data);
    }

public function saveUser ($id = null)
{
    $userModel = new UserModel();
    // Load user data if editing
    $data['user'] = $id ? $userModel->find($id) : null;

    if ($this->request->getMethod() == 'POST') {
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'firstname' => 'required|min_length[3]|max_length[50]', 
            'lastname'  => 'required|min_length[3]|max_length[50]', 
            'username'  => 'required|min_length[3]|max_length[50]', 
            'email'     => 'required|valid_email' . ($id ? '' : '|is_unique[users.email]'), 
            'password'  => 'required|min_length[8]', 
            'confirm-password' => 'required|matches[password]', 
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Show validation errors
            $data['validation'] = $validation;
        } else {
            // Prepare form data
            $userData = [
                'CreationDate' => date('Y-m-d H:i:s'),
                'DeletionDate' => null,
                'Firstname'    => $this->request->getPost('firstname'),
                'Lastname'     => $this->request->getPost('lastname'),
                'Username'     => $this->request->getPost('username'), 
                'Email'        => $this->request->getPost('email'),
                'Password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'RoleID'      => $this->request->getPost('role')
            ];

            if ($id) {
                // Update existing user
                $userModel->update($id, $userData);
                $message = 'User updated successfully';
            } else {
                // Create new user
                $userModel->save($userData);
                $message = 'User created successfully';
            }
            // Redirect to the list with a success message
            return redirect()->to('/users')->with('success', $message);
        }
    }
    return view('add-user', $data);
}
    public function delete($id)
    {
        $userModel = new UserModel();
        $userData = [
            'DeletionDate' => date('Y-m-d H:i:s')
        ];
        
        // Update the user to mark as deleted
        if ($userModel->update($id, $userData)) {
            return redirect()->to('/users')->with('success', 'User  marked as deleted successfully.');
        } else {
            return redirect()->to('/users')->with('error', 'Failed to mark user as deleted.');
        }
    }
   
    public function exportToCsv()
    {
        $userModel = new UserModel();

        // Get the search parameters from the request
        $name = $this->request->getVar('name'); // For Username
        $firstname = $this->request->getVar('firstname'); // For Firstname
        $lastname = $this->request->getVar('lastname'); // For Lastname
        $email = $this->request->getVar('email'); // For Email

        // Get sorting parameters from the request
        $sortField = $this->request->getVar('sortField');
        $sortOrder = $this->request->getVar('sortOrder');

        // Build the query based on the search parameters
        $query = $userModel;
        $query = $query->where('DeletionDate', null);

        if ($name) {
            $query = $query->like('Username', $name);
        }
        if ($firstname) {
            $query = $query->like('Firstname', $firstname);
        }
        if ($lastname) {
            $query = $query->like('Lastname', $lastname);
        }
        if ($email) {
            $query = $query->like('Email', $email);
        }

        // Apply sorting
        if ($sortField && $sortOrder) { // check if sort parameters exist
            $query = $query->orderBy($sortField, $sortOrder);
        }

        // Get the results
        $users = $query->findAll();

        // Set the headers for the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users.csv"');

        // Open the output stream
        $output = fopen('php://output', 'w');

        // Add the CSV column headers
        fputcsv($output, ['Username', 'Firstname', 'Lastname', 'Email']);

        // Add the user data to the CSV
        foreach ($users as $user) {
            fputcsv($output, [
                $user['Username'],
                $user['Firstname'],
                $user['Lastname'],
                $user['Email']
            ]);
        }

        // Close the output stream
        fclose($output);
        exit; // Terminate the script to prevent any further output
    }
}