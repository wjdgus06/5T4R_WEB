<?php
// include 'connect.php';
// 공지사항 데이터를 DB에서 가져오기

/*
$sql = "SELECT id, title, date, content FROM notices";
$result = $connect->query($sql);

header('Content-Type: application/json');

$notices = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
    echo json_encode($notices);
} else {
    echo json_encode([]);
}

$connect->close();
*/
header('Content-Type: application/json');
$notices = [
    ["title" => "국제여객 운송약관 개정안내", "date" => "2024-03-29", "content" => "에어프레미아를 이용해 주시는 고객님께 아래와 같이 국제여객 운송약관이 개정됨을 안내 드립니다."],
    ["title" => "2024년 05월 13일 인천(ICN) ↔ 방콕(BKK) YP601/602편 결항 안내", "date" => "2024-03-28", "content" => "2024년 05월 13일 인천(ICN) ↔ 방콕(BKK) YP601/602편 결항 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 4월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."],
    ["title" => "국제선 운임 안내 (2024년 5월)", "date" => "2024-03-27", "content" => "누구나 누릴 수 있는 편안함, 에어프레미아에서 국제선 운임을 안내드립니다."]
    
    // 더 많은 공지사항을 추가하세요.
];

echo json_encode($notices);

?>
