-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS PTUDTMDT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng cơ sở dữ liệu
USE PTUDTMDT;

-- Bảng San_pham
CREATE TABLE San_pham (
    ID_SP VARCHAR(10) PRIMARY KEY,
    Ten_SP VARCHAR(200) NOT NULL,
    Gia_SP FLOAT NOT NULL,
    Anh_SP TEXT NOT NULL,
    Mo_ta_SP TEXT NOT NULL,
    Cong_dung TEXT NOT NULL,
    HD_SD TEXT NOT NULL,
    So_luong_ton_kho INT(11) NOT NULL,
    ID_Danh_muc VARCHAR(10) NOT NULL,
);

-- Bảng Danh_muc
CREATE TABLE Danh_muc (
    ID_Danh_muc VARCHAR(10) PRIMARY KEY,
    Ten_DM VARCHAR(100) NOT NULL
);

-- Bảng 
CREATE TABLE don_hang_chi_tiet (
    ID_DH VARCHAR(10) NOT NULL, -- Mã đơn hàng
    ID_SP VARCHAR(10) NOT NULL, -- Mã sản phẩm
    Ten_SP VARCHAR(100) NOT NULL, -- Tên sản phẩm
    So_luong_SP INT NOT NULL, -- Số lượng sản phẩm
    Gia_SP FLOAT NOT NULL, -- Giá sản phẩm
    Cach_thanh_toan VARCHAR(100) NOT NULL, -- Phương thức thanh toán
    Dia_chi VARCHAR(200) NOT NULL, -- Địa chỉ giao hàng
    Ngay_dat DATE NOT NULL, -- Ngày đặt hàng
    Trang_thai_thanh_toan VARCHAR(50) NOT NULL, -- Trạng thái thanh toán
    Trang_thai_giao_hang VARCHAR(50) NOT NULL, -- Trạng thái giao hàng
    Tong_tien FLOAT NOT NULL, -- Tổng tiền (của toàn bộ đơn hàng)
    SDT VARCHAR(10) NOT NULL, -- Số điện thoại
    Ten_khach_hang VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NULL, -- Email khách hàng
    Ghi_chu TEXT NULL, -- Ghi chú

    PRIMARY KEY (ID_DH, ID_SP) -- Khóa chính kết hợp
);

-- Bảng Bai_viet
CREATE TABLE Bai_viet (
    ID_Bai_viet VARCHAR(10) PRIMARY KEY,
    Tua_de VARCHAR(250) NULL,
    Ngay_viet DATE NULL,
    Noi_dung TEXT NULL,
    Anh_bai_viet TEXT NULL,
    Phan_loai VARCHAR(50) NULL
);

ALTER TABLE Bai_viet
MODIFY COLUMN ID_Bai_viet INT AUTO_INCREMENT;