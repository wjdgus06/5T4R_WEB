<?php
session_start();
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => '로그아웃 성공']);
?>