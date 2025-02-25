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
        $events = $this->eventModel->findAll();
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
        $this->eventModel->delete($id);
        return $this->response->setJSON(['success' => true]);
    }
}