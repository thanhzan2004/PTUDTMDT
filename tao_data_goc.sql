-- Tạo cơ sở dữ liệu ecommerce
CREATE DATABASE IF NOT EXISTS PTUDTMDT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng cơ sở dữ liệu ecommerce
USE PTUDTMDT;

-- Tạo bảng Khách hàng
CREATE TABLE Khach_hang (
    ID_KH VARCHAR(10) NOT NULL PRIMARY KEY,
    Ho_ten VARCHAR(50) NOT NULL,
    Ngay_sinh DATE,
    Gioi_tinh VARCHAR(50),
    SDT INT(10) NOT NULL,
    Dia_chi VARCHAR(100),
    Anh_KH VARCHAR(200),
    ID_Tai_khoan VARCHAR(10)
);

-- Tạo bảng Tài khoản
CREATE TABLE Tai_khoan (
    ID_Tai_khoan VARCHAR(10) NOT NULL PRIMARY KEY,
    Email_TK VARCHAR(50) NOT NULL,
    Passwords VARCHAR(200) NOT NULL,
    Role VARCHAR(50) NOT NULL,
    Check_TK INT(11) NOT NULL,
    Trang_thai_TK INT(11) NOT NULL
);

-- Tạo bảng Sản phẩm
CREATE TABLE San_pham (
    ID_SP VARCHAR(10) NOT NULL PRIMARY KEY,
    Teb_SP VARCHAR(200) NOT NULL,
    Gia_SP FLOAT NOT NULL,
    Anh_SP VARCHAR(200) NOT NULL,
    Anh_SP_1 TEXT,
    Anh_SP_2 TEXT,
    Anh_SP_3 TEXT,
    Anh_SP_4 TEXT,
    Mo_ta_SP TEXT NOT NULL,
    Cong_dung TEXT NOT NULL,
    HD_SD TEXT NOT NULL,
    So_luong_ton_kho INT(11) NOT NULL,
    ID_Danh_muc VARCHAR(10) NOT NULL,
    ID_Mau_sac VARCHAR(10) NOT NULL,
    ID_Kich_co VARCHAR(10) NOT NULL
);

-- Tạo bảng Danh mục
CREATE TABLE Danh_muc (
    ID_Danh_muc VARCHAR(10) NOT NULL PRIMARY KEY,
    Ten_DM VARCHAR(100) NOT NULL
);

-- Tạo bảng Kích cỡ
CREATE TABLE Kich_co (
    ID_Kich_co VARCHAR(10) NOT NULL PRIMARY KEY,
    Kich_co VARCHAR(100)
);

-- Tạo bảng Màu sắc
CREATE TABLE Mau_sac (
    ID_Mau_sac VARCHAR(10) NOT NULL PRIMARY KEY,
    Mau_sac VARCHAR(50)
);

-- Tạo bảng Đơn hàng
CREATE TABLE Don_hang (
    ID_DH VARCHAR(10) NOT NULL PRIMARY KEY,
    Ngay_dat DATE NOT NULL,
    Trang_thai_thanh_toan VARCHAR(50) NOT NULL,
    Phi_giao_hang FLOAT NOT NULL,
    Giam_gia DOUBLE NOT NULL,
    Tong_tien FLOAT NOT NULL,
    Trang_thai_giao_hang VARCHAR(50) NOT NULL,
    ID_Thanh_toan VARCHAR(10) NOT NULL,
    ID_GH VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
);

-- Tạo bảng Chi tiết đơn hàng
CREATE TABLE Chi_tiet_don_hang (
    ID_Chi_tiet_DH VARCHAR(10) NOT NULL PRIMARY KEY,
    So_luong_SP INT(11) NOT NULL,
    Kich_co VARCHAR(100) NOT NULL,
    Tong_tien FLOAT NOT NULL,
    Cach_thanh_toan VARCHAR(100) NOT NULL,
    Ten_SP VARCHAR(100) NOT NULL,
    Dia_chi VARCHAR(200) NOT NULL,
    SDT INT(10) NOT NULL,
    Email VARCHAR(100),
    Ghi_chu TEXT,
    ID_SP VARCHAR(10) NOT NULL,
    ID_DH VARCHAR(10) NOT NULL
);

-- Tạo bảng Hình thức giao hàng
CREATE TABLE Hinh_thuc_giao_hang (
    ID_Giao_hang VARCHAR(10) NOT NULL PRIMARY KEY,
    Hinh_thuc_GH VARCHAR(100) NOT NULL,
    Mo_ta_GH TEXT
);

-- Tạo bảng Bình luận
CREATE TABLE Binh_luan (
    ID_Binh_luan VARCHAR(10) NOT NULL PRIMARY KEY,
    Binh_luan TEXT,
    Ngay_BL TEXT,
    Star INT,
    ID_KH VARCHAR(10),
    ID_SP VARCHAR(10)
);

-- Tạo bảng Giỏ hàng
CREATE TABLE Gio_hang (
    ID_Gio_hang VARCHAR(10) NOT NULL PRIMARY KEY,
    ID_SP VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL,
    So_luong INT NOT NULL
);

-- Tạo bảng Bài viết
CREATE TABLE Bai_viet (
    ID_Bai_viet VARCHAR(10) NOT NULL PRIMARY KEY,
    Tua_de VARCHAR(250),
    Ngay_viet DATE,
    Noi_dung TEXT,
    Anh_bai_viet TEXT,
    Phan_loai VARCHAR(50)
);

-- Tạo bảng Danh sách yêu thích
CREATE TABLE Danh_sach_yeu_thich (
    ID_DS_yeu_thich VARCHAR(10) NOT NULL PRIMARY KEY,
    ID_SP VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
);

-- Tạo bảng Lịch sử bán hàng
CREATE TABLE Lich_su_ban_hang (
    ID_Lich_su VARCHAR(10) NOT NULL PRIMARY KEY,
    ID_DH VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
);

-- Tạo bảng Giảm giá
CREATE TABLE Giam_gia (
    ID_Giam_gia VARCHAR(10) NOT NULL PRIMARY KEY,
    ID_Su_kien VARCHAR(10) NOT NULL,
    ID_SP VARCHAR(10) NOT NULL,
    Codes VARCHAR(100),
    Gia_giam INT(11) NOT NULL
);

-- Tạo bảng Sự kiện
CREATE TABLE Su_kien (
    ID_Su_kien VARCHAR(10) NOT NULL PRIMARY KEY,
    Ten_SK VARCHAR(200),
    Ngay_BD TEXT,
    Ngay_KT TEXT,
    Noi_dung_SK TEXT,
    Anh_SK VARCHAR(200)
);

-- Tạo bảng Thanh toán
CREATE TABLE Thanh_toan (
    ID_Thanh_toan VARCHAR(10) NOT NULL PRIMARY KEY,
    Phuong_thuc_thanh_toan VARCHAR(100) NOT NULL,
    Mo_ta_thanh_toan TEXT
);
