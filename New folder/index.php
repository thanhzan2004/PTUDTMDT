<?php
// Kết nối cơ sở dữ liệu
$host = "localhost";
$user = "root"; // Username của MySQL
$password = ""; // Mật khẩu của MySQL (để trống nếu dùng XAMPP)
$database = "ecommerce"; // Tên cơ sở dữ liệu

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

include 'header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Thương Mại Điện Tử</title>
    <link rel="stylesheet" href="styles.css"> <!-- Bạn có thể tạo file CSS riêng để định dạng giao diện -->
</head>
<body>
    <!-- Thanh điều hướng -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Trang Chủ</a></li>
                <li><a href="about.php">Giới Thiệu</a></li>
                <li><a href="products.php">Sản Phẩm</a></li>
                <li><a href="contact.php">Liên Hệ</a></li>
                <li><a href="cart.php">Giỏ Hàng</a></li>
                <li><a href="checkout.php">Thanh Toán</a></li>
                <li><a href="login.php">Đăng Nhập</a></li>
            </ul>
        </nav>
    </header>

    <!-- Banner giới thiệu -->
    <section class="banner">
        <h1>Chào mừng đến với cửa hàng trực tuyến của chúng tôi!</h1>
        <p>Khám phá các sản phẩm tuyệt vời ngay hôm nay.</p>
    </section>

    <!-- Giới thiệu về doanh nghiệp -->
    <section class="about">
        <h2>Giới Thiệu</h2>
        <p><?= htmlspecialchars($about['content']); ?></p>
        <a href="about.php">Tìm hiểu thêm</a>
    </section>

    <!-- Sản phẩm mới nhất -->
    <section class="products">
        <h2>Sản phẩm mới</h2>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" />
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <p><?= number_format($product['price'], 0, ',', '.'); ?> VND</p>
                    <a href="product_details.php?id=<?= $product['id']; ?>">Xem chi tiết</a>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="products.php">Xem tất cả sản phẩm</a>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Thương Mại Điện Tử. Tất cả các quyền được bảo lưu.</p>
        <nav>
            <ul>
                <li><a href="privacy.php">Chính sách bảo mật</a></li>
                <li><a href="terms.php">Điều khoản sử dụng</a></li>
            </ul>
        </nav>
    </footer>
</body>
</html>
