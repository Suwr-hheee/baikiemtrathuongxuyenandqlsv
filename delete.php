<?php
require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM sinhvien WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Chuyển hướng nhanh lại về trang danh sách chính sau khi xóa
header("Location: index.php");
exit();
?>