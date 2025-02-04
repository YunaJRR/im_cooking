<?php
namespace App\Controllers;
use App\Models\UserModel; // Import the user model to interact with the database.
class AuthController extends BaseController
{
    public function index(){
        $session = session()->get('isLoggedIn');
        print_r($session);
        

    }
    /**
     * Displays the user registration form.
     */
    public function register()
    {
        return view('register'); // Load and return the registration form view.
    }
    /**
     * Processes the registration of a new user.
     */
    public function registerProcess()
    {
        helper(['form', 'url']); // Load the necessary helpers for working with forms and URLs.
        // Configuration of the form validation rules.
        $rules = [
            'firstname' => 'required|min_length[3]|max_length[50]', 
            'lastname' => 'required|min_length[3]|max_length[50]', 
            'username' => 'required|min_length[3]|max_length[50]', 
            'email' => 'required|valid_email|is_unique[users.email]', 
            'password' => 'required|min_length[8]', 
            'confirm-password' => 'required|matches[password]', 
        ];
        // If validation fails, we show the form again with the errors.
        if (!$this->validate($rules)) {
            return view('sign-up', [
                'validation' => $this->validator, // Pass validation errors to the view.
            ]);
        }
        // If validation passes, proceed to save the user in the database.
        $userModel = new UserModel();
        $userModel->save([
            'CreationDate' => date('Y-m-d H:i:s'),
            'DeletionDate' => null,
            'Firstname' => $this->request->getPost('firstname'),
            'Lastname' => $this->request->getPost('lastname'),
            'Username' => $this->request->getPost('username'), 
            'Email' => $this->request->getPost('email'),
            'Password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Encrypt the password before saving it.
            'RoleID' => 1 
        ]);
        // Redirect to the login form with a success message.
        return redirect()->to('/sign-in')->with('success', 'User registered successfully.');
    }
    /**
     * Displays the login form.
     */
    public function signIn()
    {
        return view('sign-in'); // Load and return the login form view.
    }
    public function login()
    {
        return view('login'); // Load and return the login form view.
    }
    public function signUp()
    {
        return view('sign-up'); // Load and return the registration form view.
    }
    /**
     * Processes the user login.
     */
    public function loginProcess()
    {
        helper(['form', 'url']); // Load the necessary helpers for working with forms and URLs.
        $session = session(); // Start a session for the user.
        // Configuration of the form validation rules.
        $rules = [
            'email' => 'required|valid_email', // Email is required and must be valid.
            'password' => 'required', // Password is required.
        ];
        // If validation fails, we show the form again with the errors.
        if (!$this->validate($rules)) {
            return view('sign-in', [
                'validation' => $this->validator, // Pass validation errors to the view.
            ]);
        }
        // If validation passes, check the credentials.
        $userModel = new UserModel();
        $user = $userModel->findByEmail($this->request->getPost('email')); // Find the user by their email.
        if ($user && password_verify($this->request->getPost('password'), $user['Password'])) {
            // If the credentials are correct, save user data in the session.
            $session->set([
                'id' => $user['ID'],           // User ID.
                'name' => $user['Username'],   // User name.
                'email' => $user['Email'],     // User email.
                'role' => $user['RoleID'],     // User role
                'isLoggedIn' => true,          // Flag to indicate that the user is logged in.
            ]);
            // Redirect to the home page with a success message.
            
            return redirect()->to('')->with('success', 'Login successful.');
        }
        // If the credentials are incorrect, show an error message.
        return redirect()->to('/login')->with('error', 'Incorrect email or password.');
    }
    /**
     * Logs out the user.
     */
    public function logout()
    {
        $this->session = session(); // Start or access the session.
        $this->session->destroy(); // Destroy all session data.
        // Redirect to the login form with a success message.
        return redirect()->to('/sign-in')->with('success', 'You have logged out successfully.');
    }
    public function dashboard()
    {
        return view('dashboard'); // Redirect to dashboard
    }
}