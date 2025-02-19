<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class EventModel extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'PK_ID_EVENT';
    protected $allowedFields = ['TITLE', 'START_DATE', 'END_DATE', 'DESCRIPTION_ES', 'DESCRIPTION_ENG', 'DELETION_DATE'];
 
    public function getEvents($search = null, $perPage = 3, $page = 1)
    {
        if ($search) {
            return $this->like('TITLE', $search)
                        ->paginate($perPage, 'default', $page);
        }
        return $this->paginate($perPage);
    }
}