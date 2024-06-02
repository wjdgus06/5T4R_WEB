<?php
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

$id = $data['id'];
$title = isset($data['title']) ? $data['title'] : null;
$question_body = isset($data['question_body']) ? $data['question_body'] : null;
$answer_body = isset($data['answer_body']) ? $data['answer_body'] : null;

$sql = "UPDATE QnA SET ";
$params = [];

// 질문 내용 업데이트
if ($question_body !== null) {
    $params[] = "title = '" . $connect->real_escape_string($title) . "'";
    $params[] = "question_body = '" . $connect->real_escape_string($question_body) . "'";
}

// 답변 내용 업데이트
if ($answer_body !== null) {
    $params[] = "answer_body = '" . $connect->real_escape_string($answer_body) . "'";
    $params[] = "status = 'answered'";
}

if (!empty($params)) {
    $sql .= implode(', ', $params);
    $sql .= " WHERE id = '" . $connect->real_escape_string($id) . "'";

    $result = $connect->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => '수정 성공']);
    } else {
        echo json_encode(['success' => false, 'message' => '수정 실패: ' . $connect->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data to update']);
}

$connect->close();
?>
