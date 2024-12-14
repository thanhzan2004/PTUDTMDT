<?php
include('database.php');
include 'sidebar.html';

// Kiểm tra xem ID sản phẩm có được gửi qua URL không
if (isset($_GET['id_sp'])) {
    $id_sp = $_GET['id_sp'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM San_pham WHERE ID_SP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_sp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ten_sp = $row['Ten_SP'];
        $gia_sp = $row['Gia_SP'];
        $anh_sp = $row['Anh_SP'];
        $mo_ta_sp = $row['Mo_ta_SP'];
        $cong_dung = $row['Cong_dung'];
        $hd_sd = $row['HD_SD'];
        $so_luong_ton_kho = $row['So_luong_ton_kho'];
        $id_danh_muc = $row['ID_Danh_muc'];
    } else {
        echo "<script>alert('Không tìm thấy sản phẩm.');</script>";
        exit;
    }
} else {
    echo "<script>alert('ID sản phẩm không hợp lệ.');</script>";
    exit;
}

// Lấy danh mục từ bảng danh_muc
$sql_danh_muc = "SELECT * FROM danh_muc";
$result_danh_muc = $conn->query($sql_danh_muc);

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_sp = $_POST['ten_sp'];
    $gia_sp = $_POST['gia_sp'];
    $mo_ta_sp = $_POST['mo_ta_sp'];
    $cong_dung = $_POST['cong_dung'];
    $hd_sd = $_POST['hd_sd'];
    $so_luong_ton_kho = $_POST['so_luong_ton_kho'];
    $id_danh_muc = $_POST['id_danh_muc'];

    // Xử lý upload ảnh sản phẩm mới (nếu có)
    $anh_sp_moi = $anh_sp;  // Giữ ảnh cũ nếu không có ảnh mới
    if (!empty($_FILES['anh_sp']['name'])) {
        $target_dir = "../anh/";
        $target_file = $target_dir . basename($_FILES['anh_sp']['name']);
        if (move_uploaded_file($_FILES['anh_sp']['tmp_name'], $target_file)) {
            $anh_sp_moi = $target_file;
        } else {
            echo "<script>alert('Lỗi khi tải lên ảnh sản phẩm.');</script>";
        }
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    $sql_update = "UPDATE San_pham SET Ten_SP = ?, Gia_SP = ?, Anh_SP = ?, Mo_ta_SP = ?, Cong_dung = ?, HD_SD = ?, So_luong_ton_kho = ?, ID_Danh_muc = ? WHERE ID_SP = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssssss", $ten_sp, $gia_sp, $anh_sp_moi, $mo_ta_sp, $cong_dung, $hd_sd, $so_luong_ton_kho, $id_danh_muc, $id_sp);

    if ($stmt_update->execute()) {
        echo "<script>alert('Cập nhật sản phẩm thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật sản phẩm: " . $stmt_update->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
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
        <h1>Chỉnh sửa sản phẩm</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="ten_sp">Tên sản phẩm:</label>
            <input type="text" id="ten_sp" name="ten_sp" value="<?php echo htmlspecialchars($ten_sp); ?>" required>

            <label for="gia_sp">Giá sản phẩm:</label>
            <input type="number" id="gia_sp" name="gia_sp" value="<?php echo htmlspecialchars($gia_sp); ?>" required>

            <label for="anh_sp">Ảnh sản phẩm (Nếu muốn thay đổi):</label>
            <input type="file" id="anh_sp" name="anh_sp" accept="image/*">
            <?php if ($anh_sp): ?>
                <p><img src="<?php echo $anh_sp; ?>" alt="Ảnh sản phẩm hiện tại" width="100"></p>
            <?php endif; ?>

            <label for="mo_ta_sp">Mô tả sản phẩm:</label>
            <textarea id="mo_ta_sp" name="mo_ta_sp" required><?php echo htmlspecialchars($mo_ta_sp); ?></textarea>

            <label for="cong_dung">Công dụng:</label>
            <textarea id="cong_dung" name="cong_dung" required><?php echo htmlspecialchars($cong_dung); ?></textarea>

            <label for="hd_sd">Hướng dẫn sử dụng:</label>
            <textarea id="hd_sd" name="hd_sd" required><?php echo htmlspecialchars($hd_sd); ?></textarea>

            <label for="so_luong_ton_kho">Số lượng tồn kho:</label>
            <input type="number" id="so_luong_ton_kho" name="so_luong_ton_kho" value="<?php echo htmlspecialchars($so_luong_ton_kho); ?>" required>

            <label for="id_danh_muc">Danh mục:</label>
            <select id="id_danh_muc" name="id_danh_muc" required>
                <?php while ($row_danh_muc = $result_danh_muc->fetch_assoc()): ?>
                    <option value="<?php echo $row_danh_muc['ID_Danh_muc']; ?>" <?php echo ($id_danh_muc == $row_danh_muc['ID_Danh_muc']) ? 'selected' : ''; ?>>
                        <?php echo $row_danh_muc['Ten_DM']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Cập nhật sản phẩm</button>
        </form>
    </div>
</body>
</html>
