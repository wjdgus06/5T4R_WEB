<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);


header('Content-Type: application/json');

$user_id = $data['user_id'];
$password = $data['password'];
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$phone = $data['phone'];
$email = $data['email'];

// Check if user_id already exists
$check_sql = "SELECT user_id FROM users WHERE user_id = '$user_id'";
$check_result = $connect->query($check_sql);

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'user_id가 동일합니다.']);
} else {
    $sql = "insert into users (user_id, FirstName, LastName, password, phone, email) Values ('$user_id', '$firstname', '$lastname', '$password', '$phone', '$email')";
    $result = $connect->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => '회원가입 성공']);
    } else {
        echo json_encode(['success' => false, 'message' => '회원가입 실패']);
    }
}

$connect->close();
?>
