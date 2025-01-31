<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['CreationDate', 'DeletionDate', 'Firstname', 'Lastname', 'Username', 'Email', 'Password', 'RoleID'];

    /**
     * Método personalizado para encontrar un usuario por correo electrónico.
     * @param string $email El correo que se busca 
     * @return array|null Retorna un array con los datos del usuario si lo encuentra o vacío si no existe
     */

public function findByEmail(string $email)
{
    return $this->where('Email', $email)->first();
}
}
?>