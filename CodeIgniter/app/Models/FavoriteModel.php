<?php
namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Date', 'DeletionDate'];

    public function getFavorites($search = null, $perPage = 3, $page = 1, $sortField = 'favorites.ID', $sortOrder = 'asc')
    {
        // Select the necessary fields from favorites, users, and recipes
        $this->select('favorites.*, users.Username, recipes.Title')
            ->join('users', 'users.ID = favorites.UserID', 'left')
            ->join('recipes', 'recipes.ID = favorites.RecipeID', 'left');

        // Apply search filter if provided
        if ($search) {
            $this->like('users.Username', $search); // Assuming you want to search by Username
        }

        // Apply sorting
        $this->orderBy($sortField, $sortOrder);

        // Paginate results
        return $this->paginate($perPage, 'default', $page);
    }
}
?>