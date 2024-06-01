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
    <title>Q&A 관리</title>

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
        .question-list {
            list-style: none;
            padding: 0;
        }
        .question-item {
            padding: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            transition: background-color 0.3s;
        }
        .question-item:hover {
            background-color: #f8f9fa;
        }
        .question-title {
            font-size: 18px;
            font-weight: bold;
        }
        .question-date {
            font-size: 15px;
            color: #888;
            text-align: right;
        }
        .question-content {
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
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itemsPerPage = 7;
            const questionList = document.getElementById('questionList');
            const paginationContainer = document.getElementById('pagination');

            async function fetchQuestions() {
                const response = await fetch('/backend/QnA_all.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                return data.questions; // questions 배열을 반환
            }

            function showPage(questions, page) {
                questionList.innerHTML = '';
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = questions.slice(start, end);

                pageItems.forEach(question => {
                    const questionItem = document.createElement('div');
                    questionItem.classList.add('question-item');
                    questionItem.addEventListener('click', () => {
                        window.location.href = `/admin/QnA_detail.php?id=${question.id}`;
                    });

                    const questionTitle = document.createElement('div');
                    questionTitle.classList.add('question-title');
                    questionTitle.textContent = question.title;

                    const questionDate = document.createElement('div');
                    questionDate.classList.add('question-date');
                    questionDate.textContent = question.question_date;

                    const questionContent = document.createElement('div');
                    questionContent.classList.add('question-content');
                    questionContent.textContent = question.content;

                    questionItem.appendChild(questionTitle);
                    questionItem.appendChild(questionDate);
                    questionItem.appendChild(questionContent);

                    questionList.appendChild(questionItem);
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
                    pageButton.addEventListener('click', () => showPage(currentQuestions, i));
                    paginationContainer.appendChild(pageButton);
                }
            }

            let currentQuestions = [];

            fetchQuestions().then(questions => {
                currentQuestions = questions;
                const pageCount = Math.ceil(questions.length / itemsPerPage);
                createPagination(pageCount);
                showPage(questions, 1);
            }).catch(error => {
                console.error('Error fetching questions:', error);
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
            <h3>Q&A</h3>
            <div class="question-list" id="questionList">
                <!-- 질문 항목이 여기에 추가됩니다 -->
            </div>
            <div class="pagination" id="pagination">
                <!-- 페이지네이션 버튼이 여기에 추가됩니다 -->
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
