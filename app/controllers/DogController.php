<?php

namespace App\Controllers;

use App\Models\DogModel;
use App\Services\ResponseService;


class DogController extends Controller
{

    private $dogModel;

    public function  __construct()
    {
        $this->dogModel = new DogModel();
    }

    public function getDogsByOwner($ownerId)
    {
        $dogs = $this->dogModel->getDogsByOwner($ownerId);
        if (!$dogs) {
            ResponseService::Error('No dogs found for this owner', 404);
            return;
        }
        ResponseService::Send($dogs);
    }

    public function getDog($id)
    {
        $dog = $this->dogModel->getDog($id);
        if (!$dog) {
            ResponseService::Error('Dog not found', 404);
            return;
        }
        ResponseService::Send($dog);
    }

    public function createDog()
    {
        $user = $this->getAuthenticatedUser();
        // decode means to convert the JSON string into a PHP array
        $data = $this->decodePostData();

        $this->validateInput(['name', 'breed', 'age', 'size'], $data);

        $data['owner_id'] = $user->id;
        $data['photo'] = $data['photo'] ?? null;

        $newDog = $this->dogModel->createDog($data);

        if (!$newDog) {
            ResponseService::Error('Failed to create dog', 500);
            return;
        }
        // Send the response with the created dog data to the client.(JSON format) 
        // for example, front end, debugging, etc.
        ResponseService::Send($newDog);
        
    }

    // check update. there is no image in the update.
    public function updateDog($id)
    {
        $dog = $this->dogModel->getDog($id);

        if (!$dog) {
            ResponseService::Error('Dog not found', 404);
            return;
        }
        $this->validateIsMe($dog['owner_id']);

        // decode means to convert the JSON string into a PHP array
        $data = $this->decodePostData();
        $this->validateInput(['name', 'breed', 'age', 'size'], $data);
        $data['photo'] = $data['photo'] ?? $dog['photo'];

        $updatedDog = $this->dogModel->updateDog($id, $data);
        ResponseService::Send($updatedDog);
    }

    public function deleteDog($id)
    {
        $user = $this->getAuthenticatedUser();
        $dog = $this->dogModel->getDog($id);

        if (!$dog) {
            ResponseService::Error('Dog not found', 404);
            return;
        }
        $this->validateIsMe($dog['owner_id']);

        $this->dogModel->deleteDog($id);
        ResponseService::Send(['message' => 'Dog deleted successfully']);
    }
}
