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

// Xử lý khi người dùng gửi số điện thoại
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra xem ID_DH có được gửi hay không
    if (isset($_POST['ID_DH']) && !empty($_POST['ID_DH'])) {
        $ID_DH = $_POST['ID_DH'];

        // Truy vấn dữ liệu từ bảng don_hang
        $stmt = $conn->prepare("SELECT * FROM don_hang WHERE ID_DH = ?");
        $stmt->bind_param('s', $ID_DH);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>KẾT QUẢ TRA CỨU ĐƠN HÀNG</h2>";
            echo "<table class='result-table'>
                    <tr>
                        <th>ID Đơn Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái Thanh Toán</th>
                        <th>Phí Giao Hàng</th>
                        <th>Giảm Giá</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái Giao Hàng</th>
                        <th>ID Thanh Toán</th>
                        
                    </tr>";
            
            // Hiển thị dữ liệu từ bảng don_hang
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ID_DH']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Ngay_dat']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Trang_thai_thanh_toan']) . "</td>";
                echo "<td>" . number_format($row['Phi_giao_hang'], 0, ',', '.') . " VND</td>";
                echo "<td>" . number_format($row['Giam_gia'], 0, ',', '.') . " VND</td>";
                echo "<td>" . number_format($row['Tong_tien'], 0, ',', '.') . " VND</td>";
                echo "<td>" . htmlspecialchars($row['Trang_thai_giao_hang']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ID_Thanh_toan']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Không tìm thấy đơn hàng nào với ID đơn hàng này.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Vui lòng nhập ID đơn hàng để tra cứu.</p>";
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
        button {
            width: 100%;
            padding: 10px;
            background-color: #e6ac00;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>TRA CỨU ĐƠN HÀNG</h1>
        <form method="POST" action="">
            <label for="ID_DH">Nhập ID_DH</label>
            <input type="text" id="ID_DH" name="ID_DH" placeholder="Nhập ID_DH" required>
            <button type="submit">Tra cứu</button>
        </form>
    </div>
</body>
</html>
