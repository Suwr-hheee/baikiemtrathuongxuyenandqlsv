<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "quanly_sinhvien";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
?>