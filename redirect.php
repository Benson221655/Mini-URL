<?php
include 'db.php';

if (isset($_GET['short_url'])) {
    $short_url = $_GET['short_url'];
    $sql = 'SELECT * FROM `url` WHERE `short_url` = "' . $short_url . '"';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row && isset($row['original_url'])) {
        header('Location: ' . $row['original_url']);
        exit();
    } else {
        echo 'Short URL not found';
    }
}
?>