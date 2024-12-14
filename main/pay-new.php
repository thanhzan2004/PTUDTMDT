<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "ptudtmdt";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

include('header.html');

// Kiểm tra giỏ hàng trống
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Giỏ hàng của bạn hiện đang trống!</p>";
    exit();
}

// Lấy thông tin sản phẩm trong giỏ hàng
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$sql = "SELECT ID_SP, Ten_SP, Gia_SP FROM san_pham WHERE ID_SP IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Gán giá trị sản phẩm vào câu truy vấn
$stmt->bind_param(str_repeat('s', count($product_ids)), ...$product_ids);
$stmt->execute();
$result = $stmt->get_result();

// Tính tổng giá trị của giỏ hàng
$total_price = 0;
$product_details = []; // Lưu thông tin sản phẩm để chèn sau
while ($row = $result->fetch_assoc()) {
    $product_id = $row['ID_SP'];
    $quantity = $_SESSION['cart'][$product_id];
    $subtotal = $row['Gia_SP'] * $quantity;
    $total_price += $subtotal;

    // Lưu thông tin sản phẩm để chèn vào bảng sau
    $product_details[] = [
        'ID_SP' => $product_id,
        'Ten_SP' => $row['Ten_SP'],
        'So_luong_SP' => $quantity,
        'Gia_SP' => $row['Gia_SP'],
        'Tong_tien' => $subtotal,
    ];
}

// Xử lý thông tin thanh toán nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    $email = $_POST['email'];
    $note = $_POST['note'];

    // Tạo mã đơn hàng duy nhất
    $order_id = uniqid("DH");

    // Chèn từng sản phẩm vào bảng `don_hang_chi_tiet`
    $stmt_detail = $conn->prepare("INSERT INTO don_hang_chi_tiet 
        (ID_DH, ID_SP, Ten_SP, So_luong_SP, Gia_SP, Cach_thanh_toan, Dia_chi, Ngay_dat, Trang_thai_thanh_toan, Trang_thai_giao_hang, Tong_tien, SDT, Ten_khach_hang, Email, Ghi_chu) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'Chưa thanh toán', 'Chưa giao', ?, ?, ?, ?, ?)");
    
    foreach ($product_details as $product) {
        $stmt_detail->bind_param(
            'sssidsdsssss', 
            $order_id,
            $product['ID_SP'],
            $product['Ten_SP'],
            $product['So_luong_SP'],
            $product['Gia_SP'],
            $payment_method,
            $address,
            $product['Tong_tien'],
            $phone,
            $name,
            $email,
            $note
        );        
        $stmt_detail->execute();
    }

    // Hiển thị thông báo xác nhận
    echo "<div style='background-color: #fff3cd; padding: 20px; border-radius: 5px; margin-bottom: 20px; width: 80%; max-width: 600px; margin-left: auto; margin-right: auto; text-align: center;'>";
    echo "<p style='font-size: 18px; font-weight: bold; color: #856404; margin-bottom: 10px;'>Đơn hàng của bạn đã được xác nhận! Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>";
    echo "</div>";

    // Hiển thị chi tiết đơn hàng
    echo "<div style='border: 1px solid #f1c40f; padding: 20px; border-radius: 10px; background-color: #fff9e5; margin-bottom: 20px; width: 80%; max-width: 600px; margin-left: auto; margin-right: auto;'>";
    echo "<h3 style='font-size: 22px; font-weight: bold; color: #333; margin-bottom: 15px;'>CHI TIẾT ĐƠN HÀNG</h3>";
    echo "<p><strong>Mã đơn hàng:</strong> $order_id</p>";
    echo "<p><strong>Họ và tên:</strong> $name</p>";
    echo "<p><strong>Địa chỉ:</strong> $address</p>";
    echo "<p><strong>Số điện thoại:</strong> $phone</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Ghi chú:</strong> $note</p>";
    echo "</div>";

    echo "<table border='1' style='width: 100%; border-collapse: collapse; text-align: center;'>";
    echo "<tr><th>Tên sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tổng</th></tr>";
    foreach ($product_details as $product) {
        echo "<tr>
                <td>{$product['Ten_SP']}</td>
                <td>" . number_format($product['Gia_SP'], 0, ',', '.') . " VND</td>
                <td>{$product['So_luong_SP']}</td>
                <td>" . number_format($product['Tong_tien'], 0, ',', '.') . " VND</td>
              </tr>";
    }
    echo "</table>";
    echo "<p>Tổng cộng: " . number_format($total_price, 0, ',', '.') . " VND</p>";

    // Xóa giỏ hàng sau khi xử lý
    unset($_SESSION['cart']);
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
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Nhập email của bạn">
        </div>

        <div class="form-group">
            <label for="note">Ghi chú</label>
            <input type="text" id="note" name="note" placeholder="Nhập ghi chú (nếu có)">
        </div>

        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cod">Thanh toán khi nhận hàng</option>
                <option value="online">Thanh toán online</option>
            </select>
        </div>

        <div id="bank_info" style="display: none; margin-top: 20px;">
            <h4>Thông tin chuyển khoản</h4>
            <p><strong>Ngân hàng:</strong> Vietcombank</p>
            <p><strong>Số tài khoản:</strong> 123456789</p>
            <p><strong>Chủ tài khoản:</strong> Nguyễn Văn A</p>
            <p><em>Vui lòng ghi rõ mã đơn hàng trong nội dung chuyển khoản.</em></p>
        </div>

        <div class="payment-actions">
            <button type="submit">Xác nhận đơn hàng</button>
        </div>
    </form>
</div>
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const bankInfo = document.getElementById('bank_info');
        if (this.value === 'online') {
            bankInfo.style.display = 'block';
        } else {
            bankInfo.style.display = 'none';
        }
    });
</script>

</body>
</html>
