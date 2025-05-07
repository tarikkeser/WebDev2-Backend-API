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
    // delete dog
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
