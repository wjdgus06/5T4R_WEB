<?php
include 'connect.php';
session_start();

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        require 'QnA.php';
        break;
    case 'PUT':
        require 'QnA_update.php';
        break;
    case 'POST';
        require 'QnA_insert.php';
        break;
    case 'DELETE';
        require 'QnA_delete.php';
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>