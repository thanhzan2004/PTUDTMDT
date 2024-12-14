<?php
include('database.php');
include 'sidebar.html';

// Lấy danh mục từ bảng danh_muc
$sql_danh_muc = "SELECT * FROM danh_muc";
$result_danh_muc = $conn->query($sql_danh_muc);

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tạo ID_SP tự động
    $id_sp = "SP" . uniqid();

    $ten_sp = $_POST['ten_sp'];
    $gia_sp = $_POST['gia_sp'];
    $mo_ta_sp = $_POST['mo_ta_sp'];
    $cong_dung = $_POST['cong_dung'];
    $hd_sd = $_POST['hd_sd'];
    $so_luong_ton_kho = $_POST['so_luong_ton_kho'];
    $id_danh_muc = $_POST['id_danh_muc'];

    // Xử lý upload ảnh sản phẩm
    $anh_sp = "";
    if (!empty($_FILES['anh_sp']['name'])) {
        $target_dir = "../anh/";
        $target_file = $target_dir . basename($_FILES['anh_sp']['name']);
        if (move_uploaded_file($_FILES['anh_sp']['tmp_name'], $target_file)) {
            $anh_sp = $target_file;
        } else {
            echo "<script>alert('Lỗi khi tải lên ảnh sản phẩm.');</script>";
        }
    }

    $sql_insert = "INSERT INTO San_pham (ID_SP, Ten_SP, Gia_SP, Anh_SP, Mo_ta_SP, Cong_dung, HD_SD, So_luong_ton_kho, ID_Danh_muc) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssssssis", $id_sp, $ten_sp, $gia_sp, $anh_sp, $mo_ta_sp, $cong_dung, $hd_sd, $so_luong_ton_kho, $id_danh_muc);


    if ($stmt_insert->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công! ID: $id_sp');</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm sản phẩm: " . $stmt_insert->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới</title>
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
            margin-left: 300px;
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
        <h1>Thêm sản phẩm mới</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="ten_sp">Tên sản phẩm:</label>
            <input type="text" id="ten_sp" name="ten_sp" required>

            <label for="gia_sp">Giá sản phẩm:</label>
            <input type="number" id="gia_sp" name="gia_sp" required>

            <label for="anh_sp">Ảnh sản phẩm:</label>
            <input type="file" id="anh_sp" name="anh_sp" accept="image/*" required>

            <label for="mo_ta_sp">Mô tả sản phẩm:</label>
            <textarea id="mo_ta_sp" name="mo_ta_sp" required></textarea>

            <label for="cong_dung">Công dụng:</label>
            <textarea id="cong_dung" name="cong_dung" required></textarea>

            <label for="hd_sd">Hướng dẫn sử dụng:</label>
            <textarea id="hd_sd" name="hd_sd" required></textarea>

            <label for="so_luong_ton_kho">Số lượng tồn kho:</label>
            <input type="number" id="so_luong_ton_kho" name="so_luong_ton_kho" required>

            <label for="id_danh_muc">Danh mục:</label>
            <select id="id_danh_muc" name="id_danh_muc" required>
                <?php while ($row_danh_muc = $result_danh_muc->fetch_assoc()): ?>
                    <option value="<?php echo $row_danh_muc['ID_Danh_muc']; ?>">
                        <?php echo $row_danh_muc['Ten_DM']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Thêm sản phẩm</button>
        </form>
    </div>
</body>
</html>
