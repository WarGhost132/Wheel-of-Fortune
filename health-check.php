<?php

use Backend\HealthCheck\HealthCheck;

header('Content-Type: application/json');
$health_check = new HealthCheck();
$response = $health_check->performHealthCheck();
echo json_encode($response, JSON_PRETTY_PRINT);
