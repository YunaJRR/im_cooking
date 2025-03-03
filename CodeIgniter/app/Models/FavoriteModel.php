<?php
namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Date', 'DeletionDate'];

    public function getFavorites($user = null, $recipe = null, $date = null, $perPage = 3, $page = 1, $sortField = 'favorites.ID', $sortOrder = 'asc')
    {
        // Select the necessary fields from favorites, users, and recipes
        $this->select('favorites.*, users.Username, recipes.Title')
            ->join('users', 'users.ID = favorites.UserID', 'left')
            ->join('recipes', 'recipes.ID = favorites.RecipeID', 'left');

        // Apply search filters if provided
        if ($user !== null) {
            $this->like('users.Username', (string)$user);
        }
        if ($recipe !== null) {
            $this->like('recipes.Title', (string)$recipe);
        }
        if ($date !== null) {
            $this->where('favorites.Date', (string)$date); // Assuming you want to filter by date
        }

        // Apply sorting
        $this->orderBy($sortField, $sortOrder);

        // Paginate results
        return $this->paginate($perPage, 'default', $page);
    }
}
?>