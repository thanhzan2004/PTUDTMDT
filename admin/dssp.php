<?php
include('database.php');
include 'sidebar.html';

// Lấy danh sách sản phẩm (bao gồm cột hình ảnh)
$sql = "SELECT ID_SP, Ten_SP, Gia_SP, Anh_SP FROM San_pham"; // Bỏ "So_luong_ton_kho"
$result = $conn->query($sql);

// Xử lý khi nhấn nút xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $id_sp = $_POST['id_sp'];

    // Xóa sản phẩm
    $delete_sql = "DELETE FROM San_pham WHERE ID_SP = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $id_sp);

    if ($stmt->execute()) {
        // Chuyển hướng để tránh gửi lại form
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('Lỗi khi xóa sản phẩm: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>
    <div class="main-content">
        <h1>Quản lý Sản phẩm</h1>
        <form action="dssp_add.php" method="get">
            <button type="submit" class="btn-add-new">Thêm Sản Phẩm Mới</button>
        </form>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Hình ảnh</th> <!-- Cột hình ảnh -->
                    <th>Chỉnh Sửa</th> <!-- Cột chỉnh sửa -->
                    <th>Xóa</th> <!-- Cột xóa -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['ID_SP'] . "</td>";
                        echo "<td>" . $row['Ten_SP'] . "</td>";
                        echo "<td>" . $row['Gia_SP'] . "</td>";
                        
                        // Hiển thị hình ảnh
                        echo "<td><img src='" . $row['Anh_SP'] . "' alt='Hình ảnh sản phẩm' width='100'></td>"; 

                        // Cột chỉnh sửa
                        echo "<td>
                                <a href='dssp_edit.php?id_sp=" . $row["ID_SP"] . "'>
                                    <button class='btn-edit'>Chỉnh sửa</button>
                                </a>
                              </td>";
                        
                        // Cột xóa
                        echo "<td>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_sp' value='" . $row['ID_SP'] . "'>
                                    <button type='submit' name='delete_product' class='btn-delete' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này?\");'>Xóa</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sản phẩm nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
