<?php
header('Content-Type: application/json');

include 'connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo json_encode(['error' => 'Invalid question ID']);
    exit;
}

$sql= "SELECT * FROM QnA WHERE id = $id";
$result = $connect->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'question not found']);
    exit;
}

$question = $result->fetch_assoc();
echo json_encode($question);

$result->close();
$connect->close();
?>
