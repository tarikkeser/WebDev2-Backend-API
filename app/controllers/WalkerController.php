<?php

namespace App\Controllers;

use App\Models\WalkerModel;
use App\Services\ResponseService;


class WalkerController extends Controller
{

    private $walkerModel;

    public function  __construct()
    {
        $this->walkerModel = new WalkerModel();
    }

    // get all walkers
    public function getAllWalkers()
    {
        $walkers = $this->walkerModel->getAllWalkers();
        if (!$walkers) {
            ResponseService::Error('No walkers found', 404);
            return;
        }
        ResponseService::Send($walkers);
    }
}
