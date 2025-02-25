<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class EventModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['title', 'start', 'end'];
 
    public function getEvents()
    {
        return $this->findAll();
    }
}
