<?php
include('database.php');
include 'sidebar.html';

// Lấy danh sách bài viết
$sql = "SELECT ID_Bai_viet, Tua_de, Ngay_viet, Phan_loai FROM Bai_viet";
$result = $conn->query($sql);

// Xử lý khi form xóa bài viết
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bai_viet'])) {
    $id_bai_viet = $_POST['id_bai_viet'];

    // Xóa bài viết
    $delete_sql = "DELETE FROM Bai_viet WHERE ID_Bai_viet = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $id_bai_viet);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa bài viết thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa bài viết: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bài Viết</title>
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>
    <div class="main-content">
        <h1>Quản lý Bài Viết</h1>
        <form action="dsbv_add.php" method="get">
            <button type="submit" class="btn-add-new">Thêm Bài Viết Mới</button>
        </form>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Bài Viết</th>
                    <th>Tựa Đề</th>
                    <th>Ngày Viết</th>
                    <th>Phân Loại</th>
                    <th>Chỉnh Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hiển thị danh sách bài viết
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Bai_viet"] . "</td>";
                        echo "<td>" . $row["Tua_de"] . "</td>";
                        echo "<td>" . $row["Ngay_viet"] . "</td>";
                        echo "<td>" . $row["Phan_loai"] . "</td>";

                        // Cột chỉnh sửa
                        echo "<td>
                                <a href='dsbv_edit.php?id_bai_viet=" . $row["ID_Bai_viet"] . "'>
                                    <button>Chỉnh sửa</button>
                                </a>
                              </td>";

                        // Cột xóa
                        echo "<td>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_bai_viet' value='" . $row["ID_Bai_viet"] . "'>
                                    <button type='submit' name='delete_bai_viet' class='delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bài viết này?\");'>Xóa</button>
                                </form>
                              </td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có bài viết nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
