<?php

session_start();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = $data['user_id'];
$password = $data['password'];

$sql = "SELECT * FROM users WHERE user_id = '$user_id' AND password = '$password'";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $row['role'];
    echo json_encode(['success' => true, 'message' => '로그인 성공', 'role' => $row['role']]);
} else {
    echo json_encode(['success' => false, 'message' => '잘못된 사용자 ID 또는 비밀번호입니다.']);
}

$connect->close();
?>
