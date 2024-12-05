<?php
include('database.php');

// Lấy ID khách hàng từ URL
if (isset($_GET['id'])) {
    $id_kh = $_GET['id'];

    // Truy vấn thông tin khách hàng
    $sql = "SELECT * FROM Khach_hang WHERE ID_KH = '$id_kh'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy khách hàng.";
    }
} else {
    echo "ID khách hàng không hợp lệ.";
}

if (isset($_POST['edit'])) {
    $id_kh = $_POST['id_kh'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];

    $edit_sql = "UPDATE Khach_hang SET Ho_ten='$ho_ten', Ngay_sinh='$ngay_sinh', Gioi_tinh='$gioi_tinh', SDT='$sdt', Dia_chi='$dia_chi' WHERE ID_KH='$id_kh'";

    if ($conn->query($edit_sql) === TRUE) {
        echo "Thông tin khách hàng đã được cập nhật.";
        header("Location: manage_customers.php"); // Quay lại trang quản lý khách hàng
    } else {
        echo "Lỗi khi cập nhật thông tin khách hàng: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Thông tin Khách hàng</title>
    <link rel="stylesheet" href="style_edit_cus.css">
</head>
<body>

    <header>
        <h1>Chỉnh sửa Thông tin Khách hàng</h1>
    </header>

    <div class="container">
        <form method="POST" class="edit-form">
            <input type="hidden" name="id_kh" value="<?php echo $row['ID_KH']; ?>">

            <label for="ho_ten">Họ tên:</label>
            <input type="text" name="ho_ten" value="<?php echo $row['Ho_ten']; ?>" required>

            <label for="ngay_sinh">Ngày sinh:</label>
            <input type="date" name="ngay_sinh" value="<?php echo $row['Ngay_sinh']; ?>" required>

            <label for="gioi_tinh">Giới tính:</label>
            <input type="text" name="gioi_tinh" value="<?php echo $row['Gioi_tinh']; ?>" required>

            <label for="sdt">Số điện thoại:</label>
            <input type="text" name="sdt" value="<?php echo $row['SDT']; ?>" required>

            <label for="dia_chi">Địa chỉ:</label>
            <input type="text" name="dia_chi" value="<?php echo $row['Dia_chi']; ?>" required>

            <button type="submit" name="edit" class="btn-update">Cập nhật</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Lemonade</p>
    </footer>

</body>
</html>

<?php
$conn->close(); // Đóng kết nối cơ sở dữ liệu
?>
