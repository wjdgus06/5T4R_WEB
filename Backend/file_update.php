<?php

include 'connect.php';

$id = $_POST['id']; // 공지 ID
$filePath = '';
$originalName = '';

if (isset($_FILES['file'])) {
    $uploadDir = '/5T4R/uploads/';
    $originalName = basename($_FILES['file']['name']);
    $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '.' . $fileExtension;
    $filePath = $uploadDir . $uniqueFileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        $sql = "UPDATE Notice SET filepath='$filePath', original_filename='$originalName' WHERE id='$id'";
        if ($connect->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'File uploaded and record updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $connect->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
}

$connect->close();
?>
