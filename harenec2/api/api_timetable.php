<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config.php';
require_once '../timetable.php';
// Create an instance of the Employee class
$timetable = new Timetable($pdo);
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
// Get the requested endpoint
$endpoint = getEndpoint($_SERVER['QUERY_STRING']);

// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if ($endpoint === '/timetable') {
            $timetable = $timetable->getAllTimetableActions();
            echo json_encode($timetable, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } elseif (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Get timetable action by ID
            $taId = $matches[1];
            $ta = $timetable->getTimetableActionById($taId);
            echo json_encode($ta);
        }
        break;
    case 'POST':
        if ($endpoint === '/timetableAction') {
            // Add new timetable Action
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $timetable->addTimetableAction($data);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'PUT':
        if (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Update timetableAction by ID
            $timetableActionId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $timetable->updateTimetableAction($timetableActionId, $data);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'DELETE':
        if (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Delete timetableAction by ID
            $timetableActionId = $matches[1];
            $result = $timetable->deleteTimetableAction($timetableActionId);
            echo json_encode(['success' => $result]);
        }
        break;
}

function getEndpoint($link) {

    return "/" . rtrim(explode("/", $link, 5)[4], '/');
}