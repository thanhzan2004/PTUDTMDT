<?php
include('database.php');

// Lấy danh sách tài khoản
$sql = "SELECT ID_Tai_khoan, Email_TK, Role FROM Tai_khoan";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $id_tai_khoan = $_POST['id_tai_khoan'];
    $new_role = $_POST['role'];

    // Cập nhật vai trò người dùng
    $update_sql = "UPDATE Tai_khoan SET Role='$new_role' WHERE ID_Tai_khoan='$id_tai_khoan'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Bạn đã cập nhật thành công!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài Khoản</title>
    <link rel="stylesheet" href="style_account.css"> <!-- Liên kết đến file CSS -->
</head>
<body>
    <h1>Quản lý Tài Khoản</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID Tài Khoản</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Thay đổi vai trò</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hiển thị danh sách tài khoản
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_Tai_khoan"] . "</td>";
                    echo "<td>" . $row["Email_TK"] . "</td>";
                    echo "<td>" . $row["Role"] . "</td>";
                    echo "<td>
                            <form method='POST'>
                                <input type='hidden' name='id_tai_khoan' value='" . $row["ID_Tai_khoan"] . "'>
                                <select name='role'>
                                    <option value='user' " . ($row["Role"] == 'user' ? 'selected' : '') . ">User</option>
                                    <option value='admin' " . ($row["Role"] == 'admin' ? 'selected' : '') . ">Admin</option>
                                </select>
                                <button type='submit' name='update_role'>Cập nhật</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Không có tài khoản nào</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
