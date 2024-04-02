<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../thesis.php';
// Create an instance of the Employee class
$thesis = new Thesis();
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
// Get the requested endpoint
$endpoint = getEndpoint($_SERVER['QUERY_STRING']);

// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if (preg_match('/^\/theses\/([a-zA-Z]+)\/(\d+)$/', $endpoint, $matches)) {
            $type = $matches[1];
            $departmentId = $matches[2];
            $theses = $thesis->getAllTheses($departmentId, $type);
            echo json_encode($theses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } 
        elseif (preg_match('/^\/freeTheses\/([a-zA-Z]+)\/(\d+)$/', $endpoint, $matches)) {
            $type = $matches[1];
            $departmentId = $matches[2];
            $theses = $thesis->getAllFreeTheses($departmentId, $type);
            echo json_encode($theses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;
}

function getEndpoint($link) {
    return "/" . rtrim(explode("/", $link, 5)[4], '/');
}