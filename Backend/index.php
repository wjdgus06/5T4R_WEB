<?php
header('Content-Type: application/json');

$endpoint = $_SERVER['REQUEST_URI'];

error_log("Request URI: " . $endpoint);

switch ($endpoint) {
    case '/backend/api/users':
        error_log("Including users.php");
        require 'users.php';
        break;
    default:
        error_log("404 Not Found: " . $endpoint);
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
        break;
}
?>

