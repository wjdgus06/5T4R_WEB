<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = $data['user_id'];
$password = $data['password'];

$sql = "SELECT * FROM users WHERE id = '$user_id' AND password = '$password'";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => '로그인 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '잘못된 사용자 ID 또는 비밀번호입니다.']);
}

$connect->close();
?>
