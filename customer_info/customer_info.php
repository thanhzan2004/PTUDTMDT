<?php
session_start();
include('database.php'); // Kết nối cơ sở dữ liệu

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$sql = "SELECT Ho_ten, Email_TK, SDT, Dia_chi, Gioi_tinh FROM Khach_hang kh 
        JOIN Tai_khoan tk ON kh.ID_Tai_khoan = tk.ID_Tai_khoan 
        WHERE tk.ID_Tai_khoan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Tài Khoản</title>
    <link rel="stylesheet" href="customer_info.css">
</head>
<body>
    <div class="account-container">
        <div class="sidebar">
            <p>Hi, <?= htmlspecialchars($user['Ho_ten']) ?></p>
            <ul>
                <li><a href="#" id="tab-info" class="active">Thông tin tài khoản</a></li>
                <li><a href="#" id="tab-history">Lịch sử mua hàng</a></li>
                <li><a href="#" id="tab-address">Danh sách địa chỉ</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
        <div class="content">
            <!-- Tab thông tin tài khoản -->
            <div id="info-tab" class="tab-content active">
                <h2>Thông tin tài khoản</h2>
                <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['Ho_ten']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['Email_TK']) ?></p>
                <p><strong>Giới tính:</strong> <?= htmlspecialchars($user['Gioi_tinh']) ?></p>
                <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['SDT']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['Dia_chi']) ?></p>
            </div>
            <!-- Tab lịch sử mua hàng -->
            <div id="history-tab" class="tab-content">
                <h2>Lịch sử mua hàng</h2>
                <p>Bạn chưa đặt mua sản phẩm nào!</p>
            </div>
            <!-- Tab danh sách địa chỉ -->
            <div id="address-tab" class="tab-content">
                <h2>Danh sách địa chỉ</h2>
                <h3>Địa chỉ mặc định</h3>
                <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['Ho_ten']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['Dia_chi']) ?></p>
                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($user['SDT']) ?></p>
                <h3>Địa chỉ khác</h3>
                <p>Chưa có địa chỉ nào được thêm.</p>
            </div>
        </div>
    </div>
    <script src="customer_info.js"></script>
</body>
</html>
