<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Exception;

class ProfileController extends Controller
{
    private $profileModel;

    public function  __construct()
    {
        $this->profileModel = new User();
    }


    public function getProfile($id)
    {
        try {
            $this->validateIsMe($id);
            $user = $this->profileModel->getUserInfo($id);
            if (!$user) {
                return ResponseService::error('User not found', 404);
            }
             ResponseService::Send($user);
        } catch (Exception $e) {
            return ResponseService::error('An error occurred: ' . $e->getMessage(), 500);
        }
    }
    // Update user profile.
    public function updateProfile($id)
    {
        try {
            $this->validateIsMe($id);
            $data = $this->decodePostData();
            $fields = array_intersect_key($data, array_flip(['name', 'email', 'profile_picture', 'price', 'experience', 'password']));
            if (empty($fields)) {
                return ResponseService::error('No valid fields to update', 400);
            }
            $user = $this->profileModel->updateUser($id, $fields);
            ResponseService::Send($user);
        } catch (Exception $e) {
            return ResponseService::error('An error occurred: ' . $e->getMessage(), 500);
        }
    }
}
