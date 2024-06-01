<?php
header('Content-Type: application/json');

// Resrvation 테이블에서 데이터를 가져오기
$sql = "SELECT * FROM Reservation";
$result = $connect->query($sql);

$reservations=[];
while($row=$result->fetch_assoc()){
    $reservations[]=$row;
}
// 데이터 반환
echo json_encode(['reservations' => $reservations]);

$result->close();
$connect->close();
?>
