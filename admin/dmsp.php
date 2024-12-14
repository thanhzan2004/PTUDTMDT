



<?php
include('database.php');
include 'sidebar.html';

// Lấy danh sách danh mục
$sql = "SELECT ID_Danh_muc, Ten_DM FROM Danh_muc";
$result = $conn->query($sql);

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_dm'])) {
        $id_danh_muc = $_POST['id_danh_muc'];
        $ten_dm = $_POST['ten_dm'];

        // Cập nhật tên danh mục
        $update_sql = "UPDATE Danh_muc SET Ten_DM = ? WHERE ID_Danh_muc = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $ten_dm, $id_danh_muc);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật danh mục thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật danh mục: " . $stmt->error . "');</script>";
        }
    } elseif (isset($_POST['delete_dm'])) {
        $id_danh_muc = $_POST['id_danh_muc'];

        // Xóa danh mục
        $delete_sql = "DELETE FROM Danh_muc WHERE ID_Danh_muc = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $id_danh_muc);

        if ($stmt->execute()) {
            echo "<script>alert('Xóa danh mục thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa danh mục: " . $stmt->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh Mục</title>
    <link rel="stylesheet" href="style_admin.css"> <!-- Liên kết đến file CSS -->
</head>
<body>
    <div class="main-content">
        <h1>Quản lý Danh Mục</h1>
        <form action="dmsp_add.php" method="get">
            <button type="submit" class="btn-add-new">Thêm Danh Mục Mới</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID Danh Mục</th>
                    <th>Tên Danh Mục</th>
                    <th>Chỉnh Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hiển thị danh sách danh mục
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Danh_muc"] . "</td>";
                        echo "<td>" . $row["Ten_DM"] . "</td>";
                        // Cột Chỉnh sửa
                        echo "<td>
                                <a href='dmsp_edit.php?id_danh_muc=" . $row["ID_Danh_muc"] . "'>
                                    <button type='button' class='btn-edit'>Chỉnh sửa</button>
                                </a>
                              </td>";
                        // Cột Xóa
                        echo "<td>
                                <form method='POST' style='display: inline-block;'>
                                    <input type='hidden' name='id_danh_muc' value='" . $row["ID_Danh_muc"] . "'>
                                    <button type='submit' name='delete_dm' class='btn-delete' onclick='return confirm(\"Bạn có chắc chắn muốn xóa danh mục này?\");'>Xóa</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có danh mục nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
