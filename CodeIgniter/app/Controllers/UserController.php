<?php
namespace App\Controllers;
use App\Models\UserModel;
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
    $name = $this->request->getVar('name'); 
    if ($name) {
        $query = $userModel->like('Username', $name);
        $data['users'] = $query->paginate($perPage);
    } else {
        $data['users'] = $userModel->paginate($perPage);
    }
    $data['pager'] = $userModel->pager;
    $data['Username'] = $name; 
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