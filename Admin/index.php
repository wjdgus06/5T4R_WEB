<?php
session_start();

// 관리자 권한이 없으면 로그인 페이지나 다른 페이지로 리다이렉트
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /signin');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .content {
            padding: 20px;
        }
        .logo a {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        
    </style>
</head>
<body>
        <div class="sidebar">
            <div class="logo">
                <a href="/admin"> <!-- 메인 페이지 링크로 변경 -->
                    <img src="image/admin-logo.gif" alt="관리자 페이지 로고">
                    <span>관리자 페이지</span>
                </a>
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="/admin/notice.php" id="notice-management">공지관리</a></li>
                    <li><a href="/admin/reservation.php" id="reservation-management">예약관리</a></li>
                    <li><a href="/admin/QnA.php" id="qa">Q&A</a></li>
                </ul>
            </nav>
        </div>
        
        <main class="main-content">
            <header class="header">
                <div class="user-info">
                    <span>ADMIN</span>
                    <img src="image/administrator.png" alt="Admin Icon">
                </div>
            </header>
        </main>
</body>
</html>
