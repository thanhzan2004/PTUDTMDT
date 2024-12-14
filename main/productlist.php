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

// Lấy giá trị tìm kiếm từ URL
$search_query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$price_filter = isset($_GET['price']) ? intval($_GET['price']) : 0;

// Điều kiện mặc định
$where_clause = "WHERE 1=1"; // Không có điều kiện tìm kiếm

// Nếu có từ khóa tìm kiếm, thêm điều kiện vào truy vấn
if (!empty($search_query)) {
    $where_clause .= " AND Ten_SP LIKE '%$search_query%'";
}

// Nếu có bộ lọc danh mục
if (!empty($category_filter)) {
    $where_clause .= " AND ID_Danh_Muc = '" . $conn->real_escape_string($category_filter) . "'";
}

// Nếu có bộ lọc giá
if ($price_filter > 0) {
    $where_clause .= " AND Gia_SP <= " . $price_filter;
}

// Xử lý phân trang
$limit = 9; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Lấy trang hiện tại
$page = max($page, 1); // Đảm bảo trang >= 1
$offset = ($page - 1) * $limit; // Tính toán offset

// Truy vấn danh sách sản phẩm với bộ lọc và phân trang
$sql = "SELECT ID_SP, Ten_SP, Gia_SP, Anh_SP FROM San_pham $where_clause LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Kiểm tra lỗi SQL
if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error . "<br>Query: " . $sql);
}

// Tính tổng số trang
$total_sql = "SELECT COUNT(*) as total FROM San_pham $where_clause";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Truy vấn danh mục
$category_sql = "SELECT ID_Danh_Muc, Ten_DM FROM Danh_muc";
$category_result = $conn->query($category_sql);
if (!$category_result) {
    die("Lỗi truy vấn danh mục: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #fffbea; /* Gam màu nền vàng nhạt */
    color: #333;
}

.container {
    display: flex;
    min-width: 1200px;
    margin: 30px auto;
    gap: 30px;
    height: 100%;
}

.filter {
    width: 30%;
    background-color: #fff5cc; /* Vàng nhạt */
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.filter h3 {
    font-size: 1.5rem;
    color: #e6ac00; /* Màu vàng đậm */
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
    text-transform: uppercase;
}

.filter select, .filter input[type="range"], .filter button {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #e6ac00; /* Viền vàng */
    border-radius: 5px;
    font-size: 1rem;
    background-color: #fffbe6; /* Màu vàng nhạt */
    color: #333;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.filter select:focus, .filter input[type="range"]:focus, .filter button:focus {
    border-color: #ffc107; /* Vàng sáng */
    box-shadow: 0 0 8px rgba(255, 193, 7, 0.5);
    outline: none;
}

.filter input[type="range"] {
    width: 90%; /* Giảm chiều dài thanh trượt */
    margin: 0 auto 10px; /* Căn giữa và tạo khoảng cách */
    height: 6px; /* Giảm chiều cao thanh trượt */
    background: linear-gradient(to right, #e6ac00, #ffcc00); /* Giữ gradient vàng */
    border-radius: 3px; /* Bo tròn nhẹ hơn */
    outline: none;
    cursor: pointer;
}

.filter input[type="range"]::-webkit-slider-thumb {
    width: 12px; /* Giảm kích thước đầu trượt */
    height: 12px;
    background-color: #e6ac00; /* Màu vàng đậm */
    border-radius: 50%;
    border: 2px solid #fff; /* Giữ viền trắng */
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter input[type="range"]::-webkit-slider-thumb:hover {
    background-color: #ffc107; /* Vàng sáng khi hover */
}

.filter button {
    width: auto; /* Thu hẹp nút để vừa nội dung */
    padding: 8px 20px; /* Giảm padding cho nút */
    font-size: 0.9rem; /* Giảm cỡ chữ */
}

.filter {
    padding: 15px; /* Giảm padding của hộp lọc */
}


.filter button:hover {
    background-color: #d99100; /* Vàng cam khi hover */
    transform: translateY(-2px);
}
/* Khung tìm kiếm */
.filter input[type="text"] {
    width: 95%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #e6ac00; /* Viền vàng */
    border-radius: 5px;
    font-size: 1rem;
    background-color: #fffbe6; /* Màu vàng nhạt */
    color: #333;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

/* Khi ô tìm kiếm được focus */
.filter input[type="text"]:focus {
    border-color: #ffc107; /* Vàng sáng khi focus */
    box-shadow: 0 0 8px rgba(255, 193, 7, 0.5);
    outline: none;
}


.products {
    width: 70%; /* Chiếm 70% chiều rộng */
}

.search-result {
    background-color: #fffbea;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 cột */
    gap: 30px; /* Khoảng cách giữa các sản phẩm */
}

.product-item {
    background-color: #fff5cc; /* Màu vàng nhạt */
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.product-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.product-item:hover img {
    transform: scale(1.05);
}

.product-item h3 {
    font-size: 1.5rem;
    color: #e6ac00; /* Màu vàng đậm */
    margin-top: 15px;
}

.product-item p {
    font-size: 1rem;
    color: #555;
    margin: 15px 0;
}

.product-item a {
    display: inline-block;
    padding: 12px 25px;
    background-color: #e6ac00; /* Vàng đậm */
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.product-item a:hover {
    background-color: #d99100; /* Vàng cam khi hover */
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
    color: #e6ac00; /* Vàng đậm */
    border: 1px solid #e6ac00;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.pagination a.active {
    background-color: #e6ac00; /* Vàng đậm */
    color: white;
}

.pagination a:hover {
    background-color: #d99100; /* Vàng cam khi hover */
}


    </style>
</head>
<body>

<div class="container">
   <!-- Form tìm kiếm và lọc sản phẩm -->
<div class="filter">
    <h3>Tìm kiếm và Lọc sản phẩm</h3>
    <form method="GET" action="">
        <!-- Trường tìm kiếm từ khóa -->
        <div>
            <label for="query">Tìm kiếm sản phẩm:</label>
            <input type="text" id="query" name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" placeholder="Nhập từ khóa tìm kiếm...">
        </div>

        <!-- Bộ lọc danh mục -->
        <div>
            <label for="category">Danh mục:</label>
            <select id="category" name="category">
                <option value="">Tất cả</option>
                <?php while ($cat = $category_result->fetch_assoc()) {
                    $selected = ($cat['ID_Danh_Muc'] == $category_filter) ? 'selected' : '';
                    echo '<option value="' . $cat['ID_Danh_Muc'] . '" ' . $selected . '>' . htmlspecialchars($cat['Ten_DM']) . '</option>';
                } ?>
            </select>
        </div>

        <!-- Bộ lọc giá -->
        <div>
            <label for="price">Khoảng giá:</label>
            <input type="range" id="price" name="price" min="0" max="1000000" step="1000" value="<?php echo $price_filter; ?>" oninput="updatePriceLabel(this.value)">
            <span id="priceValue"><?php echo $price_filter > 0 ? number_format($price_filter, 0, ',', '.') . ' VND' : 'Tất cả'; ?></span>
        </div>

        <div style="margin-top: 10px;">
            <button type="submit">Áp dụng</button>
        </div>
    </form>
</div>

    <!-- Danh sách sản phẩm -->
    <div class="products">
        <?php if (!empty($search_query)): ?>
            <div class="search-result">
                <h3>Kết quả tìm kiếm cho từ khóa: "<?php echo htmlspecialchars($search_query); ?>"</h3>
            </div>
        <?php endif; ?>
        
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-item">';
                    echo "<img src='" . $row['Anh_SP'] . "' alt='Hình ảnh sản phẩm'>";
                    echo '<h3>' . htmlspecialchars($row['Ten_SP']) . '</h3>';
                    echo '<p>Giá: ' . number_format($row['Gia_SP'], 0, ',', '.') . ' VND</p>';
                    echo '<a href="product.php?ID_SP=' . $row['ID_SP'] . '">Chi tiết</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Không tìm thấy sản phẩm nào.</p>';
            }
            ?>
        </div>
    </div>
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
</div>

<script>
function updatePriceLabel(value) {
    document.getElementById('priceValue').innerText = value > 0 ? parseInt(value).toLocaleString() + " VND" : "Tất cả";
}
</script>

</body>
</html>


<?php
include('footer.html');
?>