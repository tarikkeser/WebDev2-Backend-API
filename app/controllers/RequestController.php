<?php

namespace App\Controllers;

use App\Models\RequestModel;
use App\Services\ResponseService;
use Exception;

class RequestController extends Controller
{
    private $requestModel;

    public function  __construct()
    {
        $this->requestModel = new RequestModel();
    }

    // owner: send request to a walker
    public function createRequest()
    {
        $user = $this->getAuthenticatedUser();
        $data = $this->decodePostData();

        $this->validateInput([
            'dog_id',
            'walker_id',
            'start_time',
            'end_time',
        ], $data);
        try {
            $result = $this->requestModel->createRequest(
                $data['dog_id'],
                $data['walker_id'],
                $user->id,
                $data['start_time'],
                $data['end_time']
            );
            if ($result) {
                ResponseService::send(200, "Request sent successfully");
            } else {
                ResponseService::send(500, "Failed to send request");
            }
        } catch (Exception $e) {
            ResponseService::send(500, "Error: " . $e->getMessage());
        }
    }

    // owner: cancel request

    // walker : accept request -- turn request to appointment.
    // walker : reject request 

}
