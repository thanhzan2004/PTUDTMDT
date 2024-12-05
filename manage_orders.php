<?php
session_start();
include('database.php'); // Kết nối cơ sở dữ liệu

// Kiểm tra nếu có thông tin cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDH = $_POST['ID_DH'];
    $newStatus = $_POST['Trang_thai_giao_hang'];

    // Kết nối lại để cập nhật thông tin đơn hàng
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Cập nhật trạng thái giao hàng
    $updateSql = "UPDATE Don_hang SET Trang_thai_giao_hang = '$newStatus' WHERE ID_DH = '$idDH'";

    if ($conn->query($updateSql) === TRUE) {
        echo "Cập nhật thành công";
    } else {
        echo "Lỗi cập nhật: " . $conn->error;
    }

    $conn->close();
    exit(); // Kết thúc script để tránh việc trả về phần HTML không cần thiết
}

// Lấy danh sách đơn hàng
$sqlOrders = "SELECT * FROM Don_hang";
$resultOrders = $conn->query($sqlOrders);
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <!-- Liên kết với file CSS -->
    <link rel="stylesheet" href="style_order.css">
</head>
<body>
    <h1>Quản lý đơn hàng</h1>

    <table>
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Ngày Đặt</th>
                <th>Trạng Thái Giao Hàng</th>
                <th>Tổng Tiền</th>
                <th>Chỉnh Sửa</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultOrders->num_rows > 0): ?>
                <?php while ($order = $resultOrders->fetch_assoc()): ?>
                    <tr id="order-<?php echo $order['ID_DH']; ?>">
                        <td><?php echo $order['ID_DH']; ?></td>
                        <td><?php echo $order['Ngay_dat']; ?></td>
                        <td id="status-<?php echo $order['ID_DH']; ?>"><?php echo $order['Trang_thai_giao_hang']; ?></td>
                        <td><?php echo $order['Tong_tien']; ?></td>
                        <td>
                            <button onclick="showEditForm('<?php echo $order['ID_DH']; ?>', '<?php echo $order['Trang_thai_giao_hang']; ?>')">Chỉnh sửa</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Không có đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Form chỉnh sửa trạng thái giao hàng -->
    <div id="editOrderForm" style="display:none;">
        <h2>Chỉnh sửa đơn hàng</h2>
        <form id="updateOrderForm">
            <input type="hidden" name="ID_DH" id="orderID">
            <label for="Trang_thai_giao_hang">Trạng Thái Giao Hàng:</label><br>
            <select name="Trang_thai_giao_hang" id="Trang_thai_giao_hang">
                <option value="Chưa giao">Chưa giao</option>
                <option value="Đang giao">Đang giao</option>
                <option value="Đã giao">Đã giao</option>
            </select><br><br>

            <input type="submit" value="Cập nhật">
        </form>
    </div>

    <script>
        // Hàm để hiển thị form chỉnh sửa khi nhấn nút "Chỉnh sửa"
        function showEditForm(orderID, currentStatus) {
            document.getElementById('editOrderForm').style.display = 'block';
            document.getElementById('orderID').value = orderID;
            document.getElementById('Trang_thai_giao_hang').value = currentStatus;
        }

        // Xử lý sự kiện khi submit form chỉnh sửa
        document.getElementById('updateOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();  // Ngừng việc submit form thông qua HTTP

            var orderID = document.getElementById('orderID').value;
            var newStatus = document.getElementById('Trang_thai_giao_hang').value;

            // Tạo yêu cầu AJAX để gửi dữ liệu
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'manage_orders.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Cập nhật trạng thái trên bảng mà không cần tải lại trang
                    document.getElementById('status-' + orderID).innerHTML = newStatus;
                    document.getElementById('editOrderForm').style.display = 'none';  // Đóng form
                    alert('Cập nhật trạng thái thành công!');
                } else {
                    alert('Có lỗi xảy ra: ' + xhr.responseText);
                }
            };
            xhr.send('ID_DH=' + orderID + '&Trang_thai_giao_hang=' + newStatus);
        });
    </script>
</body>
</html>
