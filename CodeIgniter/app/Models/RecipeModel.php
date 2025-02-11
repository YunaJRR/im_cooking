<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['CreationDate', 'Title', 'Description', 'Instructions', 'Image', 'UserID'];


}
?>