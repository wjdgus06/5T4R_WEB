<?php
$host = "52.79.193.89";
$user = "admin";
$pw = "1234";
$db = "Air_5T4R";

$connect = new mysqli($host, $user, $pw, $db);

if ($connect->connect_error) {
    die("연결 실패: " . $connect->connect_error);
}
?>