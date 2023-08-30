<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$db = 'mini-url';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>