<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = ""; // Điền mật khẩu nếu có
$database = "ptudtmdt";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID bài viết từ URL
$id_bai_viet = isset($_GET['ID_Bai_viet']) ? $_GET['ID_Bai_viet'] : '';

if ($id_bai_viet) {
    // Truy vấn để lấy thông tin chi tiết bài viết
    $sql = "SELECT Tua_de, Ngay_viet, Phan_loai, Noi_dung, Anh_bai_viet FROM Bai_viet WHERE ID_Bai_viet = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_bai_viet); // Bảo vệ SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();


    // Kiểm tra nếu bài viết tồn tại
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Bài viết không tồn tại.";
        exit;
    }
} else {
    echo "ID bài viết không hợp lệ.";
    exit;
}

include('header.html'); // Nếu có header riêng cho trang của bạn

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['Tua_de']); ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .content {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .content h1 {
            font-size: 2rem;
            color: #e6ac00; /* Màu vàng đậm */
        }
        .content .date, .content .author {
            font-size: 1rem;
            color: #666;
            margin: 10px 0;
        }
        .content .content-body {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #444;
        }
        .content-body img {
            max-width: 100%; /* Đảm bảo ảnh không rộng hơn phần tử chứa */
            height: auto; /* Giữ tỉ lệ khung hình của ảnh */
            display: block; /* Loại bỏ khoảng trống dưới ảnh do thuộc tính inline */
        }
    </style>
</head>
<body>

<div class="content">
    <h1><?php echo htmlspecialchars($row['Tua_de']); ?></h1>
    <div class="date">Ngày đăng: <?php echo date('d/m/Y H:i', strtotime($row['Ngay_viet'])); ?></div>
    <div class="author">Phân loại: <?php echo htmlspecialchars($row['Phan_loai']); ?></div>
    <div class="content-body">
        <?php echo "<img src='" . $row['Anh_bai_viet'] . "' alt='Hình ảnh bài viết' />"; ?>
    </div>
    <div class="content-body">
        <?php echo nl2br(htmlspecialchars($row['Noi_dung'])); ?>
    </div>
</div>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<?php
include('footer.html');
?>