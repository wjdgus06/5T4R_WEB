<?php
include 'connect.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        require 'users.php';
        break;
    case 'PUT':
        require 'user_update.php';
        break;
    case 'DELETE':
        require 'user_delete.php';
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>