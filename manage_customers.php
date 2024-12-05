<?php
include('database.php'); // Kết nối cơ sở dữ liệu

// Khởi tạo các biến lọc với giá trị mặc định là rỗng
$gioi_tinh_filter = isset($_POST['gioi_tinh']) ? $_POST['gioi_tinh'] : '';
$ngay_sinh_filter = isset($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : '';
$dia_chi_filter = isset($_POST['dia_chi']) ? $_POST['dia_chi'] : '';

// Xây dựng câu truy vấn với các điều kiện lọc
$sql = "SELECT * FROM Khach_hang WHERE 1";

// Thêm điều kiện lọc vào câu truy vấn nếu người dùng đã chọn
if ($gioi_tinh_filter != '') {
    $sql .= " AND Gioi_tinh LIKE '%$gioi_tinh_filter%'";
}
if ($ngay_sinh_filter != '') {
    $sql .= " AND Ngay_sinh LIKE '%$ngay_sinh_filter%'";
}
if ($dia_chi_filter != '') {
    $sql .= " AND Dia_chi LIKE '%$dia_chi_filter%'";
}

// Thực hiện câu truy vấn và kiểm tra kết quả
$result = $conn->query($sql);

if (!$result) {
    echo "Lỗi truy vấn: " . $conn->error;
    exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="styles.css"> <!-- Liên kết file CSS -->
</head>
<body>

    <div class="content">
        <h2>Quản lý Thông tin Khách hàng</h2>

        <!-- Form lọc khách hàng -->
        <form method="POST" action="manage_customers.php" class="filter-form">
            <label for="gioi_tinh">Giới tính:</label>
            <select name="gioi_tinh">
                <option value="">Chọn giới tính</option>
                <option value="Nam" <?php echo $gioi_tinh_filter == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo $gioi_tinh_filter == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                <option value="Khác" <?php echo $gioi_tinh_filter == 'Khác' ? 'selected' : ''; ?>>Khác</option>
            </select>

            <label for="ngay_sinh">Ngày sinh:</label>
            <input type="date" name="ngay_sinh" value="<?php echo $ngay_sinh_filter; ?>">

            <label for="dia_chi">Địa chỉ:</label>
            <select name="dia_chi">
                <option value="">Chọn địa chỉ</option>
                <option value="HaNoi" <?php echo $dia_chi_filter == 'HaNoi' ? 'selected' : ''; ?>>HaNoi</option>
                <option value="DaNang" <?php echo $dia_chi_filter == 'DaNang' ? 'selected' : ''; ?>>DaNang</option>
                <option value="Vinh" <?php echo $dia_chi_filter == 'Vinh' ? 'selected' : ''; ?>>Vinh</option>
            </select>

            <button type="submit" class="filter-button">Lọc</button>
        </form>

        <table border="1" class="customer-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['ID_KH'] . "</td>";
                        echo "<td>" . $row['Ho_ten'] . "</td>";
                        echo "<td>" . $row['Ngay_sinh'] . "</td>";
                        echo "<td>" . $row['Gioi_tinh'] . "</td>";
                        echo "<td>" . $row['SDT'] . "</td>";
                        echo "<td>" . $row['Dia_chi'] . "</td>";
                        echo "<td><img src='" . $row['Anh_KH'] . "' alt='Ảnh khách hàng' width='50' height='50'></td>";
                        echo "<td>
                                <a href='edit_customer.php?id=" . $row['ID_KH'] . "'>Chỉnh sửa</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Không có khách hàng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
