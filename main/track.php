<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "ptudtmdt";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

include('header.html');

// Xử lý khi người dùng gửi số điện thoại
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra xem SDT có được gửi hay không
    if (isset($_POST['SDT']) && !empty($_POST['SDT'])) {
        $SDT = $_POST['SDT'];

        // Truy vấn dữ liệu từ bảng don_hang_chi_tiet
        $stmt = $conn->prepare("SELECT dh.ID_DH, dh.Ngay_dat, dh.Trang_thai_thanh_toan, dh.Tong_tien, dh.Trang_thai_giao_hang, dh.SDT 
                                FROM don_hang_chi_tiet dh
                                WHERE dh.SDT = ?
                                GROUP BY dh.ID_DH");
        $stmt->bind_param('s', $SDT);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>KẾT QUẢ TRA CỨU ĐƠN HÀNG</h2>";
            echo "<table class='result-table'>
                    <tr>
                        <th>ID Đơn Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái Thanh Toán</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái Giao Hàng</th>
                        <th>SĐT Đặt hàng</th>
                        <th>Chi Tiết</th>  <!-- Cột mới -->
                    </tr>";
            
            // Hiển thị dữ liệu từ bảng don_hang_chi_tiet
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ID_DH']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Ngay_dat']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Trang_thai_thanh_toan']) . "</td>";
                echo "<td>" . number_format($row['Tong_tien'], 0, ',', '.') . " VND</td>";
                echo "<td>" . htmlspecialchars($row['Trang_thai_giao_hang']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SDT']) . "</td>";
                // Thêm nút Xem Chi Tiết dẫn đến track_detail.php
                echo "<td><a href='track_detail.php?id_dh=" . htmlspecialchars($row['ID_DH']) . "'>Xem Chi Tiết</a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Không tìm thấy đơn hàng nào với SDT này.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Vui lòng nhập SDT để tra cứu.</p>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff5cc;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2{
            text-align:center;
            margin-top:30px;
        }
        h3{
            margin-left:30px;
            margin-top:100px;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"] {
            width: calc(100%);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #e6ac00;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .container button:hover {
            background-color: #003399;
        }
        .result-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .result-table th, .result-table td {
            border: 1px solid black;
            padding: 8px;
        }
        .result-table th {
            background-color: #e6ac00;
        }
        .result-table a {
            text-decoration: none;
            color: #003399;
            font-weight: bold;
        }
        .result-table a:hover {
            text-decoration: underline;
        }
        li{
            line-height:25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TRA CỨU ĐƠN HÀNG</h1>
        <form method="POST" action="">
            <input type="text" id="SDT" name="SDT" placeholder="Nhập SĐT" required>
            <button type="submit">Tra cứu</button>
        </form>
    </div>
    <div class="contact-info">
        <h3>Liên hệ với chúng tôi nếu bạn đang cần gấp</h3>
        <ul>
            <li><strong>Điện thoại:</strong> 0379321256</li>
            <li><strong>Email:</strong> nguyenthaontt04@gmail.com</li>
            <li><strong>Địa chỉ:</strong> 279 Nguyễn Tri Phương, Phường 5, Quận 10, TP Hồ Chí Minh</li>
            <li><strong>Giờ làm việc:</strong> 9:00 AM - 6:00 PM (Thứ 2 đến Thứ 6)</li>
        </ul>
    </div>
</body>
</html>

<?php
include('footer.html');
?>
