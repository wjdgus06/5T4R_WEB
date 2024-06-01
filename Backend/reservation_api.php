<?php
include 'connect.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        require 'reservations_all.php';
        break;

    case 'POST':
        require 'reservation_insert.php';
        break;

    case 'PUT':
        require 'reservation_update.php';
        break;

    case 'DELETE':
        require 'reservation_delete.php';
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>