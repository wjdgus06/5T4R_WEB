<?php

$json_str = file_get_contents('php://input');
$data = json_decode($json_str);

$title = $data->title;
$content = $data->content;
$userid = $data->user_id;

$stmt = $connect->prepare("INSERT INTO QnA (title, question_body, user_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $userid);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

$stmt->close();
$connect->close();
?>
