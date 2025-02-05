<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Controllers\Views;

/**
 * UserController: 
 */
class UserController extends BaseController
{
    
    public function index()
{
    $session = session()->get('role');
    if (!$session) {
        return redirect()->to('sign-in')->with('error', 'You must be logged in to access this page.');
    }
    
    $userModel = new UserModel();
    $perPage = 3;

    // Get the search parameters from the request
    $name = $this->request->getVar('name'); // For Username
    $firstname = $this->request->getVar('firstname'); // For Firstname
    $lastname = $this->request->getVar('lastname'); // For Lastname
    $email = $this->request->getVar('email'); // For Email

    // Build the query based on the search parameters
    $query = $userModel;

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

    // Execute the query and paginate results
    $data['users'] = $query->paginate($perPage);
    $data['pager'] = $userModel->pager;

    // Store the search parameters in the data array
    $data['Username'] = $name; 
    $data['Firstname'] = $firstname; 
    $data['Lastname'] = $lastname; 
    $data['Email'] = $email; 

    return view('users', $data);
}
    public function saveUser($id = null)
    {
        $userModel = new UserModel();
        // Load user data if editing
        $data['user'] = $id ? $userModel->find($id) : null;
        if ($this->request->getMethod() == 'POST') {
            // Validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email',
            ]);
            if (!$validation->withRequest($this->request)->run()){
                // Show validation errors
                $data['validation'] = $validation;
            }else{
                // Prepare form data
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                ];
                if ($id){
                    // Update existing user
                    $userModel->update($id, $userData);
                    $message = 'User updated successfully';
                } else {
                    // Create new user
                    $userModel->save($userData);
                    $message = 'User created successfully';
                }
                // Redirect to the list with a success message
                return redirect()->to('/users', )->with('success', $message);
            }
        }
        return view('user_form', $data);
    }
    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id); // Delete user
        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}