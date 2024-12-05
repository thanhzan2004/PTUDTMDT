<?php
$servername = "localhost";
$username = "root";  // Tên người dùng MySQL (thường mặc định là 'root' trên XAMPP)
$password = "";      // Mật khẩu (trong XAMPP mặc định là trống)
$dbname = "PTUDTMDT"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
