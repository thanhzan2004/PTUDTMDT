<?php
include ('database.php');

// Bắt đầu phiên làm việc
session_start();

/*
Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
*/

// Tiêu đề trang
$title = "Quản trị web";

// HTML và cấu trúc giao diện
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- Liên kết file CSS -->
<body>
    <?php include 'sidebar.html'; ?> <!-- Gọi file sidebar -->

    <div class="main-content">
        <h1>Chào mừng đến với Trang Quản Trị</h1>
        <p>Đây là trang quản trị chính. Vui lòng chọn chức năng từ menu bên trái.</p>
    </div>
</body>
</html>

