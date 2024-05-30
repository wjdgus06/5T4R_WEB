<?php

header('Content-Type: application/json');

// 세션에서 user_id 가져오기
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // QnA 테이블에서 데이터를 가져오기
    $sql = "SELECT * FROM QnA WHERE user_id = '$user_id'";
    $result = $connect->query($sql);

    $qna=[];
    while($row=$result->fetch_assoc()){
        $qna[]=$row;
    }
    // 데이터 반환
    echo json_encode(['questions' => $qna]);


} catch (Exception $e) {
    // 에러 메시지를 반환하여 디버깅에 도움을 줍니다.
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
}
?>
