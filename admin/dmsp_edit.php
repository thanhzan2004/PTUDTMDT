<?php
include('database.php');
include 'sidebar.html';

// Kiểm tra xem ID danh mục có được gửi qua URL không
if (isset($_GET['id_danh_muc'])) {
    $id_danh_muc = $_GET['id_danh_muc'];

    // Lấy thông tin danh mục từ cơ sở dữ liệu
    $sql = "SELECT * FROM Danh_muc WHERE ID_Danh_muc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_danh_muc);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ten_dm = $row['Ten_DM'];
    } else {
        echo "<script>alert('Không tìm thấy danh mục.');</script>";
        exit;
    }
} else {
    echo "<script>alert('ID danh mục không hợp lệ.');</script>";
    exit;
}

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_dm = $_POST['ten_dm'];

    // Cập nhật danh mục vào cơ sở dữ liệu
    $sql_update = "UPDATE Danh_muc SET Ten_DM = ? WHERE ID_Danh_muc = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $ten_dm, $id_danh_muc);

    if ($stmt_update->execute()) {
        echo "<script>alert('Cập nhật danh mục thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật danh mục: " . $stmt_update->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa danh mục</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8e1;
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-left: 300px; /* Để nội dung tránh bị sidebar che khuất */
        }
        h1 {
            text-align: center;
            color: #ffb300;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #ffb300;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ffa000;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Chỉnh sửa danh mục</h1>
        <form method="POST">
            <label for="ten_dm">Tên danh mục:</label>
            <input type="text" id="ten_dm" name="ten_dm" value="<?php echo htmlspecialchars($ten_dm); ?>" required>

            <button type="submit">Cập nhật danh mục</button>
        </form>
    </div>
</body>
</html>
