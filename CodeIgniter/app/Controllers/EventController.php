<?php
namespace App\Controllers;
use App\Models\EventModel;
use CodeIgniter\API\ResponseTrait; // To handle JSON responses

class EventController extends BaseController{
    use ResponseTrait; 

    public function index()
    {
        return view('calendar'); // Load and return the event view
    }

    public function fetchEvents()
    {
        $model = new EventModel();
        $events = $model->findAll(); // Fetch all events

        return $this->respond($events, 200); // Respond with JSON and 200 status code
    }

    public function addEvent()
    {
        $model = new EventModel();
        $data = $this->request->getJson(); // Get data from POST request

        if ($model->insert($data)) {
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Event saved successfully'
                ]
            ];
            return $this->respondCreated($response); // Respond with 201 Created status
        } else {
            return $this->fail($model->errors()); // Respond with validation errors
        }
    }

    public function deleteEvent($id = null)
    {
        $model = new EventModel();
        $event = $model->find($id);
        if ($event === null) {
            return $this->failNotFound('Event not found');
        }

        if ($model->delete($id)) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Event deleted successfully'
                ]
            ];
            return $this->respond($response);
        } else {
            return $this->fail('Could not delete event');
        }
    }
}