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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>공지관리</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">

    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .header {
            width: calc(100% - 250px);
            background-color: #fff;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .custom-container {
            margin-top: 70px; /* Add margin to avoid overlap with fixed header */
        }
        .notice-list {
            list-style: none;
            padding: 0;
        }
        .notice-item {
            padding: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            transition: background-color 0.3s;
        }
        .notice-item:hover {
            background-color: #f8f9fa;
        }
        .notice-title {
            font-size: 18px;
            font-weight: bold;
        }
        .notice-date {
            font-size: 15px;
            color: #888;
            text-align: right;
        }
        .notice-content {
            font-size: 13px;
            color: #666;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .page-item {
            display: inline-block;
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }
        .page-item.active {
            background-color: #6e73eb;
            color: white;
        }
        .notice-form {
            display: none;
            margin-top: 20px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itemsPerPage = 4;
            const noticeList = document.getElementById('noticeList');
            const paginationContainer = document.getElementById('pagination');
            const addNoticeBtn = document.getElementById('add-notice-btn');
            const noticeForm = document.getElementById('notice-form-container');

            async function fetchNotices() {
                const response = await fetch('/backend/notices_api.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const notices = await response.json();
                return notices;
            }

            function showPage(notices, page) {
                noticeList.innerHTML = '';
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = notices.slice(start, end);

                pageItems.forEach(notice => {
                    const noticeItem = document.createElement('div');
                    noticeItem.classList.add('notice-item');
                    noticeItem.addEventListener('click', () => {
                        window.location.href = `/admin/notice_detail.php?id=${notice.id}`;
                    });

                    const noticeTitle = document.createElement('div');
                    noticeTitle.classList.add('notice-title');
                    noticeTitle.textContent = notice.title;

                    const noticeDate = document.createElement('div');
                    noticeDate.classList.add('notice-date');
                    noticeDate.textContent = notice.notice_date;

                    const noticeContent = document.createElement('div');
                    noticeContent.classList.add('notice-content');
                    noticeContent.textContent = notice.content;

                    noticeItem.appendChild(noticeTitle);
                    noticeItem.appendChild(noticeDate);
                    noticeItem.appendChild(noticeContent);

                    noticeList.appendChild(noticeItem);
                });

                document.querySelectorAll('.page-item').forEach((button, index) => {
                    button.classList.remove('active');
                    if (index === page - 1) {
                        button.classList.add('active');
                    }
                });
            }

            function createPagination(pageCount) {
                paginationContainer.innerHTML = '';
                for (let i = 1; i <= pageCount; i++) {
                    const pageButton = document.createElement('div');
                    pageButton.textContent = i;
                    pageButton.classList.add('page-item');
                    pageButton.addEventListener('click', () => showPage(currentNotices, i));
                    paginationContainer.appendChild(pageButton);
                }
            }

            let currentNotices = [];

            fetchNotices().then(notices => {
                currentNotices = notices;
                const pageCount = Math.ceil(notices.length / itemsPerPage);
                createPagination(pageCount);
                showPage(notices, 1);
            }).catch(error => {
                console.error('Error fetching notices:', error);
            });

            addNoticeBtn.addEventListener('click', function() {
                if (noticeForm.style.display === 'none' || noticeForm.style.display === '') {
                    noticeForm.style.display = 'block';
                } else {
                    noticeForm.style.display = 'none';
                }
            });

            async function submitNotice() {
                const formElement = document.getElementById('notice-form');
                const formData = new FormData(formElement);

                try {
                    const response = await fetch('/backend/notices_api.php', {
                        method: 'POST',
                        body: formData  // JSON 대신 FormData 사용
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    noticeForm.style.display = 'none';

                    formElement.reset();
                    // 공지사항 목록 새로 고침
                    fetchNotices().then(notices => {
                        currentNotices = notices;
                        const pageCount = Math.ceil(notices.length / itemsPerPage);
                        createPagination(pageCount);
                        showPage(notices, 1);
                    }).catch(error => {
                        console.error('Error fetching notices:', error);
                    });

                } catch (error) {
                    console.error('Error:', error);
                }
            }

            document.getElementById('submit-notice-btn').addEventListener('click', function(event) {
                event.preventDefault();
                submitNotice();
            });
        });
    </script>
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
    
    <div class="main-content">
        <header class="header">
            <div class="user-info">
                <span>ADMIN</span>
                <img src="image/administrator.png" alt="Admin Icon">
            </div>
        </header>

        <div class="custom-container">
            <h3>공지사항</h3>
            <div class="notice-list" id="noticeList">
                <!-- 공지사항 항목이 여기에 추가됩니다 -->
            </div>
            <div class="pagination" id="pagination">
                <!-- 페이지네이션 버튼이 여기에 추가됩니다 -->
            </div>
            <button id="add-notice-btn" class="btn btn-primary">공지사항 등록</button>
            <div class="notice-form" id="notice-form-container">
                <h3>공지사항 작성</h3>
                <form id="notice-form" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">제목</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">날짜</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="summary" class="form-label">요약</label>
                        <input type="text" class="form-control" id="summary" name="summary" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">본문</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">첨부파일</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <button type="submit" class="btn btn-success" id="submit-notice-btn">업로드</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
