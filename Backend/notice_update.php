<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'];
$title = $data['title'];
$notice_date = $data['date'];
$content = $data['summary'];
$body = $data['content'];

$stmt = $connect->prepare("UPDATE Notice SET title=?, notice_date=?, content=?, body=? WHERE id=?");
$stmt->bind_param("ssssi", $title, $notice_date, $content, $body, $id);
$result = $stmt->execute();

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '수정 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '수정 실패']);
}

$connect->close();
?>
