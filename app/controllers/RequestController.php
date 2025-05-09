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
                ResponseService::Send(['message' => 'Request sent successfully'], 200);
            } else {
                ResponseService::Error('Failed to send request', 500);
            }
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }
    // owner: get all send requests
    public function getRequestsByOwner()
    {

        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'owner') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }
        try {
            $requests = $this->requestModel->getRequestsByOwner($user->id);
            ResponseService::Send($requests);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage());
        }
    }

    // owner: cancel request
    public function cancelRequest($requestId)
    {

        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'owner') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }
        
        try {
            $result = $this->requestModel->cancelRequest($requestId, $user->id);
            if ($result) {
                ResponseService::Send(['message' => 'Request cancelled']);
            } else {
                ResponseService::Error('Failed to cancel request');
            }
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage());
        }
    }


    // walker : get requests
    public function getRequestsByWalker()
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'walker') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }

        try {
            $requests = $this->requestModel->getRequestsByWalker($user->id);
            ResponseService::Send($requests);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage());
        }
    }

    // walker : accept request -- turn request to appointment.
    public function acceptRequest($requestId)
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'walker') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }

        try {
            $result = $this->requestModel->acceptRequest($requestId);
            if ($result) {
                ResponseService::Send(['message' => 'Request accepted']);
            } else {
                ResponseService::Error('Failed to accept request');
            }
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage());
        }
    }

    // walker : reject request 
    public function rejectRequest($requestId)
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'walker') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }

        try {
            $result = $this->requestModel->rejectRequest($requestId, $user->id);
            if ($result) {
                ResponseService::Send(['message' => 'Request rejected']);
            } else {
                ResponseService::Error('Failed to reject request');
            }
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage());
        }
    }
}
