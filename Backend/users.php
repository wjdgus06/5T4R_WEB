<?php
/**
 * @OA\Get(
 *     path="/backend/api/users",
 *     summary="Retrieve a list of users",
 *     @OA\Response(
 *         response=200,
 *         description="A list of users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "username", "FirstName", "LastName", "phone", "email", "role"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="username", type="string"),
 *     @OA\Property(property="FirstName", type="string"),
 *     @OA\Property(property="LastName", type="string"),
 *     @OA\Property(property="phone", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="role", type="string")
 * )
 */

$servername = "3.37.195.23";
$username = "admin";
$password = "1234";
$dbname = "Air_5T4R";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, FirstName, LastName, phone, email, role FROM users";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(['message' => 'No users found']);
}

$conn->close();
?>

