<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = ""; // Điền mật khẩu nếu có
$database = "ptudtmdt";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

include('header.html');


// Lấy 3 sản phẩm gần nhất
$product_sql = "SELECT ID_SP, Ten_SP, Gia_SP, Anh_SP FROM San_pham ORDER BY ID_SP DESC LIMIT 3";
$product_result = $conn->query($product_sql);

// Lấy 3 bài blog gần nhất
$blog_sql = "SELECT ID_Bai_viet, Tua_de, Noi_dung, Anh_bai_viet FROM Bai_viet ORDER BY ID_Bai_viet DESC LIMIT 3";
$blog_result = $conn->query($blog_sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fffbea;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Phần giới thiệu */
        .about-preview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f9f9f9;
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .about-preview img {
            width: 40%;
            border-radius: 10px;
            object-fit: cover;
        }

        .about-text {
            width: 55%;
            padding: 0 20px;
        }

        .about-text h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .about-text p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-more {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fdda44;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-more:hover {
            background-color: #e5c633;
        }

        /* Phần sản phẩm và blog */
        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .item {
            background-color: #fff5cc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .item h3 {
            font-size: 18px;
            color: #e6ac00;
            margin-bottom: 10px;
        }

        .item p {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .item a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fdda44;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }

        .item a:hover {
            background-color: #e5c633;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Phần giới thiệu -->
        <div class="about-preview">
            <img src="https://lh7-us.googleusercontent.com/hDkp-bo_7shrzfbOORBKNGiMovvo_jJLuCbYECB-iyc27q_kyxvffK7d10G-uIVYwJyKaQvlMFhx17viBoNxFZ_oMCMtjROwguZ30yQ-NVMtelgRDdwOldlCfe_No_xCH5hE3NLU2y3MYEKyfooXnpg" alt="Giới thiệu">
            <div class="about-text">
                <h2>Về chúng tôi</h2>
                <p>Lemonade Cosmetics là thương hiệu mỹ phẩm Việt Nam, được thành lập vào năm 2018 bởi Makeup Artist Quách Ánh với mục tiêu mang đến giải pháp trang điểm dễ dàng cho phụ nữ Việt.</p>
                <a href="about.php" class="btn-more">Xem thêm</a>
            </div>
        </div>

        <!-- Phần 3 sản phẩm gần nhất -->
        <div class="section">
            <h2>Sản phẩm mới nhất</h2>
            <div class="grid">
                <?php while ($product = $product_result->fetch_assoc()): ?>
                    <div class="item">
                        <img src="<?php echo $product['Anh_SP']; ?>" alt="<?php echo htmlspecialchars($product['Ten_SP']); ?>">
                        <h3><?php echo htmlspecialchars($product['Ten_SP']); ?></h3>
                        <p><?php echo number_format($product['Gia_SP'], 0, ',', '.'); ?> VND</p>
                        <a href="productlist.php">Chi tiết</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Phần 3 bài blog gần nhất -->
        <div class="section">
            <h2>Bài viết mới nhất</h2>
            <div class="grid">
                <?php while ($blog = $blog_result->fetch_assoc()): ?>
                    <div class="item">
                        <img src="<?php echo $blog['Anh_bai_viet']; ?>" alt="<?php echo htmlspecialchars($blog['Tua_de']); ?>">
                        <h3><?php echo htmlspecialchars($blog['Tua_de']); ?></h3>
                        <p><?php echo substr(strip_tags($blog['Noi_dung']), 0, 100); ?>...</p>
                        <a href="bloglist.php">Chi tiết</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
