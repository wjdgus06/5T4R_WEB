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
            margin-top: 70px;
        }
        .question-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .question-date {
            font-size: 1rem;
            color: #888;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .question-body {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 50px;
        }
        .answer-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .answer-body {
            font-size: 1rem;
            color: #333;
            margin-bottom: 20px;
            white-space: pre-line;
        }
        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .back-button button {
            background-color: #0a488b;
            color: white;
            border: none;
            padding: 10px 50px;
            font-size: 1rem;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
        .back-button button:hover {
            background-color: #003d80;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            const questionId = params.get('id');

            async function fetchQuestionDetail() {
                const response = await fetch(`/backend/QnA_detail.php?id=${questionId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const question = await response.json();
                return question;
            }

            fetchQuestionDetail().then(question => {
                document.querySelector('.question-title').textContent = question.title;
                document.querySelector('.question-date').textContent = question.created_at;
                document.querySelector('.question-body').textContent = question.question_body;
                document.querySelector('.question-id').textContent =  "user_id: " +question.user_id;

                const answerForm = document.querySelector('.answer-form textarea');
                if (question.status === 'answered') {
                    answerForm.textContent = question.answer_body;
                } else {
                    answerForm.textContent = ''; // 비어있는 상태
                }
            }).catch(error => {
                console.error('Error fetching question detail:', error);
            });

            document.querySelector('.back-button button').addEventListener('click', function() {
                window.location.href = '/admin/QnA.php';
            });

            document.querySelector('.answer-form').addEventListener('submit', async function(event) {
                event.preventDefault();
                const answerBody = document.querySelector('.answer-form textarea').value;
                try {
                    const response = await fetch(`/backend/QnA_api.php`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: questionId,
                            answer_body: answerBody
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // 제출 후 처리 로직 추가
                    const result = await response.json();
                } catch (error) {
                    console.error('Error submitting answer:', error);
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
            <div class="question-title"></div>
            <div class="question-id"></div>
            <div class="question-date"></div>
            <div class="question-body"></div>

            
            <div class="answer-header">Answer</div>
            <form class="answer-form">
                <textarea class="form-control" rows="5"></textarea>
                <div class="back-button">
                    <button type="submit" class="btn btn-success">등록</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
