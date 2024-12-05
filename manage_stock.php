<?php
// Kết nối cơ sở dữ liệu
include('database.php');

// Kiểm tra nếu có yêu cầu cập nhật số lượng
if (isset($_POST['update'])) {
    $id_sp = $_POST['ID_SP'];
    $new_stock = $_POST['So_luong_ton_kho'];

    // Cập nhật số lượng tồn kho trong bảng San_pham
    $updateQuery = "UPDATE San_pham SET So_luong_ton_kho = ? WHERE ID_SP = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('is', $new_stock, $id_sp);
    $stmt->execute();
    $stmt->close();
}

// Lấy tất cả sản phẩm và hiển thị
$query = "SELECT sp.ID_SP, sp.Ten_SP, dm.Ten_DM, sp.So_luong_ton_kho
          FROM San_pham sp
          INNER JOIN Danh_muc dm ON sp.ID_Danh_muc = dm.ID_Danh_muc";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hàng Tồn Kho</title>
    <!-- Tham chiếu đến file styles.css -->
    <link rel="stylesheet" href="style_stock.css">
</head>
<body>
    <h1>Quản lý Hàng Tồn Kho</h1>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Mã Sản Phẩm</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Số Lượng Tồn Kho</th>
                    <th>Trạng Thái</th>
                    <th>Chỉnh Sửa</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['ID_SP'] ?></td>
                        <td><?= $row['Ten_SP'] ?></td>
                        <td><?= $row['Ten_DM'] ?></td>
                        <td>
                            <form method="POST" action="manage_stock.php">
                                <input type="number" name="So_luong_ton_kho" value="<?= $row['So_luong_ton_kho'] ?>" min="0" required>
                                <input type="hidden" name="ID_SP" value="<?= $row['ID_SP'] ?>">
                        </td>
                        <td>
                            <?php
                            if ($row['So_luong_ton_kho'] > 0) {
                                echo "<span class='in-stock'>Còn hàng</span>";
                            } else {
                                echo "<span class='out-of-stock'>Hết hàng</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <input type="submit" name="update" value="Cập nhật">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
