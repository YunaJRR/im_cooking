<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();
        return view('users', $data);
    }
    public function saveUser($id = null)
    {
        $userModel = new UserModel();
        // Cargar datos del usuario si es edición
        $data['user'] = $id ? $userModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {

            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email',
            ]);

            if (!$validation->withRequest($this->request)->run()){
                // Mostrar errores de validación
                $data['validation'] = $validation;
            }else{
                // Preparar datos del formulario
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                ];

                if ($id){
                    // Actualizar usuario existente
                    $userModel->update($id, $userData);
                    $message = 'Usuario actualizado correctamente';
                } else {
                    // Crear nuevo usuario
                    $userModel->save($userData);
                    $message = 'Usuario creado correctamente';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/users')->with('success', $message);
            }

        }
        return view('user_form', $data);
    }
    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id); // Eliminar usuario
        return redirect()->to('/users')->with('success', 'Usuario eliminado correctamente.');
    }
}