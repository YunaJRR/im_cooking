<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class EventModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['title', 'start', 'end', 'deletiondate'];
 
    public function getEvents()
    {
        return $this->findAll();
    }
    public function getActiveEvents()
    {
        return $this->where('DeletionDate IS NULL OR DeletionDate = "0000-00-00 00:00:00"')->findAll();
    }
}
