<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = $data['user_id'];
$password = $data['password'];
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$phone = $data['phone'];
$email = $data['email'];

$sql = "insert into users (id, FirstName, LastName, password, phone, email) Values ('$user_id', '$firstname', '$lastname', '$password', '$phone', '$email')";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '회원가입 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '회원가입 실패']);
}

$connect->close();
?>
