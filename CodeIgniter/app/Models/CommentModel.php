<?php
namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Text', 'Date', 'DeletionDate'];

    public function getComments($search = null, $perPage = 3, $page = 1, $sortField = 'comments.ID', $sortOrder = 'asc')
    {
        // Select the necessary fields from comments, users, and recipes
        $this->select('comments.*, users.Username, recipes.Title')
            ->join('users', 'users.ID = comments.UserID', 'left')
            ->join('recipes', 'recipes.ID = comments.RecipeID', 'left');
    
        // Apply search filter if provided
        if ($search) {
            $this->like('Text', $search);
        }
    
        // Apply sorting
        $this->orderBy($sortField, $sortOrder);
    
        // Paginate results
        return $this->paginate($perPage, 'default', $page);
    }
}
?>