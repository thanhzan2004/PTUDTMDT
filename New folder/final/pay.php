<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "final";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hiển thị nút chọn
if (!isset($_POST['checkout_option'])) {
    echo "<h2>Chọn phương thức thanh toán:</h2>";
    echo "<form method='POST' action=''>
            <button type='submit' name='checkout_option' value='login'>Đăng nhập / Đăng ký</button>
            <button type='submit' name='checkout_option' value='guest'>Thanh toán không cần đăng nhập</button>
          </form>";
    exit();
}

// Nếu chọn Đăng nhập / Đăng ký
if ($_POST['checkout_option'] === 'login') {
    echo "<p>Chức năng đăng nhập / đăng ký chưa được triển khai trong đoạn mã này.</p>";
    exit();
}

// Nếu chọn Thanh toán không cần đăng nhập
if ($_POST['checkout_option'] === 'guest' && !isset($_POST['guest_checkout'])) {
    echo "<h2>Thông tin khách hàng</h2>";
    echo "<form method='POST' action=''>
            <label for='ho_ten'>Họ tên:</label>
            <input type='text' id='ho_ten' name='ho_ten' required><br><br>

            <label for='ngay_sinh'>Ngày sinh:</label>
            <input type='date' id='ngay_sinh' name='ngay_sinh'><br><br>

            <label for='gioi_tinh'>Giới tính:</label>
            <select id='gioi_tinh' name='gioi_tinh'>
                <option value='Nam'>Nam</option>
                <option value='Nữ'>Nữ</option>
                <option value='Khác'>Khác</option>
            </select><br><br>

            <label for='sdt'>Số điện thoại:</label>
            <input type='text' id='sdt' name='sdt' required><br><br>

            <label for='dia_chi'>Địa chỉ:</label>
            <textarea id='dia_chi' name='dia_chi'></textarea><br><br>

            <button type='submit' name='guest_checkout'>Tiếp tục</button>
          </form>";
    exit();
}

if (isset($_POST['guest_checkout'])) {
    $id_kh = uniqid('KH');
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'] ?: null;
    $gioi_tinh = $_POST['gioi_tinh'] ?: null;
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'] ?: null;

    $sql = "INSERT INTO Khach_hang (ID_KH, Ho_ten, Ngay_sinh, Gioi_tinh, SDT, Dia_chi) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $id_kh, $ho_ten, $ngay_sinh, $gioi_tinh, $sdt, $dia_chi);
    $stmt->execute();

    $_SESSION['user_id'] = $id_kh;

    // Hiển thị thông tin giỏ hàng và hóa đơn
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $total_price = 0;
        echo "<h2>Tóm tắt đơn hàng:</h2><ul>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "SELECT Ten_SP, Gia_SP FROM san_pham WHERE ID_SP = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $total_price += $product['Gia_SP'] * $quantity;

            echo "<li>{$product['Ten_SP']} - {$quantity} x {$product['Gia_SP']} VND</li>";
        }
        echo "</ul>";
        echo "<p>Tổng tiền: {$total_price} VND</p>";
        echo "<h3>Chọn phương thức thanh toán:</h3>";
        echo "<form method='POST'>
                <select name='phuong_thuc'>
                    <option value='COD'>Thanh toán khi nhận hàng (COD)</option>
                    <option value='Online'>Thanh toán trực tuyến</option>
                </select><br><br>
                <button type='submit' name='confirm_order'>Xác nhận đơn hàng</button>
              </form>";
    }
}

$conn->close();
?>
