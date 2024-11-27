

// Thêm sinh viên mới
if (isset($_POST['add_student'])) {
    $id = $_POST['id']; 
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "INSERT INTO students (id, name, email, age) VALUES ('$id', '$name', '$email', '$age')";
    if ($conn->query($sql) === TRUE) {
        echo "Thêm sinh viên thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Cập nhật thông tin sinh viên
if (isset($_POST['update_student'])) {
    $id = $_POST['id'];  // Đảm bảo id được lấy từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "UPDATE students SET name='$name', email='$email', age='$age' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Xóa sinh viên
if (isset($_GET['delete_student'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM students WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Lấy thông tin sinh viên để cập nhật
$student_to_update = null;
if (isset($_GET['edit_student'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $student_to_update = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý danh sách sinh viên</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        a{
            text-decoration: none; 
            color: #000;
        }
        
    </style>
</head>
<body>
    <h2>Danh sách sinh viên</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Tuổi</th>
            <th>Hành động</th>
        </tr>
        <?php
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['age']}</td>
                        <td>
                            <a href='?edit_student=1&id={$row['id']}'>Chỉnh sửa</a> |
                            <a href='?delete_student=1&id={$row['id']}'>Xóa</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Không có sinh viên nào</td></tr>";
        }
        ?>
    </table>

    <h2>Thêm sinh viên mới</h2>
    <form method="post" action="">
        <input type="number" name="id" placeholder="ID" required><br>
        <input type="text" name="name" placeholder="Tên" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="number" name="age" placeholder="Tuổi" required><br>
        <button type="submit" name="add_student">Thêm sinh viên</button>
    </form>

    <?php if ($student_to_update): ?>
        <h2>Cập nhật thông tin sinh viên</h2>
        <form method="post" action="">
            <input type="number" name="id" value="<?php echo $student_to_update['id']; ?>"placeholder="ID" required><br>
            <input type="text" name="name" value="<?php echo $student_to_update['name']; ?>" placeholder="Tên" required><br>
            <input type="email" name="email" value="<?php echo $student_to_update['email']; ?>" placeholder="Email" required><br>
            <input type="number" name="age" value="<?php echo $student_to_update['age']; ?>" placeholder="Tuổi" required><br>
            <button type="submit" name="update_student">Cập nhật sinh viên</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>