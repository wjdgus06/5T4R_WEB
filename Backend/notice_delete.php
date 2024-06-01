<?php

parse_str(file_get_contents("php://input"), $_DELETE);
header('Content-Type: application/json');

error_log("DELETE data: " . json_encode($_DELETE));

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT filepath FROM Notice WHERE id = '$id'";
    $result = $connect->query($sql);
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filePath = $row['filepath'];
    
        $sqlDelete = "DELETE FROM Notice WHERE id = '$id'";
        if ($connect->query($sqlDelete) === TRUE) {
    
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    echo json_encode(['success' => true, 'message' => 'Record and file deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete file.']);
                }
            }
            else {
                echo json_encode(['success' => true, 'message' => 'Record deleted successfully, file not found.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting record: ' . $connect->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No record found with id ' . $noticeId]);
    }
}

$connect->close();
?>
