<?php
header('Content-Type: application/json');
session_start();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = $_SESSION['user_id'];

$password = $data['password'];
$phone = $data['phone'];
$email = $data['email'];
$FirstName = $data['firstName'];
$LastName = $data['lastName'];

$sql = "update users set password='$password', phone='$phone', email='$email', FirstName='$FirstName', LastName='$LastName' where user_id = '$user_id'";
$result = $connect->query($sql);



if ($result) {
    echo json_encode(['success' => true, 'message' => '수정 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '수정 실패']);
}

$connect->close();
?>
