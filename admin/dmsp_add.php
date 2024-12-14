<?php
include('database.php');
include('sidebar.html');

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_dm = $_POST['ten_dm'];

    // Thêm danh mục vào cơ sở dữ liệu
    $sql = "INSERT INTO Danh_muc (ID_Danh_muc, Ten_DM) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Tạo ID dành cho danh mục mới
    $id_danh_muc = uniqid('DM');
    $stmt->bind_param("ss", $id_danh_muc, $ten_dm);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm danh mục mới thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm danh mục: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục sản phẩm</title>
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
        <h1>Thêm danh mục sản phẩm</h1>
        <form method="POST">
            <label for="ten_dm">Tên danh mục:</label>
            <input type="text" id="ten_dm" name="ten_dm" required>

            <button type="submit">Thêm danh mục</button>
        </form>
    </div>
</body>
</html>
