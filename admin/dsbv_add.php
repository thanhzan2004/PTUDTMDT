<?php
include('database.php');
include 'sidebar.html';

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tua_de = $_POST['tua_de'];
    $noi_dung = $_POST['noi_dung'];
    $phan_loai = $_POST['phan_loai'];

    // Xử lý upload ảnh bài viết
    $anh_bai_viet = '';
    if (!empty($_FILES['anh_bai_viet']['name'])) {
        $target_dir = "../anh/";
        $target_file = $target_dir . basename($_FILES['anh_bai_viet']['name']);
        if (move_uploaded_file($_FILES['anh_bai_viet']['tmp_name'], $target_file)) {
            $anh_bai_viet = $target_file;
        } else {
            echo "<script>alert('Lỗi khi tải lên ảnh bài viết.');</script>";
        }
    }

    // Thêm bài viết vào cơ sở dữ liệu
    $sql = "INSERT INTO Bai_viet (Tua_de, Ngay_viet, Noi_dung, Anh_bai_viet, Phan_loai) VALUES (?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $tua_de, $noi_dung, $anh_bai_viet, $phan_loai);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm bài viết mới thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm bài viết: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài viết mới</title>
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
        input, textarea, select {
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
        <h1>Thêm bài viết mới</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="tua_de">Tiêu đề bài viết:</label>
            <input type="text" id="tua_de" name="tua_de" required>

            <label for="noi_dung">Nội dung bài viết:</label>
            <textarea id="noi_dung" name="noi_dung" required></textarea>

            <label for="anh_bai_viet">Ảnh bài viết:</label>
            <input type="file" id="anh_bai_viet" name="anh_bai_viet" accept="image/*">

            <label for="phan_loai">Phân loại bài viết:</label>
            <select id="phan_loai" name="phan_loai" required>
                <option value="tin_tuc">Tin tức</option>
                <option value="hoat_dong">Hoạt động</option>
                <option value="kieu_bai">Kiểu bài</option>
            </select>

            <button type="submit">Thêm bài viết</button>
        </form>
    </div>
</body>
</html>
