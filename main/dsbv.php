<?php
include('database.php');
include 'sidebar.html';

// Lấy danh sách bài viết
$sql = "SELECT ID_Bai_viet, Tua_de, Ngay_viet, Phan_loai FROM Bai_viet";
$result = $conn->query($sql);

// Xử lý khi form được gửi để cập nhật bài viết
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bai_viet'])) {
    $id_bai_viet = $_POST['id_bai_viet'];
    $tua_de = $_POST['tua_de'];
    $ngay_viet = $_POST['ngay_viet'];
    $phan_loai = $_POST['phan_loai'];

    // Cập nhật thông tin bài viết
    $update_sql = "UPDATE Bai_viet SET Tua_de = ?, Ngay_viet = ?, Phan_loai = ? WHERE ID_Bai_viet = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $tua_de, $ngay_viet, $phan_loai, $id_bai_viet);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật bài viết thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật bài viết: " . $stmt->error . "');</script>";
    }
}

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
    <link rel="stylesheet" href="style_admin.css"> <!-- Liên kết đến file CSS -->
</head>
<body>
    <div class="main-content">
        <h1>Quản lý Bài Viết</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Bài Viết</th>
                    <th>Tựa Đề</th>
                    <th>Ngày Viết</th>
                    <th>Phân Loại</th>
                    <th>Thao Tác</th>
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
                        echo "<td>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_bai_viet' value='" . $row["ID_Bai_viet"] . "'>
                                    <button type='submit' name='update_bai_viet' class='edit-btn'>Chỉnh sửa</button>
                                </form>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_bai_viet' value='" . $row["ID_Bai_viet"] . "'>
                                    <button type='submit' name='delete_bai_viet' class='delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bài viết này?\");'>Xóa</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có bài viết nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
