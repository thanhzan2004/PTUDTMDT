<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ptudtmdt"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Kiểm tra xem ID đơn hàng có được gửi qua URL không
if (isset($_GET['id_dh'])) {
    $id_dh = $_GET['id_dh'];

    // Lấy thông tin đơn hàng từ cơ sở dữ liệu
    $sql_order = "SELECT ID_DH, Ten_khach_hang, Email, SDT, Dia_chi, Ngay_dat, Cach_thanh_toan, Trang_thai_thanh_toan, Trang_thai_giao_hang, Tong_tien, Ghi_chu 
                  FROM don_hang_chi_tiet 
                  WHERE ID_DH = ? LIMIT 1";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("s", $id_dh);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();

    // Kiểm tra nếu đơn hàng tồn tại
    if ($result_order->num_rows > 0) {
        $order_details = $result_order->fetch_assoc(); // Lưu thông tin đơn hàng
    } else {
        echo "<script>alert('Không tìm thấy đơn hàng với ID này.');</script>";
        exit;
    }

    // Lấy thông tin các sản phẩm trong đơn hàng
    $sql_products = "SELECT ID_SP, Ten_SP, Gia_SP, So_luong_SP, (Gia_SP * So_luong_SP) AS Tong_tien 
                     FROM don_hang_chi_tiet 
                     WHERE ID_DH = ?";
    $stmt_products = $conn->prepare($sql_products);
    $stmt_products->bind_param("s", $id_dh);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();

    // Kiểm tra nếu có sản phẩm
    if ($result_products->num_rows > 0) {
        $products = $result_products->fetch_all(MYSQLI_ASSOC); // Lưu thông tin các sản phẩm
    } else {
        echo "<script>alert('Không tìm thấy sản phẩm trong đơn hàng này.');</script>";
        exit;
    }
} else {
    echo "<script>alert('ID đơn hàng không hợp lệ.');</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin đơn hàng</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Thông tin đơn hàng</h1>
    
    <h3>Chi tiết đơn hàng ID: <?php echo htmlspecialchars($order_details['ID_DH']); ?></h3>
    <table>
        <tr>
            <th>Tên khách hàng</th>
            <td><?php echo htmlspecialchars($order_details['Ten_khach_hang']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($order_details['Email']); ?></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><?php echo htmlspecialchars($order_details['SDT']); ?></td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td><?php echo htmlspecialchars($order_details['Dia_chi']); ?></td>
        </tr>
        <tr>
            <th>Ngày đặt</th>
            <td><?php echo htmlspecialchars($order_details['Ngay_dat']); ?></td>
        </tr>
        <tr>
            <th>Cách thanh toán</th>
            <td><?php echo htmlspecialchars($order_details['Cach_thanh_toan']); ?></td>
        </tr>
        <tr>
            <th>Trạng thái thanh toán</th>
            <td><?php echo htmlspecialchars($order_details['Trang_thai_thanh_toan']); ?></td>
        </tr>
        <tr>
            <th>Trạng thái giao hàng</th>
            <td><?php echo htmlspecialchars($order_details['Trang_thai_giao_hang']); ?></td>
        </tr>
        <tr>
            <th>Tổng tiền</th>
            <td><?php echo number_format($order_details['Tong_tien'], 0, ',', '.'); ?> VND</td>
        </tr>
        <tr>
            <th>Ghi chú</th>
            <td><?php echo htmlspecialchars($order_details['Ghi_chu']); ?></td>
        </tr>
    </table>

    <h3>Danh sách sản phẩm</h3>
    <table>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Giá sản phẩm</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['Ten_SP']); ?></td>
                <td><?php echo number_format($product['Gia_SP'], 0, ',', '.'); ?> VND</td>
                <td><?php echo htmlspecialchars($product['So_luong_SP']); ?></td>
                <td><?php echo number_format($product['Tong_tien'], 0, ',', '.'); ?> VND</td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
