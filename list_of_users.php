<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql213.infinityfree.com";
$user = "if0_40063264";
$pass = "0xYzWJnLveiQf"; // replace this with your control panel password
$dbname = "if0_40063264_usersdb";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>
