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
    $email = $_POST['email'];
    $note = $_POST['note'];

    // Kiểm tra nếu phương thức thanh toán đã có trong bảng thanh_toan
    $stmt_payment = $conn->prepare("SELECT ID_Thanh_toan FROM thanh_toan WHERE Phuong_thuc_thanh_toan = ?");
    $stmt_payment->bind_param('s', $payment_method);
    $stmt_payment->execute();
    $result_payment = $stmt_payment->get_result();

    // Nếu không có phương thức thanh toán, thêm vào bảng thanh_toan
    if ($result_payment->num_rows == 0) {
        $stmt_insert_payment = $conn->prepare("INSERT INTO thanh_toan (Phuong_thuc_thanh_toan, Mo_ta_thanh_toan) VALUES (?, ?)");
        $stmt_insert_payment->bind_param('ss', $payment_method, $note);
        $stmt_insert_payment->execute();
        
        // Lấy ID của phương thức thanh toán vừa thêm vào
        $ID_Thanh_toan = $stmt_insert_payment->insert_id;
    } else {
        // Lấy ID_Thanh_toan đã tồn tại
        $row_payment = $result_payment->fetch_assoc();
        $ID_Thanh_toan = $row_payment['ID_Thanh_toan'];
    }

    // Thêm đơn hàng vào bảng `don_hang` với ID_Thanh_toan vừa lấy
    $stmt_order = $conn->prepare("INSERT INTO don_hang (Ngay_dat, Trang_thai_thanh_toan, Phi_giao_hang, Giam_gia, Tong_tien, Trang_thai_giao_hang, ID_Thanh_toan) VALUES (NOW(), 'Chưa thanh toán', 0, 0, ?, 'Chưa giao', ?)");
    $stmt_order->bind_param('di', $total_price, $ID_Thanh_toan);
    $stmt_order->execute();

    // Lấy ID đơn hàng vừa tạo
    $order_id = $stmt_order->insert_id;

    // Cập nhật bảng thanh_toan với ID_Don_hang (liên kết với đơn hàng)
    $stmt_update_payment = $conn->prepare("UPDATE thanh_toan SET ID_Don_hang = ? WHERE ID_Thanh_toan = ?");
    $stmt_update_payment->bind_param('ii', $order_id, $ID_Thanh_toan);
    $stmt_update_payment->execute();

    // Thêm chi tiết đơn hàng vào bảng `chi_tiet_don_hang`
    $stmt_details = $conn->prepare("INSERT INTO chi_tiet_don_hang (ID_DH, ID_SP, So_luong_SP, Tong_tien, Ten_SP, Dia_chi, SDT, Email, Ghi_chu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['ID_SP'];
        $quantity = $_SESSION['cart'][$product_id];
        $subtotal = $row['Gia_SP'] * $quantity;

        $stmt_details->bind_param('iiissssss', $order_id, $product_id, $quantity, $subtotal, $row['Ten_SP'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['note']);
        $stmt_details->execute();
    }

// Hiển thị thông tin đơn hàng và thông báo xác nhận
echo "<p style='font-size: 16px; font-weight: bold; color: green; margin-bottom: 10px;'>Đơn hàng của bạn đã được xác nhận! Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>";
echo "<h3 style='font-size: 20px; font-weight: bold; color: #333; margin-bottom: 15px;'>CHI TIẾT ĐƠN HÀNG</h3>";
echo "<p style='margin: 5px 0;'><strong>Họ và tên:</strong> " . htmlspecialchars($name) . "</p>";
echo "<p style='margin: 5px 0;'><strong>Địa chỉ giao hàng:</strong> " . htmlspecialchars($address) . "</p>";
echo "<p style='margin: 5px 0;'><strong>Số điện thoại:</strong> " . htmlspecialchars($phone) . "</p>";
echo "<p style='margin: 5px 0;'><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
echo "<p style='margin: 5px 0;'><strong>Ghi chú:</strong> " . htmlspecialchars($note) . "</p>";

echo "<h4 style='font-size: 18px; font-weight: bold; color: #555; margin-top: 20px;'>Sản phẩm trong đơn hàng:</h4>";
echo "<table border='1' style='width: 60%; border-collapse: collapse; text-align: center; margin-top: 10px;'>
        <tr style='background-color: #e6ac00;'>
            <th style='padding: 8px; border: 1px solid #ddd;'>Tên sản phẩm</th>
            <th style='padding: 8px; border: 1px solid #ddd;'>Giá</th>
            <th style='padding: 8px; border: 1px solid #ddd;'>Số lượng</th>
            <th style='padding: 8px; border: 1px solid #ddd;'>Tổng</th>
        </tr>";
        
// Hiển thị lại thông tin sản phẩm trong giỏ hàng
$result->data_seek(0); // Đặt lại kết quả của truy vấn
while ($row = $result->fetch_assoc()) {
    $product_id = $row['ID_SP'];
    $quantity = $_SESSION['cart'][$product_id];
    $subtotal = $row['Gia_SP'] * $quantity;
    echo "<tr>
            <td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($row['Ten_SP']) . "</td>
            <td style='padding: 8px; border: 1px solid #ddd;'>" . number_format($row['Gia_SP'], 0, ',', '.') . " VND</td>
            <td style='padding: 8px; border: 1px solid #ddd;'>" . $quantity . "</td>
            <td style='padding: 8px; border: 1px solid #ddd;'>" . number_format($subtotal, 0, ',', '.') . " VND</td>
          </tr>";
}

echo "</table>";
echo "<p style='font-size: 16px; font-weight: bold; color: #333; margin-top: 15px;'>Tổng cộng: " . number_format($total_price, 0, ',', '.') . " VND</p>";


   
       // Sau khi thanh toán thành công, xóa giỏ hàng
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
            <label for="payment_method">Hình thức thanh toán</label>
            <select id="payment_method" name="payment_method" required>
                <option value="COD">Thanh toán khi nhận hàng</option>
                <option value="Online">Thanh toán trực tuyến</option>
            </select>
        </div>

        <div class="payment-actions">
            <button type="submit">Xác nhận đơn hàng</button>
        </div>
    </form>
</div>


</body>
</html>
