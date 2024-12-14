<?php
// Kết nối cơ sở dữ liệu
include('database.php');
include 'sidebar.html';

// Khởi tạo các biến mặc định để tránh lỗi undefined
$totalRevenue = 0;
$totalQuantitySold = 0;
$totalOrdersDelivered = 0;
$totalOrdersNotDelivered = 0;
$totalOrdersInProgress = 0;
$totalStock = 0;

// Lọc theo thời gian (nếu có)
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Lọc dữ liệu báo cáo nếu có lọc theo thời gian
$whereCondition = "";
if ($startDate && $endDate) {
    $whereCondition = "WHERE dh.Ngay_dat BETWEEN '$startDate' AND '$endDate'";
}

// Truy vấn tổng doanh thu, số lượng bán, đơn hàng và số lượng sản phẩm còn lại
$query = "
    SELECT 
        SUM(dh.Tong_tien) AS totalRevenue,
        SUM(dct.So_luong_SP) AS totalQuantitySold,
        COUNT(DISTINCT dh.ID_DH) AS totalOrders,
        COUNT(CASE WHEN dh.Trang_thai_giao_hang = 'Đã giao' THEN 1 END) AS totalOrdersDelivered,
        COUNT(CASE WHEN dh.Trang_thai_giao_hang = 'Chưa giao' THEN 1 END) AS totalOrdersNotDelivered,
        COUNT(CASE WHEN dh.Trang_thai_giao_hang = 'Đang giao' THEN 1 END) AS totalOrdersInProgress
    FROM don_hang_chi_tiet dh
    LEFT JOIN don_hang_chi_tiet dct ON dh.ID_DH = dct.ID_DH
    $whereCondition;
";

$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalRevenue = $row['totalRevenue'];
    $totalQuantitySold = $row['totalQuantitySold'];
    $totalOrdersDelivered = $row['totalOrdersDelivered'];
    $totalOrdersNotDelivered = $row['totalOrdersNotDelivered'];
    $totalOrdersInProgress = $row['totalOrdersInProgress'];
}

// Truy vấn tổng số sản phẩm còn lại trong kho (nếu bảng kho có)
$stockQuery = "SELECT SUM(So_luong_ton_kho) AS totalStock FROM San_pham";
$stockResult = $conn->query($stockQuery);
if ($stockResult->num_rows > 0) {
    $stockRow = $stockResult->fetch_assoc();
    $totalStock = $stockRow['totalStock'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo doanh thu</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #fff8e1;
    margin: 0;
    padding: 0;
}

.main-content {
    margin: 50px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-left: 300px; /* Để nội dung tránh bị sidebar che khuất */
}

/* Tiêu đề chính */
h1 {
    text-align: center;
    color: #ffb300;
    margin-bottom: 20px;
}

/* Form lọc thời gian */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 30px;
}

form label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

/* Input và nút submit */
form input[type="date"] {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    width: 200px;
}

form input[type="submit"] {
    background-color: #ffb300;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 220px;
    margin-top: 10px;
}

form input[type="submit"]:hover {
    background-color: #ffa000;
}

/* Dashboard */
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* Card thống kê */
.card {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.card h3 {
    font-size: 20px;
    color: #333;
    margin-bottom: 15px;
}

.card p {
    font-size: 30px;
    color: #ffb300;
    font-weight: bold;
    margin: 0;
}

/* Footer của trang */
footer {
    text-align: center;
    font-size: 14px;
    color: #888;
    margin-top: 40px;
}
    </style>
</head>
<body>
    <div class="main-content">    
    <h1>Báo cáo doanh thu</h1>
    <form method="POST" action="reports.php">
        <label for="start_date">Từ ngày:</label>
        <input type="date" name="start_date" value="<?= $startDate ?>">
        <label for="end_date">Đến ngày:</label>
        <input type="date" name="end_date" value="<?= $endDate ?>">
        <input type="submit" value="Lọc">
    </form>

    <div class="dashboard">
        <div class="card">
            <h3>Tổng Doanh Thu</h3>
            <p><?= number_format($totalRevenue, 0, ',', '.') ?> VNĐ</p>
        </div>
        <div class="card">
            <h3>Tổng Số Lượng Bán</h3>
            <p><?= $totalQuantitySold ?> sản phẩm</p>
        </div>
        <div class="card">
            <h3>Tổng Đơn Hàng Đã Giao</h3>
            <p><?= $totalOrdersDelivered ?> đơn</p>
        </div>
        <div class="card">
            <h3>Tổng Đơn Hàng Chưa Giao</h3>
            <p><?= $totalOrdersNotDelivered ?> đơn</p>
        </div>
        <div class="card">
            <h3>Tổng Đơn Hàng Đang Giao</h3>
            <p><?= $totalOrdersInProgress ?> đơn</p>
        </div>
        <div class="card">
            <h3>Tổng Sản Phẩm Còn Lại</h3>
            <p><?= $totalStock ?> sản phẩm</p>
        </div>
    </div>
    </div>

</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
