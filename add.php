<?php
require_once 'connect.php';

$error = "";
$success = "";

// Khởi tạo biến rỗng để tránh lỗi khi render vào form lần đầu
$ma_sv = "";
$ho_ten = "";
$diem_php = "";
$diem_mysql = "";
$diem_html = "";

if (isset($_POST['them'])) {
    $ma_sv = $_POST['ma_sv'];
    $ho_ten = $_POST['ho_ten'];
    $diem_php = $_POST['diem_php'];
    $diem_mysql = $_POST['diem_mysql'];
    $diem_html = $_POST['diem_html'];

    // Câu 1: Kiểm tra trống dữ liệu
    if ($ma_sv == "" || $ho_ten == "" || $diem_php == "" || $diem_mysql == "" || $diem_html == "") {
        $error = "Vui lòng nhập đầy đủ tất cả các trường dữ liệu!";
    } 
    // Câu 1: Kiểm tra khoảng điểm từ 0 đến 10
    elseif ($diem_php < 0 || $diem_php > 10 || $diem_mysql < 0 || $diem_mysql > 10 || $diem_html < 0 || $diem_html > 10) {
        $error = "Điểm số phải nằm trong khoảng từ 0 đến 10!";
    } else {
        // Kiểm tra xem mã sinh viên đã tồn tại chưa (Dùng câu lệnh SQL cơ bản công chuỗi)
        $sql_check = "SELECT * FROM sinhvien WHERE ma_sv = '$ma_sv'";
        $result_check = $conn->query($sql_check);
        
        if ($result_check->num_rows > 0) {
            $error = "Mã sinh viên này đã tồn tại trong hệ thống!";
        } else {
            // Thêm dữ liệu vào bảng bằng câu lệnh INSERT đơn giản
            $sql_insert = "INSERT INTO sinhvien (ma_sv, ho_ten, diem_php, diem_mysql, diem_html) 
                           VALUES ('$ma_sv', '$ho_ten', '$diem_php', '$diem_mysql', '$diem_html')";
            
            if ($conn->query($sql_insert) === TRUE) {
                $success = "Thêm mới sinh viên thành công!";
                // Xóa trắng các biến để form trống sau khi thêm thành công
                $ma_sv = $ho_ten = $diem_php = $diem_mysql = $diem_html = "";
            } else {
                $error = "Có lỗi xảy ra khi thêm dữ liệu!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm Sinh Viên Mới</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .form-box { max-width: 400px; margin: 0 auto; border: 1px solid black; padding: 20px; }
        .form-group { margin-bottom: 10px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input { width: 95%; padding: 5px; }
        button { padding: 6px 12px; background: green; color: white; border: none; cursor: pointer; }
        .btn-back { margin-left: 10px; color: blue; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Thêm Sinh Viên Mới</h2>

    <?php if ($error != ""): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success != ""): ?>
        <p style="color: green; font-weight: bold;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="add.php" method="POST">
        <div class="form-group">
            <label>Mã sinh viên:</label>
            <input type="text" name="ma_sv" value="<?php echo $ma_sv; ?>">
        </div>
        <div class="form-group">
            <label>Họ tên:</label>
            <input type="text" name="ho_ten" value="<?php echo $ho_ten; ?>">
        </div>
        <div class="form-group">
            <label>Điểm PHP:</label>
            <input type="number" step="0.1" name="diem_php" value="<?php echo $diem_php; ?>">
        </div>
        <div class="form-group">
            <label>Điểm MySQL:</label>
            <input type="number" step="0.1" name="diem_mysql" value="<?php echo $diem_mysql; ?>">
        </div>
        <div class="form-group">
            <label>Điểm HTML/CSS:</label>
            <input type="number" step="0.1" name="diem_html" value="<?php echo $diem_html; ?>">
        </div>
        
        <button type="submit" name="them">Lưu lại</button>
        <a href="index.php" class="btn-back">Quay về danh sách</a>
    </form>
</div>

</body>
</html>