<?php
require_once 'connect.php';

// Lấy danh sách sinh viên
$sql = "SELECT * FROM sinhvien";
$result = $conn->query($sql);

// Khởi tạo các biến đếm và thống kê (Câu 4)
$total_sv = 0;
$total_hoc_bong = 0;

$count_gioi = 0;
$count_kha = 0;
$count_tb = 0;
$count_yeu = 0;

// Biến tính tổng điểm các môn để chia trung bình
$tong_php = 0;
$tong_mysql = 0;
$tong_html = 0;

// Biến lưu điểm cao nhất (khởi tạo bằng số nhỏ nhất)
$max_php = 0; $max_mysql = 0; $max_html = 0;

// Biến lưu điểm thấp nhất (khởi tạo bằng số lớn nhất)
$min_php = 10; $min_mysql = 10; $min_html = 10;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý điểm sinh viên</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .thong-ke { border: 1px dashed red; padding: 15px; margin-top: 30px; background-color: #fff9f9; }
    </style>
</head>
<body>

    <h2>DANH SÁCH ĐIỂM SINH VIÊN</h2>
    
    <p><a href="add.php">[+] Thêm sinh viên mới</a></p>

    <table>
        <thead>
            <tr>
                <th>MSSV</th>
                <th>Họ tên</th>
                <th>Điểm PHP</th>
                <th>Điểm MySQL</th>
                <th>Điểm HTML/CSS</th>
                <th>ĐTB</th>
                <th>Xếp loại</th>
                <th>Học bổng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
                    $total_sv++; // Đếm tổng số sinh viên

                    // 1. Tính toán Thống kê Điểm số (Thuật toán cơ bản thay cho hàm max/min)
                    $tong_php   += $row['diem_php'];
                    $tong_mysql += $row['diem_mysql'];
                    $tong_html  += $row['diem_html'];

                    if ($row['diem_php'] > $max_php)     $max_php   = $row['diem_php'];
                    if ($row['diem_mysql'] > $max_mysql) $max_mysql = $row['diem_mysql'];
                    if ($row['diem_html'] > $max_html)   $max_html  = $row['diem_html'];

                    if ($row['diem_php'] < $min_php)     $min_php   = $row['diem_php'];
                    if ($row['diem_mysql'] < $min_mysql) $min_mysql = $row['diem_mysql'];
                    if ($row['diem_html'] < $min_html)   $min_html  = $row['diem_html'];

                    // 2. Tính Điểm trung bình (Câu 2)
                    $dtb = ($row['diem_php'] * 2 + $row['diem_mysql'] * 2 + $row['diem_html']) / 5;
                    $dtb = round($dtb, 2);

                    // 3. Xét Xếp loại (Câu 2)
                    if ($dtb >= 8) {
                        $xep_loai = "Giỏi";
                        $count_gioi++;
                    } elseif ($dtb >= 6.5) {
                        $xep_loai = "Khá";
                        $count_kha++;
                    } elseif ($dtb >= 5) {
                        $xep_loai = "Trung bình";
                        $count_tb++;
                    } else {
                        $xep_loai = "Yếu";
                        $count_yeu++;
                    }

                    // 4. Xét Học bổng (Câu 3)
                    if ($dtb >= 8.0 && $row['diem_php'] >= 7.0 && $row['diem_mysql'] >= 7.0 && $row['diem_html'] >= 7.0) {
                        $hoc_bong = "Đủ điều kiện học bổng";
                        $total_hoc_bong++;
                    } else {
                        $hoc_bong = "5.000.000VND";
                    }
            ?>
                <tr>
                    <td><?php echo $row['ma_sv']; ?></td>
                    <td><?php echo $row['ho_ten']; ?></td>
                    <td><?php echo $row['diem_php']; ?></td>
                    <td><?php echo $row['diem_mysql']; ?></td>
                    <td><?php echo $row['diem_html']; ?></td>
                    <td><b><?php echo $dtb; ?></b></td>
                    <td><?php echo $xep_loai; ?></td>
                    <td><?php echo $hoc_bong; ?></td>
                    <td>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa sinh viên này?')">Xóa</a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="9">Không có dữ liệu sinh viên.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_sv > 0): ?>
    <div class="thong-ke">
        <h3>BẢNG THỐNG KÊ</h3>
        
        <p><b>1. Thống kê điểm số môn học:</b></p>
        <table>
            <tr>
                <th>Môn học</th>
                <th>Điểm cao nhất</th>
                <th>Điểm thấp nhất</th>
                <th>Điểm trung bình môn</th>
            </tr>
            <tr>
                <td>PHP</td>
                <td><?php echo $max_php; ?></td>
                <td><?php echo $min_php; ?></td>
                <td><?php echo round($tong_php / $total_sv, 2); ?></td>
            </tr>
            <tr>
                <td>MySQL</td>
                <td><?php echo $max_mysql; ?></td>
                <td><?php echo $min_mysql; ?></td>
                <td><?php echo round($tong_mysql / $total_sv, 2); ?></td>
            </tr>
            <tr>
                <td>HTML/CSS</td>
                <td><?php echo $max_html; ?></td>
                <td><?php echo $min_html; ?></td>
                <td><?php echo round($tong_html / $total_sv, 2); ?></td>
            </tr>
        </table>

        <p><b>2. Thống kê số lượng học bổng và học lực:</b></p>
        <ul>
            <li>Tổng số sinh viên: <?php echo $total_sv; ?></li>
            <li>Tổng số sinh viên nhận học bổng: <?php echo $total_hoc_bong; ?></li>
            <li>Số sinh viên Giỏi: <?php echo $count_gioi; ?></li>
            <li>Số sinh viên Khá: <?php echo $count_kha; ?></li>
            <li>Số sinh viên Trung bình: <?php echo $count_tb; ?></li>
            <li>Số sinh viên Yếu: <?php echo $count_yeu; ?></li>
        </ul>
    </div>
    <?php endif; ?>

</body>
</html>