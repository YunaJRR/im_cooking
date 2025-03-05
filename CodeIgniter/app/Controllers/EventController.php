<?php

namespace App\Controllers;

use App\Models\EventModel;

class EventController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    public function index()
    {
        return view('calendar');
    }
    public function fetchEvents()
    {
        $events = $this->eventModel->getActiveEvents();

        
        return $this->response->setJSON($events);
    }

    public function addEvent()
    {
        $data = $this->request->getPost();
        $this->eventModel->insert($data);
        return $this->response->setJSON(['success' => true]);
    }

    public function deleteEvent($id)
    {
        $eventModel = new EventModel();
        $eventData = [
            'deletiondate' => date('Y-m-d H:i:s')
        ];
        // Update the user to mark as deleted
        if ($eventModel->update($id, $eventData)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
        
    }
}