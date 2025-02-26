<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['CreationDate', 'DeletionDate', 'Title', 'Description', 'Instructions', 'Image', 'UserID'];

    public function getRecipes($search = null, $perPage = 3, $page = 1)
    {
        // Select the necessary fields from recipes and users
        $this->select('recipes.*, users.Username')
            ->join('users', 'users.ID = recipes.UserID', 'left');

        // Apply search filter if provided
        if ($search) {
            $this->like('Title', $search);
        }

        // Paginate results
        return $this->paginate($perPage, 'default', $page);
    }
}
?>