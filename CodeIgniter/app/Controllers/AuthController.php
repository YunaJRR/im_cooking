<?php

namespace App\Controllers;

use App\Models\UserModel; // Importamos el modelo de usuarios para interactuar con la base de datos.

class AuthController extends BaseController
{
    /**
     * Muestra el formulario de registro de usuario.
     */
    public function register()
    {
        return view('register'); // Carga y retorna la vista del formulario de registro.
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function registerProcess()
    {
        helper(['form', 'url']); // Carga los helpers necesarios para trabajar con formularios y URLs.

        // Configuración de las reglas de validación del formulario.
        $rules = [
            'firstname' => 'required|min_length[3]|max_length[50]', 
            'lastname' => 'required|min_length[3]|max_length[50]', 
            'username' => 'required|min_length[3]|max_length[50]', 
            'email' => 'required|valid_email|is_unique[users.email]', 
            'password' => 'required|min_length[8]', 
            'confirm-password' => 'required|matches[password]', 
        ];

        // Si la validación falla, volvemos a mostrar el formulario con los errores.
        if (!$this->validate($rules)) {
            return view('sign-up', [
                'validation' => $this->validator, // Pasamos los errores de validación a la vista.
            ]);
        }

        // Si la validación pasa, procedemos a guardar el usuario en la base de datos.
        $userModel = new UserModel();
        $userModel->save([
            'CreationDate' => date('Y-m-d'),
            'DeletionDate' => null,
            'Firstname' => $this->request->getPost('firstname'),
            'Lastname' => $this->request->getPost('lastname'),
            'Username' => $this->request->getPost('username'), 
            'Email' => $this->request->getPost('email'),
            'Password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Encriptamos la contraseña antes de guardarla.
            'RoleID' => 1 
        ]);

        // Redirigimos al formulario de inicio de sesión con un mensaje de éxito.
        return redirect()->to('/sign-in')->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function signIn()
    {
        return view('sign-in'); // Carga y retorna la vista del formulario de inicio de sesión.
    }
    public function login()
    {
        return view('login'); // Carga y retorna la vista del formulario de inicio de sesión.
    }
    public function signUp()
    {
        return view('sign-up'); // Carga y retorna la vista del formulario de registro.
    }

    /**
     * Procesa el inicio de sesión del usuario.
     */
    public function loginProcess()
    {
        helper(['form', 'url']); // Carga los helpers necesarios para trabajar con formularios y URLs.
        $session = session(); // Inicia una sesión para el usuario.

        // Configuración de las reglas de validación del formulario.
        $rules = [
            'email' => 'required|valid_email', // El correo es obligatorio y debe ser válido.
            'password' => 'required', // La contraseña es obligatoria.
        ];

        // Si la validación falla, volvemos a mostrar el formulario con los errores.
        if (!$this->validate($rules)) {
            return view('sign-in', [
                'validation' => $this->validator, // Pasamos los errores de validación a la vista.
            ]);
        }

        // Si la validación pasa, verificamos las credenciales.
        $userModel = new UserModel();
        $user = $userModel->findByEmail($this->request->getPost('email')); // Buscamos al usuario por su correo.

        if ($user && password_verify($this->request->getPost('password'), $user['Password'])) {
            // Si las credenciales son correctas, guardamos datos del usuario en la sesión.
            echo 'hola';
            $session->set([
                
                'id' => $user['ID'],           // ID del usuario.
                'name' => $user['Username'],       // Nombre del usuario.
                'email' => $user['Email'],     // Correo del usuario.
                'isLoggedIn' => true,          // Bandera para indicar que está logueado.
                
            ]);

            // Redirigimos a la página de inicio con un mensaje de éxito.
            return redirect()->to('')->with('success', 'Inicio de sesión exitoso.');
        }

        // Si las credenciales son incorrectas, mostramos un mensaje de error.
        return redirect()->to('/login')->with('error', 'Correo o contraseña incorrectos.');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        $session = session(); // Inicia o accede a la sesión.
        $session->destroy(); // Destruye todos los datos de la sesión.

        // Redirige al formulario de inicio de sesión con un mensaje de éxito.
        return redirect()->to('/login')->with('success', 'Has cerrado sesión correctamente.');
    }
    public function dashboard()
    {
        return view('dashboard'); // Redirigir a dashboard
    }
}
