-- Tạo cơ sở dữ liệu ecommerce
CREATE DATABASE IF NOT EXISTS PTUDTMDT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sử dụng cơ sở dữ liệu ecommerce
USE PTUDTMDT;

-- Bảng Khach_hang
CREATE TABLE Khach_hang (
    ID_KH VARCHAR(10) PRIMARY KEY,
    Ho_ten VARCHAR(50) NOT NULL,
    Ngay_sinh DATE NULL,
    Gioi_tinh VARCHAR(50) NULL,
    SDT VARCHAR(10) NOT NULL,
    Dia_chi VARCHAR(100) NULL,
    Anh_KH TEXT NULL, -- Chuyển từ VARCHAR(200) sang TEXT để linh hoạt hơn
    ID_Tai_khoan VARCHAR(10) NULL
);

-- Bảng Tai_khoan
CREATE TABLE Tai_khoan (
    ID_Tai_khoan VARCHAR(10) PRIMARY KEY,
    Email_TK VARCHAR(50) NOT NULL,
    Passwords VARCHAR(200) NOT NULL,
    Role VARCHAR(50) NOT NULL,
    Check_TK INT(11) NOT NULL,
    Trang_thai_TK INT(11) NOT NULL
);

-- Bảng San_pham
CREATE TABLE San_pham (
    ID_SP VARCHAR(10) PRIMARY KEY,
    Ten_SP VARCHAR(200) NOT NULL, -- Sửa từ Teb_SP
    Gia_SP FLOAT NOT NULL,
    Anh_SP TEXT NOT NULL, -- Chuyển từ VARCHAR(200) sang TEXT
    Anh_SP_1 TEXT NULL, -- Sửa lỗi Anh__SP_1
    Anh_SP_2 TEXT NULL,
    Anh_SP_3 TEXT NULL,
    Anh_SP_4 TEXT NULL,
    Mo_ta_SP TEXT NOT NULL,
    Cong_dung TEXT NOT NULL,
    HD_SD TEXT NOT NULL,
    So_luong_ton_kho INT(11) NOT NULL,
    ID_Danh_muc VARCHAR(10) NOT NULL,
    ID_Mau_sac VARCHAR(10) NOT NULL,
    ID_Kich_co VARCHAR(10) NOT NULL
);

-- Bảng Danh_muc
CREATE TABLE Danh_muc (
    ID_Danh_muc VARCHAR(10) PRIMARY KEY,
    Ten_DM VARCHAR(100) NOT NULL
);

-- Bảng Mau_sac
CREATE TABLE Mau_sac (
    ID_Mau_sac VARCHAR(10) PRIMARY KEY,
    Mau_sac VARCHAR(50) NULL
);

-- Bảng Kich_co
CREATE TABLE Kich_co (
    ID_Kich_co VARCHAR(10) PRIMARY KEY,
    Kich_co VARCHAR(100) NULL
);

-- Bảng Don_hang
CREATE TABLE Don_hang (
    ID_DH VARCHAR(10) PRIMARY KEY,
    Ngay_dat DATE NOT NULL,
    Trang_thai_thanh_toan VARCHAR(50) NOT NULL, -- Sửa khoảng trắng
    Phi_giao_hang FLOAT NOT NULL,
    Giam_gia FLOAT NOT NULL,
    Tong_tien FLOAT NOT NULL,
    Trang_thai_giao_hang VARCHAR(50) NOT NULL,
    ID_Thanh_toan VARCHAR(10) NOT NULL,
    ID_GH VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
);

-- Bảng Chi_tiet_don_hang
CREATE TABLE Chi_tiet_don_hang (
    ID_Chi_tiet_DH VARCHAR(10) PRIMARY KEY,
    So_luong_SP INT(11) NOT NULL,
    Kich_co VARCHAR(100) NOT NULL,
    Tong_tien FLOAT NOT NULL,
    Cach_thanh_toan VARCHAR(100) NOT NULL,
    Ten_SP VARCHAR(100) NOT NULL,
    Dia_chi VARCHAR(200) NOT NULL,
    SDT VARCHAR(10) NOT NULL,
    Email VARCHAR(100) NULL,
    Ghi_chu TEXT NULL,
    ID_SP VARCHAR(10) NOT NULL,
    ID_DH VARCHAR(10) NOT NULL
);

-- Bảng Giam_gia
CREATE TABLE Giam_gia (
    ID_Giam_gia VARCHAR(10) PRIMARY KEY,
    ID_Su_kien VARCHAR(10) NOT NULL,
    ID_SP VARCHAR(10) NOT NULL,
    Codes VARCHAR(100) NULL,
    Gia_giam FLOAT NOT NULL
);

-- Bảng Su_kien
CREATE TABLE Su_kien (
    ID_Su_kien VARCHAR(10) PRIMARY KEY,
    Ten_SK VARCHAR(200) NULL,
    Ngay_BD DATE NULL,
    Ngay_KT DATE NULL,
    Noi_dung_SK TEXT NULL,
    Anh_SK TEXT NULL -- Chuyển từ VARCHAR(200) sang TEXT
);

-- Bảng Lich_su_ban_hang
CREATE TABLE Lich_su_ban_hang (
    ID_Lich_su VARCHAR(10) PRIMARY KEY,
    ID_DH VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
);

-- Bảng Binh_luan
CREATE TABLE Binh_luan (
    ID_binh_luan VARCHAR(10) PRIMARY KEY,
    Binh_luan TEXT NULL,
    Ngay_BL DATE NULL,
    Star INT CHECK (Star BETWEEN 1 AND 5), -- Thêm ràng buộc kiểm tra
    ID_KH VARCHAR(10) NULL,
    ID_SP VARCHAR(10) NULL,
);

-- Bảng Gio_hang
CREATE TABLE Gio_hang (
    ID_Gio_hang VARCHAR(10) PRIMARY KEY,
    ID_SP VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL,
    So_luong INT NOT NULL
);

-- Bảng Danh_sach_yeu_thich
CREATE TABLE Danh_sach_yeu_thich (
    ID_DS_yeu_thich VARCHAR(10) PRIMARY KEY,
    ID_SP VARCHAR(10) NOT NULL,
    ID_KH VARCHAR(10) NOT NULL
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

-- Bảng Hinh_thuc_giao_hang
CREATE TABLE Hinh_thuc_giao_hang (
    ID_Giao_hang VARCHAR(10) PRIMARY KEY,
    Hinh_thuc_GH VARCHAR(100) NOT NULL,
    Mo_ta_GH TEXT NULL
);

-- Bảng Thanh_toan
CREATE TABLE Thanh_toan (
    ID_Thanh_toan VARCHAR(10) PRIMARY KEY,
    Phuong_thuc_thanh_toan VARCHAR(100) NOT NULL,
    Mo_ta_thanh_toan TEXT NULL
);

-- Bảng Trang
CREATE TABLE Trang (
    ID_Trang VARCHAR(10) PRIMARY KEY,
    Ten_trang VARCHAR(250) NOT NULL,
    Ngay_tao_trang DATE NULL,
    Ngay_update DATE NULL
);

-- Thêm khóa ngoại
ALTER TABLE Khach_hang ADD FOREIGN KEY (ID_Tai_khoan) REFERENCES Tai_khoan(ID_Tai_khoan);

ALTER TABLE San_pham 
    ADD FOREIGN KEY (ID_Danh_muc) REFERENCES Danh_muc(ID_Danh_muc),
    ADD FOREIGN KEY (ID_Mau_sac) REFERENCES Mau_sac(ID_Mau_sac),
    ADD FOREIGN KEY (ID_Kich_co) REFERENCES Kich_co(ID_Kich_co);

ALTER TABLE Don_hang 
    ADD FOREIGN KEY (ID_Thanh_toan) REFERENCES Thanh_toan(ID_Thanh_toan),
    ADD FOREIGN KEY (ID_GH) REFERENCES Hinh_thuc_giao_hang(ID_Giao_hang),
    ADD FOREIGN KEY (ID_KH) REFERENCES Khach_hang(ID_KH);

ALTER TABLE Chi_tiet_don_hang 
    ADD FOREIGN KEY (ID_SP) REFERENCES San_pham(ID_SP),
    ADD FOREIGN KEY (ID_DH) REFERENCES Don_hang(ID_DH);

ALTER TABLE Lich_su_ban_hang 
    ADD FOREIGN KEY (ID_DH) REFERENCES Don_hang(ID_DH),
    ADD FOREIGN KEY (ID_KH) REFERENCES Khach_hang(ID_KH);

ALTER TABLE Binh_luan 
    ADD FOREIGN KEY (ID_KH) REFERENCES Khach_hang(ID_KH),
    ADD FOREIGN KEY (ID_SP) REFERENCES San_pham(ID_SP);

ALTER TABLE Gio_hang 
    ADD FOREIGN KEY (ID_SP) REFERENCES San_pham(ID_SP),
    ADD FOREIGN KEY (ID_KH) REFERENCES Khach_hang(ID_KH);

ALTER TABLE Danh_sach_yeu_thich 
    ADD FOREIGN KEY (ID_SP) REFERENCES San_pham(ID_SP),
    ADD FOREIGN KEY (ID_KH) REFERENCES Khach_hang(ID_KH);

ALTER TABLE Giam_gia 
    ADD FOREIGN KEY (ID_Su_kien) REFERENCES Su_kien(ID_Su_kien),
    ADD FOREIGN KEY (ID_SP) REFERENCES San_pham(ID_SP);
