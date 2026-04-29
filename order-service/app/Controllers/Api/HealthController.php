<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class HealthController extends ResourceController
{
    public function index()
    {
        return $this->respond([
            'service' => 'OrderService',
            'status' => 'up',
            'timestamp' => time()
        ]);
    }
}
