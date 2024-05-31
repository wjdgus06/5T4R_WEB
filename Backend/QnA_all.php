<?php
header('Content-Type: application/json');

include 'connect.php';

// QnA 테이블에서 데이터를 가져오기
$sql = "SELECT * FROM QnA";
$result = $connect->query($sql);

$qna=[];
while($row=$result->fetch_assoc()){
    $qna[]=$row;
}
// 데이터 반환
echo json_encode(['questions' => $qna]);

$result->close();
$connect->close();
?>
