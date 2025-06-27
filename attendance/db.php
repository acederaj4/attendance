<?php
$conn = new mysqli("localhost", "root", "", "attendance_admin1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
