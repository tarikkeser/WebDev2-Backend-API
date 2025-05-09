<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Services\ResponseService;
use Exception;

class AppointmentController extends Controller
{
    private $appointmentModel;

    public function  __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    // get all appointments for walker
    public function getAllAppointmentsForWalker()
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'walker') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }

        try {
            $appointments = $this->appointmentModel->getAppointmentsByWalker($user->id);
            ResponseService::send($appointments);
        } catch (Exception $e) {
            ResponseService::send("Error: " . $e->getMessage());
        }
    }
    // get all appointments for owner
    public function getAllAppointmentsForOwner()
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'owner') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }
        try {
            $appointments = $this->appointmentModel->getAppointmentsByOwner($user->id);
            ResponseService::send( $appointments);
        } catch (Exception $e) {
            ResponseService::send( "Error: " . $e->getMessage());
        }
    }
    // cancel appointment
    public function cancelAppointment($appointmentId)
    {
        $user = $this->getAuthenticatedUser();
        if ($user->role !== 'owner' && $user->role !== 'walker') {
            ResponseService::Error('Unauthorized', 403);
            return;
        }
        try {
            $this->appointmentModel->cancelAppointment($appointmentId);
            ResponseService::send("Appointment cancelled successfully");
        } catch (Exception $e) {
            ResponseService::send("Error: " . $e->getMessage());
        }
    }
}