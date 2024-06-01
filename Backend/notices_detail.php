<?php
header('Content-Type: application/json');

include 'connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo json_encode(['error' => 'Invalid notice ID']);
    exit;
}

$sql= "SELECT id, notice_date, title, content, body, filepath, original_filename FROM Notice WHERE id = $id";
$result = $connect->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'Notice not found']);
    exit;
}

$notice = $result->fetch_assoc();
echo json_encode($notice);

$result->close();
$connect->close();
?>
