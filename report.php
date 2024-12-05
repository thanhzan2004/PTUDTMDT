<?php
// Kết nối cơ sở dữ liệu
include('database.php');

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
    $whereCondition = "WHERE d.Ngay_dat BETWEEN '$startDate' AND '$endDate'";
}

// Truy vấn tổng doanh thu, số lượng bán, đơn hàng và số lượng sản phẩm còn lại
$query = "
    SELECT 
        SUM(d.Tong_tien) AS totalRevenue,
        SUM(cd.So_luong_SP) AS totalQuantitySold,
        COUNT(DISTINCT d.ID_DH) AS totalOrders,
        COUNT(CASE WHEN d.Trang_thai_giao_hang = 'Đã giao' THEN 1 END) AS totalOrdersDelivered,
        COUNT(CASE WHEN d.Trang_thai_giao_hang = 'Chưa giao' THEN 1 END) AS totalOrdersNotDelivered,
        COUNT(CASE WHEN d.Trang_thai_giao_hang = 'Đang giao' THEN 1 END) AS totalOrdersInProgress
    FROM Don_hang d
    LEFT JOIN Chi_tiet_don_hang cd ON d.ID_DH = cd.ID_DH
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

// Truy vấn tổng số sản phẩm còn lại trong kho
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
    <link rel="stylesheet" href="style_report.css">
</head>
<body>
    <h1>Báo cáo doanh thu</h1>
    <form method="POST" action="report.php">
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
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
