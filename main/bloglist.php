<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = ""; // Điền mật khẩu nếu có
$database = "ptudtmdt";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

include('header.html');

// Xử lý phân trang
$limit = 3; // Số bài viết mỗi trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Lấy trang hiện tại
$page = max($page, 1); // Đảm bảo trang >= 1
$offset = ($page - 1) * $limit; // Tính toán offset

// Truy vấn bài viết
$sql = "SELECT ID_Bai_viet, Tua_de, Ngay_viet, Noi_dung, Anh_bai_viet FROM Bai_viet LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Kiểm tra lỗi SQL
if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error . "<br>Query: " . $sql);
}

// Tính tổng số trang
$total_sql = "SELECT COUNT(*) as total FROM Bai_viet";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['total'];
$total_pages = ceil($total_articles / $limit);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài viết</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fffbea;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
    }

    .article-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .article-item {
        background-color: #fff5cc;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .article-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .article-item img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .article-item h3 {
        font-size: 1.5rem;
        color: #e6ac00;
        margin-top: 15px;
    }

    .article-item p {
        font-size: 1rem;
        color: #555;
        margin: 15px 0;
    }

    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 5px;
        text-decoration: none;
        color: #e6ac00;
        border: 1px solid #e6ac00;
        border-radius: 5px;
    }

    .pagination a.active {
        background-color: #e6ac00;
        color: white;
    }

    .pagination a:hover {
        background-color: #d99100;
    }
    </style>
</head>
<body>

<div class="container">
    <!-- Danh sách bài viết -->
    <div class="article-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="article-item">';
                echo "<img src='" . $row['Anh_bai_viet'] . "' alt='Hình ảnh bài viết' />";
                echo '<h3>' . htmlspecialchars($row['Tua_de']) . '</h3>';
                echo '<p>' . substr(strip_tags($row['Noi_dung']), 0, 150) . '...</p>';
                echo '<a href="bloglist_detail.php?ID_Bai_viet=' . urlencode($row['ID_Bai_viet']) . '" style="background: #e6ac00; color: white; padding: 10px 20px; text-decoration: none;">Xem chi tiết</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Không có bài viết nào.</p>';
        }
        ?>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="prev">« Trước</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>" class="next">Sau »</a>
    <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>

<?php
include('footer.html');
?>