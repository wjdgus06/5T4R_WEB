<?php
include 'connect.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        require 'notices.php';
        break;
    case 'POST':
        require 'upload.php';
        break;
    case 'PUT':
        require 'notice_update.php';
        break;
    case 'DELETE':
        require 'notice_delete.php';
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>