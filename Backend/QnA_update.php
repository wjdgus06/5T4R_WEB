<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'];
$body = $data['answer_body'];

$sql = "update QnA set answer_body='$body', status='answered' where id = '$id'";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '수정 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '수정 실패']);
}

$connect->close();
?>
