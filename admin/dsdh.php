<?php
include('database.php');
include 'sidebar.html';

// Lấy danh sách đơn hàng
$sql = "SELECT ID_DH, So_luong_SP, Cach_thanh_toan, Dia_chi, Ngay_dat, Trang_thai_thanh_toan, Trang_thai_giao_hang, Tong_tien, SDT, Ten_khach_hang, Email, Ghi_chu 
        FROM (
            SELECT ID_DH, SUM(So_luong_SP) AS So_luong_SP, Cach_thanh_toan, Dia_chi, Ngay_dat, Trang_thai_thanh_toan, Trang_thai_giao_hang, SUM(Gia_SP * So_luong_SP) AS Tong_tien, SDT, Ten_khach_hang, Email, Ghi_chu
            FROM don_hang_chi_tiet
            GROUP BY ID_DH
        ) AS grouped_don_hang
        ORDER BY Ngay_dat DESC";
$result = $conn->query($sql);

// Xử lý khi form xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_don_hang'])) {
    $id_dh = $_POST['id_dh'];

    // Xóa đơn hàng chi tiết
    $delete_sql = "DELETE FROM don_hang_chi_tiet WHERE ID_DH = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $id_dh);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa đơn hàng thành công!'); window.location.href='don_hang_list.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa đơn hàng: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn Hàng</title>
    <link rel="stylesheet" href="style_admin.css">
    <style>
        .edit-btn {
    display: inline-block;
    padding: 5px 10px;
    background-color: #ffb300;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.edit-btn:hover {
    background-color: #ffa000;
}
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Quản lý Đơn Hàng</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Số Lượng</th>
                    <th>Phương Thức Thanh Toán</th>
                    <th>Địa Chỉ</th>
                    <th>Ngày Đặt</th>
                    <th>TT Thanh Toán</th>
                    <th>TT Giao Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Họ Tên KH</th>
                    <th>SĐT</th>
                    <th>Ghi Chú</th>
                    <th>Chỉnh Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hiển thị danh sách đơn hàng
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_DH"] . "</td>";
                        echo "<td>" . $row["So_luong_SP"] . "</td>";
                        echo "<td>" . $row["Cach_thanh_toan"] . "</td>";
                        echo "<td>" . $row["Dia_chi"] . "</td>";
                        echo "<td>" . $row["Ngay_dat"] . "</td>";
                        echo "<td>" . $row["Trang_thai_thanh_toan"] . "</td>";
                        echo "<td>" . $row["Trang_thai_giao_hang"] . "</td>";
                        echo "<td>" . number_format($row["Tong_tien"], 2) . " VND</td>";
                        echo "<td>" . $row["Ten_khach_hang"] . "</td>";
                        echo "<td>" . $row["SDT"] . "</td>";
                        echo "<td>" . $row["Ghi_chu"] . "</td>";
                        // Thêm nút Chỉnh sửa
                        echo "<td>
                                <a href='dsdh_edit.php?id_dh=" . $row["ID_DH"] . "' class='edit-btn'>Chỉnh Sửa</a>
                              </td>";
                        // Nút Xóa
                        echo "<td>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_dh' value='" . $row["ID_DH"] . "'>
                                    <button type='submit' name='delete_don_hang' class='delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa đơn hàng này?\");'>Xóa</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>Không có đơn hàng nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

