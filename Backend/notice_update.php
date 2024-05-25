<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'];
$title = $data['title'];
$notice_date = $data['date'];
$content = $data['summary'];
$body = $data['content'];

$sql = "update Notice set title='$title', notice_date='$notice_date', content='$content', body='$body' where id = '$id'";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '수정 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '수정 실패']);
}

$connect->close();
?>
