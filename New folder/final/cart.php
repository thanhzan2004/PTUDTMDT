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

// Xử lý cập nhật giỏ hàng
if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    if ($new_quantity > 0) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    } else {
        // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
        unset($_SESSION['cart'][$product_id]);
    }
    // Chuyển hướng lại trang giỏ hàng để tránh gửi lại form khi tải lại trang
    header("Location: cart.php");
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);

    // Chuyển hướng lại trang giỏ hàng
    header("Location: cart.php");
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

// Xử lý tính tổng giá trị của các sản phẩm đã chọn
$selected_total_price = 0;
if (isset($_POST['calculate'])) {
    $selected_products = $_POST['selected_products'] ?? []; // Lấy mảng sản phẩm được chọn từ form

    // Tính tổng giá trị của các sản phẩm được chọn
    foreach ($selected_products as $product_id) {
        $sql = "SELECT Gia_SP FROM san_pham WHERE ID_SP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $quantity = $_SESSION['cart'][$product_id];
            $selected_total_price += $row['Gia_SP'] * $quantity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <style>
        /* Tổng thể */
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
}

/* Container giỏ hàng */
.cart-container {
    max-width: 1200px;
    margin: 20px auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* Tiêu đề giỏ hàng */
.cart-container h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Sản phẩm trong giỏ hàng */
.cart-item {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 15px 0;
}

.cart-item:last-child {
    border-bottom: none;
}

/* Hình ảnh sản phẩm */
.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 20px;
}

/* Thông tin sản phẩm */
.cart-item-info {
    flex: 1;
}

.cart-item-info h3 {
    font-size: 18px;
    margin: 0 0 10px;
    color: #333;
}

.cart-item-info .price {
    color: #e74c3c;
    font-weight: bold;
    margin-bottom: 10px;
}

/* Nút hành động (Cập nhật và Xóa) */
.cart-item-info form {
    display: inline-block;
    margin-right: 10px;
}

.cart-item-info button {
    padding: 5px 10px;
    font-size: 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cart-item-info button[name="update"] {
    background-color: #3498db;
    color: #fff;
}

.cart-item-info button[name="update"]:hover {
    background-color: #2980b9;
}

.cart-item-info button[name="delete"] {
    background-color: #e74c3c;
    color: #fff;
}

.cart-item-info button[name="delete"]:hover {
    background-color: #c0392b;
}

/* Tổng giá */
.cart-item-info div p {
    margin: 5px 0;
    font-size: 14px;
    color: #666;
}

/* Checkbox chọn sản phẩm */
.cart-item-info label {
    display: flex;
    align-items: center;
    margin-top: 10px;
    font-size: 14px;
    color: #333;
}

.cart-item-info .product-checkbox {
    margin-right: 8px;
    transform: scale(1.2);
}

/* Tổng kết giỏ hàng */
.cart-summary {
    text-align: right;
    margin-top: 20px;
}

.cart-summary p {
    font-size: 16px;
    margin: 5px 0;
    color: #333;
}

.cart-summary strong {
    font-weight: bold;
    color: #555;
}

/* Nút tiếp tục mua sắm và thanh toán */
.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.actions a button {
    width: 100%;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background-color: #e6ac00;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.actions a button:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

.actions a button:nth-child(2) {
    background-color: #f39c12;
}

.actions a button:nth-child(2):hover {
    background-color: #e67e22;
}

/* Responsive thiết kế */
@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .cart-item img {
        margin-bottom: 10px;
    }

    .cart-item-info {
        width: 100%;
    }

    .actions a button {
        width: 100%;
        margin-bottom: 10px;
    }
}


    </style>
</head>
<body>
<div class="cart-container">
    <h2>GIỎ HÀNG</h2>
    <form method="POST" action="">
        <?php
        $total_price = 0;
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['ID_SP'];
            $quantity = $_SESSION['cart'][$product_id];
            $subtotal = $row['Gia_SP'] * $quantity;
            $total_price += $subtotal;
        ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($row['Anh_SP']); ?>" alt="<?php echo htmlspecialchars($row['Ten_SP']); ?>">
                <div class="cart-item-info">
                    <h3><?php echo htmlspecialchars($row['Ten_SP']); ?></h3>
                    <p class="price"><?php echo number_format($row['Gia_SP'], 0, ',', '.') . " VND"; ?></p>
                    <form method="POST" action="">
                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1">
                        <button type="submit" name="update">Cập nhật</button>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    </form>
                    <form method="POST" action="">
                        <button type="submit" name="delete">Xóa</button>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    </form>
                    <div>
                        <p>Tổng giá: <?php echo number_format($subtotal, 0, ',', '.') . " VND"; ?></p>
                    </div>
                    <!-- Thêm checkbox cho từng sản phẩm -->
                    <label>
                        <input type="checkbox" name="selected_products[]" value="<?php echo $product_id; ?>" class="product-checkbox">
                        Chọn sản phẩm này
                    </label>
                </div>
            </div>
        <?php } ?>
    </form>

    <div class="cart-summary">
        <p><strong>Tổng cộng:</strong> <?php echo number_format($total_price, 0, ',', '.') ?> VNĐ</p>
    <div class="actions">
        <a href="productlist.php">
            <button type="button">Tiếp tục mua sắm</button>
        </a>
        <a href="pay.php">
            <button type="button">Thanh toán</button>
        </a>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>
