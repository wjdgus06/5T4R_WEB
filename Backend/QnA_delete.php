<?php
header('Content-Type: application/json');

$questionId = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($questionId)) {
    echo json_encode(['success' => false, 'message' => 'Question ID is required']);
    exit;
}

$sql = "DELETE FROM QnA WHERE id = '$questionId'";
$result = $connect -> query($sql);

if ($result === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Question deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting question: ' . $conn->error]);
}

$connect->close();
?>