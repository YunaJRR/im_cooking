<?php
namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Text', 'Date', 'DeletionDate'];

    public function getComments($search = null, $perPage = 3, $page = 1)
    {
        $this->select('comments.*, users.Username')
         ->join('users', 'users.ID = comments.UserID', 'left');
        $this->select('comments.*, recipes.Title')
         ->join('recipes', 'recipes.ID = comments.RecipeID', 'left');
        if ($search) {
            return $this->like('Text', $search)
                        ->paginate($perPage, 'default', $page);
        }
        return $this->paginate($perPage);
    }
}
?>