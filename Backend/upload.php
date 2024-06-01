<?php

$filePath = '';
$originalName = '';
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $uploadDir = '/5T4R/uploads/';
    $originalName = basename($_FILES['file']['name']); // 원본 파일 이름 추출
    $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION); // 파일 확장자 추출
    $uniqueFileName = uniqid() . '.' . $fileExtension; // 고유한 파일명 생성
    $filePath = $uploadDir . $uniqueFileName; // 파일 저장 경로 설정

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        echo json_encode(['success' => false, 'message' => '파일 업로드 실패']);
        exit;
    }
}

$title = $_POST['title'];
$notice_date = $_POST['date'];
$content = $_POST['summary'];
$body = $_POST['content'];

$sql = "insert into Notice (title, notice_date, content, body, filepath, original_filename) Values ('$title', '$notice_date', '$content', '$body', '$filePath', '$originalName')";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '업로드 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '업로드 실패']);
}

$connect->close();
?>
