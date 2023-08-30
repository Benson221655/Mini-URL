<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'get_short_urls') {
        $sql = 'SELECT * FROM `url`';
        $result = $conn->query($sql);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] == 'insert_short_url') {
        $long_url = $_POST['original_url'];
        $short_url = $_POST['short_url'];
        $sql = "INSERT INTO `url` (`original_url`, `short_url`) VALUES ('$long_url', '$short_url')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode('New record created successfully');
        } else {
            echo json_encode('Error: ' . $sql . '<br>' . $conn->error);
        }
    }
}

$conn->close();
?>