<?php

/**
 * Setup
 */

// require autoload file to autoload vendor libraries
require_once __DIR__ . '/../vendor/autoload.php';

// require local classes
use App\Services\EnvService;
use App\Services\ErrorReportingService;
use App\Services\ResponseService;
use App\Controllers\AuthController;
use App\Controllers\DogController;
use App\Controllers\WalkerController;
use App\Controllers\AppointmentController;
use App\Controllers\RequestController;
use App\Controllers\ProfileController;  

// require vendor libraries
use Steampixel\Route;

// initialize global environment variables
EnvService::Init();

// initialize error reporting (on in local env)
ErrorReportingService::Init();

// set CORS headers
ResponseService::SetCorsHeaders();

/**
 * Main application routes
 */
// top level fail-safe try/catch
try {
    /**
     * Auth routes
     */
    Route::add('/auth/register', function () {
        $authController = new AuthController();
        $authController->register();
    }, ["post"]);

    Route::add('/auth/login', function () {
        $authController = new AuthController();
        $authController->login();
    }, ["post"]);

    Route::add('/auth/me', function () {
        $authController = new AuthController();
        $authController->me();
    }, ["get"]);

    /**
     * Dog routes
     */

    // get all dogs by owner id
    Route::add('/dog/owner/([0-9]*)', function ($ownerId) {
        $dogController = new DogController();
        $dogController->getDogsByOwner($ownerId);
    }, "get");

    // get dog by id
    Route::add('/dog/([0-9]*)', function ($id) {
        $dogController = new DogController();
        $dogController->getDog($id);
    }, "get");
    // create a new dog
    Route::add('/dog', function () {
        $dogController = new DogController();
        $dogController->createDog();
    }, "post");
    // update dog 
    Route::add('/dog/([0-9]*)', function ($id) {
        $dogController = new DogController();
        $dogController->updateDog($id);
    }, "put");
    // delete  dog
    Route::add('/dog/([0-9]*)', function ($id) {
        $dogController = new DogController();
        $dogController->deleteDog($id);
    }, "delete");

    //**walkers */
    // get all walkers
    Route::add('/walkers', function () {
        $walkerController = new WalkerController();
        $walkerController->getAllWalkers();
    }, "get");

    //**request routes */
    // REQUESTS-OWNER
    // owner : send request to a walker
    Route::add('/request', function () {
        $requestController = new RequestController();
        $requestController->createRequest();
    }, "post");
    // owner : get sent requests
    Route::add('/request/owner', function () {
        $requestController = new RequestController();
        $requestController->getRequestsByOwner();
    }, "get");
    // owner : cancel request
    Route::add('/request/([0-9]*)', function ($requestId) {
        $requestController = new RequestController();
        $requestController->cancelRequest($requestId);
    }, "delete");
    
    // -- REQUEST-WALKER
    // walker : get received requests
    Route::add('/request/walker', function () {
        $requestController = new RequestController();
        $requestController->getRequestsByWalker();
    }, "get");
    // walker : accept request -- turn request to appointment.
    Route::add('/request/accept/([0-9]*)', function ($requestId) {
        $requestController = new RequestController();
        $requestController->acceptRequest($requestId);
    }, "post");
    // walker : reject request
    Route::add('/request/reject/([0-9]*)', function ($requestId) {
        $requestController = new RequestController();
        $requestController->rejectRequest($requestId);
    }, "post");


    /**
     * Appointment routes
     */
    // get all appointments for walker
    Route::add('/appointments/walker', function () {
        $appointmentController = new AppointmentController();
        $appointmentController->getAllAppointmentsForWalker();
    }, "get");
    // get all appointments for owner
    Route::add('/appointments/owner', function () {
        $appointmentController = new AppointmentController();
        $appointmentController->getAllAppointmentsForOwner();
    }, "get");
    // cancel appointment
    Route::add('/appointments/([0-9]*)', function ($appointmentId) {
        $appointmentController = new AppointmentController();
        $appointmentController->cancelAppointment($appointmentId);
    }, "delete");

    // PROFILE ROUTES//
    // get profile information
    Route::add('/profile/([0-9]*)', function ($id) {
        $profileController = new ProfileController();
        $profileController->getProfile($id);
    }, "get");
    // update profile information
    Route::add('/profile/([0-9]*)', function ($id) {
        $profileController = new ProfileController();
        $profileController->updateProfile($id);
    }, "put");




    /**
     * 404 route handler
     */
    Route::pathNotFound(function () {
        ResponseService::Error("route is not defined", 404);
    });
} catch (\Throwable $error) {
    if ($_ENV["environment" == "LOCAL"]) {
        var_dump($error);
    } else {
        error_log($error);
    }
    ResponseService::Error("A server error occurred");
}


Route::run();
