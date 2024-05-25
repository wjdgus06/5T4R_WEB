<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$title = $data['title'];
$notice_date = $data['date'];
$content = $data['summary'];
$body = $data['content'];

$sql = "insert into Notice (title, notice_date, content, body) Values ('$title', '$notice_date', '$content', '$body')";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '업로드 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '업로드 실패']);
}

$connect->close();
?>
