<?php
namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['UserID', 'RecipeID', 'Text', 'Date'];

}
?>