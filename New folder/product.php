<?php
session_start(); // Bắt đầu session
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "final";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL và xử lý như chuỗi
$ID_SP = isset($_GET['ID_SP']) ? trim($_GET['ID_SP']) : '';

// Nếu ID không hợp lệ
if (empty($ID_SP)) {
    echo "Mã sản phẩm không hợp lệ.";
    exit;
}

// Truy vấn thông tin chi tiết sản phẩm
$sql = "SELECT Ten_SP, Gia_SP, Anh_SP, Mo_ta_SP FROM san_pham WHERE ID_SP = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ID_SP); // Sử dụng "s" vì ID_SP là chuỗi
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu có sản phẩm
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Sản phẩm không tồn tại trong cơ sở dữ liệu.";
    exit;
}

// Truy vấn màu sắc và kích cỡ
$sql_options = "SELECT Mau_sac, Kich_co FROM Mau_sac,Kich_co,San_pham WHERE ID_SP = ?";
$stmt_options = $conn->prepare($sql_options);
$stmt_options->bind_param("s", $ID_SP);
$stmt_options->execute();
$result_options = $stmt_options->get_result();

$options = [];
while ($row = $result_options->fetch_assoc()) {
    $options[] = $row;
}

// Truy vấn đánh giá sản phẩm
$sql_reviews = "SELECT Binh_luan, Star, Ngay_BL, ID_KH FROM Binh_luan WHERE ID_SP = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("s", $ID_SP);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();

// Lấy danh sách đánh giá
$reviews = [];
while ($row = $result_reviews->fetch_assoc()) {
    $reviews[] = $row;
}

// Truy vấn các sản phẩm liên quan (ví dụ dựa trên thể loại)
$sql_related = "SELECT ID_SP, Ten_SP, Gia_SP, Anh_SP FROM san_pham WHERE ID_SP != ? LIMIT 4"; // Lấy 4 sản phẩm khác
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("s", $ID_SP);
$stmt_related->execute();
$result_related = $stmt_related->get_result();

$related_products = [];
while ($row = $result_related->fetch_assoc()) {
    $related_products[] = $row;
}

// Kiểm tra giỏ hàng và xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array(); // Tạo giỏ hàng nếu chưa có
    }

    $product_id = $ID_SP;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // Nếu có rồi, cộng thêm số lượng
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // Nếu chưa có, thêm mới vào giỏ hàng
    }

    // Không chuyển hướng đến giỏ hàng mà ở lại trang chi tiết sản phẩm
    $message = "Sản phẩm đã được thêm vào giỏ hàng!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <style>
      body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f3f4f6;
    color: #333333;
    line-height: 1.6;
}

.product-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
}

.product-image {
    flex: 1;
    min-width: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-image img {
    max-width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 15px;
}

.product-info {
    flex: 2;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-info h2 {
    font-size: 32px;
    color: #2c3e50;
    margin-bottom: 15px;
}

.product-info .price {
    font-size: 28px;
    color: #e74c3c;
    font-weight: bold;
    margin: 15px 0;
}

.product-info p {
    margin-bottom: 20px;
}

.actions {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.actions form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.actions select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
    width: 100px;
}

.actions button {
    padding: 12px 24px;
    background-color: #e6ac00;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.actions button:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

.actions a button {
    background-color: #3498db;
}

.actions a button:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

.message {
    margin-top: 20px;
    font-size: 16px;
    color: green;
    font-weight: bold;
    
}

@media (max-width: 768px) {
    .product-detail {
        flex-direction: column;
        padding: 15px;
    }

    .product-image img {
        max-height: 300px;
    }

    .product-info h2 {
        font-size: 28px;
    }

    .product-info .price {
        font-size: 24px;
    }

    .actions form {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
/* Phần Đánh giá sản phẩm */
.product-reviews {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
}

.product-reviews h3 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 20px;
}

.product-reviews ul {
    list-style-type: none;
    padding: 0;
}

.product-reviews li {
    border-bottom: 1px solid #ddd;
    padding: 15px 0;
}

.product-reviews li:last-child {
    border-bottom: none;
}

.product-reviews p {
    margin: 5px 0;
}

/* Phần Sản phẩm liên quan */
.related-products {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.related-products h3 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 20px;
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product-item {
    flex: 1;
    min-width: 220px;
    max-width: 250px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 20px;
}

.product-item img {
    max-width: 100%;
    max-height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

.product-item h4 {
    font-size: 20px;
    color: #333;
    margin: 15px 0;
}

.product-item p {
    font-size: 18px;
    color: #e74c3c;
    margin-bottom: 15px;
}

.product-item a {
    display: inline-block;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.product-item a:hover {
    background-color: #2980b9;
}


    </style>
</head>
<body>
<div class="product-detail">
    <div class="product-image">
        <img src="<?php echo htmlspecialchars($product['Anh_SP']); ?>" alt="<?php echo htmlspecialchars($product['Ten_SP']); ?>">
    </div>
    <div class="product-info">
        <h2><?php echo htmlspecialchars($product['Ten_SP']); ?></h2>
        <p class="price"><?php echo number_format($product['Gia_SP'], 0, ',', '.') . " VND"; ?></p>
        <p><strong>Mô tả:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($product['Mo_ta_SP'])); ?></p>
        <div class="actions">
            <form method="POST" action="">
                <select name="quantity">
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> </option>
                    <?php } ?>
                </select>
                <button type="submit" name="add_to_cart">Thêm vào giỏ hàng</button>
            </form>
        </div>
        <?php
        if (isset($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
        ?>
        <div class="actions">
            <a href="cart.php">
                <button>Xem giỏ hàng</button>
            </a>
        </div>
    </div>
</div>


<!-- Phần Đánh Giá -->
<div class="product-detail">
    <div class="product-reviews">
        <h3>Đánh giá sản phẩm</h3>
        <?php if (count($reviews) > 0) { ?>
            <ul>
                <?php foreach ($reviews as $review) { ?>
                    <li>
                        <p><strong>Đánh giá:</strong> <?php echo str_repeat("⭐", $review['Star']); ?></p>
                        <p><strong>Nội dung:</strong> <?php echo nl2br(htmlspecialchars($review['Binh_luan'])); ?></p>
                        <p><strong>Ngày bình luận:</strong> <?php echo $review['Ngay_BL']; ?></p>
                        <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($review['ID_KH']); ?></p>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        <?php } ?>
    </div>
</div>

<!-- Sản phẩm liên quan -->
<div class="product-detail">
    <div class="related-products">
        <h3>Sản phẩm liên quan</h3>
        <div class="product-list">
            <?php foreach ($related_products as $related_product) { ?>
                <div class="product-item">
                    <img src="<?php echo htmlspecialchars($related_product['Anh_SP']); ?>" alt="<?php echo htmlspecialchars($related_product['Ten_SP']); ?>">
                    <h4><?php echo htmlspecialchars($related_product['Ten_SP']); ?></h4>
                    <p><?php echo number_format($related_product['Gia_SP'], 0, ',', '.') . " VND"; ?></p>
                    <a href="product_detail.php?ID_SP=<?php echo $related_product['ID_SP']; ?>">Xem chi tiết</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
<?php
$conn->close();
?>