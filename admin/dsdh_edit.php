<?php
include('database.php'); // Kết nối cơ sở dữ liệu
include 'sidebar.html'; // Sidebar nếu cần

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

    // Cập nhật trạng thái nếu có yêu cầu từ form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Lấy trạng thái thanh toán và giao hàng từ form
        $new_payment_status = $_POST['Trang_thai_thanh_toan'];
        $new_shipping_status = $_POST['Trang_thai_giao_hang'];

        // Cập nhật vào cơ sở dữ liệu
        $sql_update = "UPDATE don_hang_chi_tiet 
                       SET Trang_thai_thanh_toan = ?, Trang_thai_giao_hang = ? 
                       WHERE ID_DH = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sss", $new_payment_status, $new_shipping_status, $id_dh);
        $stmt_update->execute();

        // Thông báo cập nhật thành công
        echo "<script>alert('Trạng thái đã được cập nhật!');</script>";
        // Reload lại trang để hiển thị trạng thái mới
        header("Location: " . $_SERVER['PHP_SELF'] . "?id_dh=" . $id_dh);
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
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8e1; /* Màu nền giống ví dụ */
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin: 50px;
            background: #fff; /* Màu nền trắng */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-left: 300px; /* Để nội dung tránh bị sidebar che khuất */
        }
        h1 {
            text-align: center;
            color: #ffb300; /* Màu vàng nổi bật */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #fff8e1; /* Màu nền của bảng */
            color: #333; /* Màu chữ */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333; /* Màu chữ đậm */
        }
        input, textarea, select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button, input[type="submit"] {
            background-color: #ffb300; /* Màu vàng đậm */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #ffa000; /* Màu vàng đậm hơn khi hover */
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Thông tin đơn hàng: <?php echo htmlspecialchars($id_dh); ?></h1>

        <!-- Thông tin đơn hàng -->
        <table>
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">Thông tin đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Tên khách hàng:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Ten_khach_hang']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Số điện thoại:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['SDT']); ?></td>
                </tr>
                <tr>
                    <td><strong>Địa chỉ giao hàng:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Dia_chi']); ?></td>
                </tr>
                <tr>
                    <td><strong>Ngày đặt:</strong></td>
                    <td><?php echo date('d/m/Y', strtotime($order_details['Ngay_dat'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Phương thức thanh toán:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Cach_thanh_toan']); ?></td>
                </tr>
                <tr>
                    <td><strong>Trạng thái thanh toán:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Trang_thai_thanh_toan']); ?></td>
                </tr>
                <tr>
                    <td><strong>Trạng thái giao hàng:</strong></td>
                    <td><?php echo htmlspecialchars($order_details['Trang_thai_giao_hang']); ?></td>
                </tr>
                <tr>
                    <td><strong>Tổng tiền (VND):</strong></td>
                    <td><?php echo number_format($order_details['Tong_tien'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td><strong>Ghi chú:</strong></td>
                    <td><?php echo nl2br(htmlspecialchars($order_details['Ghi_chu'])); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Form để cập nhật trạng thái thanh toán và giao hàng -->
        <h3>Cập nhật trạng thái</h3>
        <form method="POST">
            <label for="Trang_thai_thanh_toan">Trạng thái thanh toán:</label>
            <select name="Trang_thai_thanh_toan" id="Trang_thai_thanh_toan">
                <option value="Chưa thanh toán" <?php if ($order_details['Trang_thai_thanh_toan'] == 'Chưa thanh toán') echo 'selected'; ?>>Chưa thanh toán</option>
                <option value="Đã thanh toán" <?php if ($order_details['Trang_thai_thanh_toan'] == 'Đã thanh toán') echo 'selected'; ?>>Đã thanh toán</option>
            </select>
            <br><br>
            <label for="Trang_thai_giao_hang">Trạng thái giao hàng:</label>
            <select name="Trang_thai_giao_hang" id="Trang_thai_giao_hang">
                <option value="Chưa giao" <?php if ($order_details['Trang_thai_giao_hang'] == 'Chưa giao') echo 'selected'; ?>>Chưa giao</option>
                <option value="Đã giao" <?php if ($order_details['Trang_thai_giao_hang'] == 'Đã giao') echo 'selected'; ?>>Đã giao</option>
            </select>
            <br><br>
            <input type="submit" value="Cập nhật">
        </form>

        <!-- Thông tin các sản phẩm -->
        <h3>Chi tiết các sản phẩm trong đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá (VND)</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền (VND)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['ID_SP']); ?></td>
                    <td><?php echo htmlspecialchars($product['Ten_SP']); ?></td>
                    <td><?php echo number_format($product['Gia_SP'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($product['So_luong_SP']); ?></td>
                    <td><?php echo number_format($product['Tong_tien'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
