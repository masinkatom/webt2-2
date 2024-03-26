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
$endpoint = $_SERVER['QUERY_STRING'];


// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if ($endpoint === 'path=/harenec2/api/api_timetable.php/timetableAction') {
            // Get all timetableActions
            $timetable = $timetable->getAllTimetableActions();
            echo json_encode($timetable);
        } elseif (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Get employee by ID
            $employeeId = $matches[1];
            $employee = $timetable->getTimetableActionById($employeeId);
            echo json_encode($employee);
        }
        break;
    case 'POST':
        if ($endpoint === '/timetableAction') {
            // Add new employee
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $timetable->addTimetableAction($data);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'PUT':
        if (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Update employee by ID
            $employeeId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $timetable->updateTimetableAction($employeeId, $data);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'DELETE':
        if (preg_match('/^\/timetableAction\/(\d+)$/', $endpoint, $matches)) {
            // Delete employee by ID
            $employeeId = $matches[1];
            $result = $timetable->deleteTimetableAction($employeeId);
            echo json_encode(['success' => $result]);
        }
        break;
}