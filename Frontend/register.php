<?php
header('Content-Type: application/json');

$servername = "3.37.195.23";
$username = "admin";
$password = "1234";
$dbname = "Air_5T4R";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, FirstName, LastName, password, phone, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $firstName, $lastName, $password, $phone, $email);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "User registered successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: " . $stmt->error));
    }

    $stmt->close();
}

$conn->close();
?>

