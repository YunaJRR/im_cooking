<?php
namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Date', 'DeletionDate'];

    public function getFavorites($search = null, $perPage = 3, $page = 1)
    {
        $this->select('Favorites.*, users.Username')
         ->join('users', 'users.ID = Favorites.UserID', 'left');
        $this->select('Favorites.*, recipes.Title')
         ->join('recipes', 'recipes.ID = Favorites.RecipeID', 'left');
        if ($search) {
            return $this->like('Text', $search)
                        ->paginate($perPage, 'default', $page);
        }
        return $this->paginate($perPage);
    }
}
?>