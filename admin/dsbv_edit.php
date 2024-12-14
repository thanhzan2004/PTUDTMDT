<?php
include('database.php');
include 'sidebar.html';

// Kiểm tra xem ID bài viết có được gửi qua URL không
if (isset($_GET['id_bai_viet'])) {
    $id_bai_viet = $_GET['id_bai_viet'];

    // Lấy thông tin bài viết từ cơ sở dữ liệu
    $sql = "SELECT * FROM Bai_viet WHERE ID_Bai_viet = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_bai_viet);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tua_de = $row['Tua_de'];
        $noi_dung = $row['Noi_dung'];
        $anh_bai_viet = $row['Anh_bai_viet'];
        $phan_loai = $row['Phan_loai'];
    } else {
        echo "<script>alert('Không tìm thấy bài viết.');</script>";
        exit;
    }
} else {
    echo "<script>alert('ID bài viết không hợp lệ.');</script>";
    exit;
}

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tua_de = $_POST['tua_de'];
    $noi_dung = $_POST['noi_dung'];
    $phan_loai = $_POST['phan_loai'];

    // Xử lý upload ảnh bài viết mới (nếu có)
    $anh_bai_viet_moi = $anh_bai_viet;  // Giữ ảnh cũ nếu không có ảnh mới
    if (!empty($_FILES['anh_bai_viet']['name'])) {
        $target_dir = "../anh/";
        $target_file = $target_dir . basename($_FILES['anh_bai_viet']['name']);
        if (move_uploaded_file($_FILES['anh_bai_viet']['tmp_name'], $target_file)) {
            $anh_bai_viet_moi = $target_file;
        } else {
            echo "<script>alert('Lỗi khi tải lên ảnh bài viết.');</script>";
        }
    }

    // Cập nhật bài viết vào cơ sở dữ liệu
    $sql_update = "UPDATE Bai_viet SET Tua_de = ?, Noi_dung = ?, Anh_bai_viet = ?, Phan_loai = ? WHERE ID_Bai_viet = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssss", $tua_de, $noi_dung, $anh_bai_viet_moi, $phan_loai, $id_bai_viet);

    if ($stmt_update->execute()) {
        echo "<script>alert('Cập nhật bài viết thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật bài viết: " . $stmt_update->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bài viết</title>
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
        <h1>Chỉnh sửa bài viết</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="tua_de">Tiêu đề bài viết:</label>
            <input type="text" id="tua_de" name="tua_de" value="<?php echo htmlspecialchars($tua_de); ?>" required>

            <label for="noi_dung">Nội dung bài viết:</label>
            <textarea id="noi_dung" name="noi_dung" required><?php echo htmlspecialchars($noi_dung); ?></textarea>

            <label for="anh_bai_viet">Ảnh bài viết (Nếu muốn thay đổi):</label>
            <input type="file" id="anh_bai_viet" name="anh_bai_viet" accept="image/*">
            <?php if ($anh_bai_viet): ?>
                <p><img src="<?php echo $anh_bai_viet; ?>" alt="Ảnh bài viết hiện tại" width="100"></p>
            <?php endif; ?>

            <label for="phan_loai">Phân loại bài viết:</label>
            <select id="phan_loai" name="phan_loai" required>
                <option value="tin_tuc" <?php echo ($phan_loai == 'tin_tuc') ? 'selected' : ''; ?>>Tin tức</option>
                <option value="hoat_dong" <?php echo ($phan_loai == 'hoat_dong') ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="kieu_bai" <?php echo ($phan_loai == 'kieu_bai') ? 'selected' : ''; ?>>Kiểu bài</option>
            </select>

            <button type="submit">Cập nhật bài viết</button>
        </form>
    </div>
</body>
</html>
