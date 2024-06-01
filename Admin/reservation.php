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
    <title>예약 관리</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">

    <!-- Custom Styles -->
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
        .reservation-list {
            list-style: none;
            padding: 0;
        }
        .reservation-item {
            padding: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            flex-direction: center;
            background-color: #fff;
            transition: background-color 0.3s;
            justify-content: space-between;
        }
        .reservation-item:hover {
            background-color: #f8f9fa;
        }
        .reservation-details-container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .reservation-id {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
            font-weight: bold;
        }
        .reservation-details {
            font-size: 15px;
            color: #666;
            margin-top: 5px;
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
        .btn-confirm {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-confirm:hover{
            color:#fff;
            background-color: #179aaf;
            border-color: #179aaf;
        }
        .btn-cancel {
            color: #fff;
            background-color: #ee7682;
            border-color: #ee7682;
        }
        .btn-cancel:hover{
            color:#fff;
            background-color: #e2707b;
            border-color: #e2707b;;
        }

    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itemsPerPage = 4;
            const reservationList = document.getElementById('reservationList');
            const paginationContainer = document.getElementById('pagination');

            async function fetchReservations() {
                const response = await fetch('/backend/reservation_api.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                return data.reservations; // reservations 배열을 반환
            }

            function showPage(reservations, page) {
                reservationList.innerHTML = '';
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageItems = reservations.slice(start, end);

                pageItems.forEach(reservation => {
                    const reservationItem = document.createElement('div');
                    reservationItem.classList.add('reservation-item');

                    const detailsContainer = document.createElement('div');
                    detailsContainer.classList.add('reservation-details-container');
                    
                    const reservationId = document.createElement('div');
                    reservationId.classList.add('reservation-id');
                    reservationId.textContent = reservation.reservation_id;

                    const userId = document.createElement('div');
                    userId.classList.add('reservation-details');
                    userId.textContent = `예약자 ID: ${reservation.user_id}`;

                    const status = document.createElement('div');
                    status.classList.add('reservation-details');
                    status.textContent = `상태: ${reservation.status}`;

                    detailsContainer.appendChild(reservationId);
                    detailsContainer.appendChild(userId);
                    detailsContainer.appendChild(status);

                    reservationId.classList.add('reservation-id');
                    reservationId.textContent = reservation.reservation_id;
                    reservationId.dataset.id = reservation.reservation_id;  
                    
                    const actionButton = document.createElement('button');
                    if (reservation.status === 'pending') {
                        actionButton.classList.add('btn', 'btn-confirm');
                        actionButton.textContent = '예약 확정';
                        actionButton.onclick = function() {
                            confirmReservation(reservationId.dataset.id);
                        };
                    } else if (reservation.status === 'confirm') {
                        actionButton.classList.add('btn', 'btn-cancel');
                        actionButton.textContent = '예약 취소';
                        actionButton.onclick = function() {
                            cancelReservation(reservationId.dataset.id);
                        };
                    }

                    reservationItem.appendChild(detailsContainer);
                    reservationItem.appendChild(actionButton);

                    reservationList.appendChild(reservationItem);
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
                    pageButton.addEventListener('click', () => showPage(currentReservations, i));
                    paginationContainer.appendChild(pageButton);
                }
            }

            let currentReservations = [];

            fetchReservations().then(reservations => {
                currentReservations = reservations;
                const pageCount = Math.ceil(reservations.length / itemsPerPage);
                createPagination(pageCount);
                showPage(reservations, 1);
            }).catch(error => {
                console.error('Error fetching reservations:', error);
            });

            function confirmReservation(id) {
                fetch('/backend/reservation_api.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        reservationId: id,
                        status: 'confirm'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        console.error('Reservation confirmation failed:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating reservation:', error);
                });
            }


            function cancelReservation(id) {
                fetch(`/backend/reservation_api.php?id=${id}`, {
                method: 'DELETE',
            })
            .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();  // 페이지 새로 고침
                    } else {
                        console.error('Reservation Delete failed:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting reservation:', error);
                });
            }
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <a href="/admin">
                <img src="image/admin-logo.gif" alt="관리자 페이지 로고">
                <span>관리자 페이지</span>
            </a>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="/admin/notice.php" id="notice-management">공지관리</a></li>
                <li><a href="/admin/reservation.php" id="reservation-management">예약관리</a></li>
                <li><a href="/admin/QnA.php" id="qa-management">Q&A</a></li>
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
            <h3>예약 관리</h3>
            <div class="reservation-list" id="reservationList">
                <!-- 예약 항목이 여기에 추가됩니다 -->
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
