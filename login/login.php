<?php
// Kết nối cơ sở dữ liệu
include('database.php'); // Đảm bảo rằng file 'database.php' đã chứa mã kết nối đúng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // Để phân biệt đăng nhập và đăng ký
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Đăng nhập
    if ($action == 'login') {
        // Kiểm tra nếu email đã tồn tại trong cơ sở dữ liệu
        $sql = "SELECT * FROM Tai_khoan WHERE Email_TK = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Passwords'])) {
                // Đăng nhập thành công
                echo json_encode([
                    "status" => "success", 
                    "message" => "Đăng nhập thành công", 
                    "redirect" => "customer_info.php" // Chuyển hướng đến trang thông tin khách hàng
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Sai mật khẩu. Vui lòng thử lại."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Tài khoản chưa tồn tại. Vui lòng đăng ký.", 
                "redirect" => "show_register_tab" // Chuyển hướng đến tab đăng ký nếu tài khoản không tồn tại
            ]);
        }

        $stmt->close();
    }

    // Đăng ký
    elseif ($action == 'register') {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu trước khi lưu

        // Lưu thông tin vào bảng Tai_khoan
        $sql = "INSERT INTO Tai_khoan (Email_TK, Passwords, Role, Check_TK, Trang_thai_TK) VALUES (?, ?, 'user', 1, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password_hashed);

        if ($stmt->execute()) {
            // Lấy ID của tài khoản mới
            $user_id = $stmt->insert_id;

            // Thêm thông tin vào bảng Khach_hang
            $fullName = $_POST['first_name'] . ' ' . $_POST['last_name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $gender = $_POST['gender'];

            $sql_kh = "INSERT INTO Khach_hang (Ho_ten, SDT, Dia_chi, ID_Tai_khoan) VALUES (?, ?, ?, ?)";
            $stmt_kh = $conn->prepare($sql_kh);
            $stmt_kh->bind_param("ssss", $fullName, $phone, $address, $user_id);

            if ($stmt_kh->execute()) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "Đăng ký thành công, vui lòng đăng nhập."
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Đã có lỗi xảy ra khi lưu thông tin khách hàng."
                ]);
            }

            $stmt_kh->close();
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Đã có lỗi xảy ra khi đăng ký."
            ]);
        }

        $stmt->close();
    }

    // Quên mật khẩu
    elseif ($action == 'forgot_password') {
        // Quên mật khẩu
        $sql = "SELECT * FROM Tai_khoan WHERE Email_TK = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                "status" => "success", 
                "message" => "Đã gửi mật khẩu mới qua email."
            ]);
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Email không tồn tại trong hệ thống."
            ]);
        }

        $stmt->close();
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
}
?>
