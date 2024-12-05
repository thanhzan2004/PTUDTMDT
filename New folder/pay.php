<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "final";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra giỏ hàng trống
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Giỏ hàng của bạn hiện đang trống!</p>";
    exit();
}

// Lấy thông tin sản phẩm trong giỏ hàng
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$sql = "SELECT ID_SP, Ten_SP, Gia_SP, Anh_SP FROM san_pham WHERE ID_SP IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Gán giá trị sản phẩm vào câu truy vấn
$stmt->bind_param(str_repeat('s', count($product_ids)), ...$product_ids);
$stmt->execute();
$result = $stmt->get_result();

// Tính tổng giá trị của giỏ hàng
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $product_id = $row['ID_SP'];
    $quantity = $_SESSION['cart'][$product_id];
    $total_price += $row['Gia_SP'] * $quantity;
}

// Xử lý thông tin thanh toán nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    
    // Bạn có thể lưu thông tin đơn hàng vào cơ sở dữ liệu hoặc xử lý thanh toán tại đây
    
    // Sau khi thanh toán thành công, xóa giỏ hàng
    unset($_SESSION['cart']);
    
    echo "<p>Đơn hàng của bạn đã được xác nhận! Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>";
    // Thực hiện xử lý thanh toán hoặc chuyển hướng đến trang thành công
    exit();

}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <style>
        /* Thêm kiểu dáng CSS cho trang thanh toán */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .payment-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .payment-container h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .payment-details {
            margin-bottom: 30px;
        }

        .payment-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-details table, th, td {
            border: 1px solid #ddd;
        }

        .payment-details th, .payment-details td {
            padding: 10px;
            text-align: left;
        }

        .payment-details th {
            background-color: #f1f1f1;
        }

        .payment-summary {
            font-size: 16px;
            color: #333;
        }

        .payment-summary strong {
            font-weight: bold;
            color: #e74c3c;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 98%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .payment-actions {
            text-align: center;
        }

        .payment-actions button {
            padding: 10px 20px;
            background-color: #e6ac00;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-actions button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Thông tin thanh toán</h2>
    <div class="payment-details">
        <h3>Sản phẩm</h3>
        <table>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
            </tr>
            <?php
            // Lấy lại thông tin giỏ hàng và hiển thị
            $result->data_seek(0); // Đặt lại kết quả của truy vấn
            while ($row = $result->fetch_assoc()) {
                $product_id = $row['ID_SP'];
                $quantity = $_SESSION['cart'][$product_id];
                $subtotal = $row['Gia_SP'] * $quantity;
                echo "<tr>
                    <td>" . htmlspecialchars($row['Ten_SP']) . "</td>
                    <td>" . number_format($row['Gia_SP'], 0, ',', '.') . " VND</td>
                    <td>" . $quantity . "</td>
                    <td>" . number_format($subtotal, 0, ',', '.') . " VND</td>
                </tr>";
            }
            ?>
        </table>
    </div>

    <div class="payment-summary">
        <p><strong>Tổng cộng:</strong> <?php echo number_format($total_price, 0, ',', '.') ?> VND</p>
    </div>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Họ và tên</label>
            <input type="text" id="name" name="name" required placeholder="Nhập họ và tên của bạn">
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ giao hàng</label>
            <input type="text" id="address" name="address" required placeholder="Nhập địa chỉ của bạn">
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" required placeholder="Nhập số điện thoại của bạn">
        </div>

        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cod">Thanh toán khi nhận hàng</option>
                <option value="online">Thanh toán trực tuyến</option>
            </select>
        </div>

        <div class="payment-actions">
            <button type="submit">Xác nhận thanh toán</button>
        </div>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
