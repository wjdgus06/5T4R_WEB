<?php
session_start();

// 관리자 권한이 없으면 로그인 페이지나 다른 페이지로 리다이렉트
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /signin.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>공지사항 상세보기</title>

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
        .main-content {
            margin-top: 60px; /* Header height + padding */
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            overflow-y: auto;
        }
        .file-info{
            margin-top:20px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const noticeId = new URLSearchParams(window.location.search).get('id');

            async function fetchNoticeDetail(id) {
                const response = await fetch(`/backend/notices_detail.php?id=${noticeId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const notice = await response.json();
                return notice;
            }

            async function populateNoticeDetail(id) {
                try {
                    const notice = await fetchNoticeDetail(id);

                    document.getElementById('title').value = notice.title;
                    document.getElementById('date').value = notice.notice_date;
                    document.getElementById('summary').value = notice.content;
                    document.getElementById('content').value = notice.body;

                    if (notice.original_filename) {
                        document.getElementById('file-info').textContent = `현재 첨부된 파일: ${notice.original_filename}`;
                    } else {
                        document.getElementById('file-info').textContent = 'No file attached';
                    }
                } catch (error) {
                    console.error('Error fetching notice detail:', error);
                }
            }

            populateNoticeDetail(noticeId);

            // update
            document.getElementById('update-notice-btn').addEventListener('click', async function(event) {
                event.preventDefault();

                const data = {
                    id: noticeId,
                    title: document.getElementById('title').value,
                    date: document.getElementById('date').value,
                    summary: document.getElementById('summary').value,
                    content: document.getElementById('content').value
                };

                const fileInput = document.getElementById('file');
                try {
                    const response = await fetch('/backend/notices_api.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        // 파일이 있는 경우, 파일 업로드 진행 (POST 요청)
                        if (fileInput.files.length > 0) {
                            const formData = new FormData();
                            formData.append('file', fileInput.files[0]);
                            formData.append('id', noticeId); // 파일과 함께 공지사항 ID 전달

                            const uploadResponse = await fetch('/backend/file_update.php', {
                                method: 'POST',
                                body: formData
                            });

                            if (!uploadResponse.ok) {
                                throw new Error(`HTTP error! status: ${uploadResponse.status}`);
                            }

                            const uploadResult = await uploadResponse.json();
                        }

                        window.location.href = '/admin/notice.php'; // 성공 시 페이지 이동
                    } else {
                        console.error('Update failed:', result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // delete
            document.getElementById('delete-notice-btn').addEventListener('click', async function(event) {
                event.preventDefault();

                const noticeId = new URLSearchParams(window.location.search).get('id');

                try {
                    const response = await fetch(`/backend/notices_api.php?id=${noticeId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.success) {
                        window.location.href = '/admin/notice.php';
                    } else {
                        console.error('Error:', result.message);
                    }

                } catch (error) {
                    console.error('Error:', error);
                }
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
            <h3>공지사항 상세보기</h3>
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
                    <div class="file-info" id="file-info"></div>
                </div>
                <button type="submit" class="btn btn-success" id="update-notice-btn">수정</button>
                <button type="button" class="btn btn-danger" id="delete-notice-btn">삭제</button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
