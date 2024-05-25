<?php

parse_str(file_get_contents("php://input"), $_DELETE);
header('Content-Type: application/json');

error_log("DELETE data: " . json_encode($_DELETE));

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM Notice WHERE id = '$id'";

    error_log("SQL query: " . $sql);
    $result = $connect->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => '삭제 성공']);
    } else {
        echo json_encode(['success' => false, 'message' => '삭제 실패']);
    }
}
else{
    echo json_encode(['error' => 'ID is required']);
}



$connect->close();
?>
