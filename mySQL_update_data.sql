-- Thêm dữ liệu vào bảng Tai_khoan

INSERT INTO Tai_khoan (ID_Tai_khoan, Email_TK, Passwords, Role, Check_TK, Trang_thai_TK)
VALUES 
('TK001', 'user1@example.com', 'password1', 'user', 1, 1),
('TK002', 'user2@example.com', 'password2', 'user', 1, 1),
('TK003', 'user3@example.com', 'password3', 'admin', 1, 1),
('TK004', 'user4@example.com', 'password4', 'user', 1, 1),
('TK005', 'user5@example.com', 'password5', 'admin', 1, 1),
('TK006', 'user6@example.com', 'password6', 'user', 1, 1),
('TK007', 'user7@example.com', 'password7', 'user', 1, 1),
('TK008', 'user8@example.com', 'password8', 'user', 1, 1),
('TK009', 'user9@example.com', 'password9', 'user', 1, 1),
('TK010', 'user10@example.com', 'password10', 'user', 1, 1);

-- Thêm dữ liệu vào bảng Khach_hang
INSERT INTO Khach_hang (ID_KH, Ho_ten, Ngay_sinh, Gioi_tinh, SDT, Dia_chi, Anh_KH, ID_Tai_khoan)
VALUES 
('KH001', 'Nguyen Van A', '1990-01-01', 'Nam', '0123456789', 'Hanoi', NULL, 'TK001'),
('KH002', 'Tran Thi B', '1992-02-02', 'Nu', '0123456790', 'Saigon', NULL, 'TK002'),
('KH003', 'Le Van C', '1994-03-03', 'Nam', '0123456791', 'Danang', NULL, 'TK003'),
('KH004', 'Pham Thi D', '1996-04-04', 'Nu', '0123456792', 'Hue', NULL, 'TK004'),
('KH005', 'Hoang Van E', '1988-05-05', 'Nam', '0123456793', 'Hai Phong', NULL, 'TK005'),
('KH006', 'Do Thi F', '1999-06-06', 'Nu', '0123456794', 'Quang Ninh', NULL, 'TK006'),
('KH007', 'Ngo Van G', '1991-07-07', 'Nam', '0123456795', 'Vinh', NULL, 'TK007'),
('KH008', 'Vu Thi H', '1993-08-08', 'Nu', '0123456796', 'Nha Trang', NULL, 'TK008'),
('KH009', 'Ly Van I', '1995-09-09', 'Nam', '0123456797', 'Phu Quoc', NULL, 'TK009'),
('KH010', 'Mai Thi J', '1987-10-10', 'Nu', '0123456798', 'Can Tho', NULL, 'TK010');

-- Thêm dữ liệu vào bảng Danh_muc
INSERT INTO Danh_muc (ID_Danh_muc, Ten_DM)
VALUES 
('DM001', 'Thời trang nam'),
('DM002', 'Thời trang nữ'),
('DM003', 'Phụ kiện thời trang'),
('DM004', 'Giày dép'),
('DM005', 'Túi xách'),
('DM006', 'Trang sức'),
('DM007', 'Mỹ phẩm'),
('DM008', 'Đồ gia dụng'),
('DM009', 'Sản phẩm điện tử'),
('DM010', 'Khác');

-- Thêm dữ liệu vào bảng Mau_sac
INSERT INTO Mau_sac (ID_Mau_sac, Mau_sac)
VALUES 
('MS001', 'Đỏ'),
('MS002', 'Xanh'),
('MS003', 'Vàng'),
('MS004', 'Đen'),
('MS005', 'Trắng'),
('MS006', 'Hồng'),
('MS007', 'Tím'),
('MS008', 'Cam'),
('MS009', 'Xám'),
('MS010', 'Nâu');

-- Thêm dữ liệu vào bảng Kich_co
INSERT INTO Kich_co (ID_Kich_co, Kich_co)
VALUES 
('KC001', 'S'),
('KC002', 'M'),
('KC003', 'L'),
('KC004', 'XL'),
('KC005', 'XXL'),
('KC006', '30'),
('KC007', '32'),
('KC008', '34'),
('KC009', '36'),
('KC010', 'Free Size');

-- Thêm dữ liệu vào bảng San_pham
INSERT INTO San_pham (ID_SP, Ten_SP, Gia_SP, Anh_SP, Anh_SP_1, Anh_SP_2, Anh_SP_3, Anh_SP_4, Mo_ta_SP, Cong_dung, HD_SD, So_luong_ton_kho, ID_Danh_muc, ID_Mau_sac, ID_Kich_co)
VALUES 
('SP001', 'San Pham 1', 100000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 1', 'Cong dung SP 1', 'Huong dan SP 1', 100, 'DM001', 'MS001', 'KC001'),
('SP002', 'San Pham 2', 200000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 2', 'Cong dung SP 2', 'Huong dan SP 2', 200, 'DM002', 'MS002', 'KC002'),
('SP003', 'San Pham 3', 300000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 3', 'Cong dung SP 3', 'Huong dan SP 3', 150, 'DM001', 'MS003', 'KC003'),
('SP004', 'San Pham 4', 400000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 4', 'Cong dung SP 4', 'Huong dan SP 4', 250, 'DM003', 'MS004', 'KC004'),
('SP005', 'San Pham 5', 500000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 5', 'Cong dung SP 5', 'Huong dan SP 5', 50, 'DM002', 'MS005', 'KC005'),
('SP006', 'San Pham 6', 600000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 6', 'Cong dung SP 6', 'Huong dan SP 6', 60, 'DM003', 'MS001', 'KC006'),
('SP007', 'San Pham 7', 700000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 7', 'Cong dung SP 7', 'Huong dan SP 7', 70, 'DM001', 'MS002', 'KC007'),
('SP008', 'San Pham 8', 800000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 8', 'Cong dung SP 8', 'Huong dan SP 8', 80, 'DM003', 'MS003', 'KC008'),
('SP009', 'San Pham 9', 900000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 9', 'Cong dung SP 9', 'Huong dan SP 9', 90, 'DM002', 'MS004', 'KC009'),
('SP010', 'San Pham 10', 1000000, NULL, NULL, NULL, NULL, NULL, 'Mo ta SP 10', 'Cong dung SP 10', 'Huong dan SP 10', 10, 'DM001', 'MS005', 'KC010');

-- Thêm dữ liệu vào bảng Thanh_toan
INSERT INTO Thanh_toan (ID_Thanh_toan, Phuong_thuc_thanh_toan, Mo_ta_thanh_toan)
VALUES 
('TT001', 'Thanh toán online', 'Sử dụng thẻ tín dụng hoặc chuyển khoản.'),
('TT002', 'Thanh toán COD', 'Thanh toán khi nhận hàng.'),
('TT003', 'Thanh toán qua ví điện tử', 'Hỗ trợ MoMo, ZaloPay, VNPay.'),
('TT004', 'Thanh toán bằng QR code', 'Quét mã QR để thanh toán.'),
('TT005', 'Trả góp 0%', 'Áp dụng với đơn hàng từ 3 triệu đồng.'),
('TT006', 'Thanh toán qua PayPal', 'Dành cho khách hàng quốc tế.'),
('TT007', 'Thanh toán qua ATM', 'Hỗ trợ các ngân hàng nội địa.'),
('TT008', 'Thanh toán qua thẻ Visa/MasterCard', 'Hỗ trợ thẻ quốc tế.'),
('TT009', 'Thanh toán bằng tiền mặt', 'Chỉ áp dụng tại cửa hàng.'),
('TT010', 'Thanh toán qua ShopeePay', 'Ưu đãi giảm giá 5%.');

-- Thêm dữ liệu vào bảng Danh_sach_yeu_thich
INSERT INTO Danh_sach_yeu_thich (ID_DS_yeu_thich, ID_SP, ID_KH)
VALUES 
('DSYT001', 'SP001', 'KH001'),
('DSYT002', 'SP002', 'KH002'),
('DSYT003', 'SP003', 'KH003'),
('DSYT004', 'SP004', 'KH004'),
('DSYT005', 'SP005', 'KH005'),
('DSYT006', 'SP006', 'KH006'),
('DSYT007', 'SP007', 'KH007'),
('DSYT008', 'SP008', 'KH008'),
('DSYT009', 'SP009', 'KH009'),
('DSYT010', 'SP010', 'KH010');

-- Thêm dữ liệu vào bảng Bai_viet
INSERT INTO Bai_viet (ID_Bai_viet, Tua_de, Ngay_viet, Noi_dung, Anh_bai_viet, Phan_loai)
VALUES 
('BV001', 'Lợi ích của việc mua sắm online', '2023-11-01', 'Bài viết giới thiệu các lợi ích khi mua sắm online.', NULL, 'Kinh nghiệm'),
('BV002', 'Top 5 sản phẩm bán chạy tháng 11', '2023-11-02', 'Danh sách 5 sản phẩm bán chạy nhất.', NULL, 'Tin tức'),
('BV003', 'Hướng dẫn chọn giày thể thao phù hợp', '2023-11-03', 'Các tiêu chí để chọn giày thể thao tốt.', NULL, 'Hướng dẫn'),
('BV004', 'Những mẫu áo khoác đẹp cho mùa đông', '2023-11-04', 'Tổng hợp những mẫu áo khoác hot trend.', NULL, 'Thời trang'),
('BV005', 'Cách bảo quản đồ da', '2023-11-05', 'Hướng dẫn cách bảo quản túi xách và giày da.', NULL, 'Hướng dẫn'),
('BV006', 'Đánh giá sản phẩm mới', '2023-11-06', 'Nhận xét về các sản phẩm vừa ra mắt.', NULL, 'Đánh giá'),
('BV007', 'Ưu đãi đặc biệt mùa lễ hội', '2023-11-07', 'Thông tin về các chương trình giảm giá.', NULL, 'Khuyến mãi'),
('BV008', 'Cách phối đồ với phụ kiện thời trang', '2023-11-08', 'Gợi ý phối đồ với túi xách và trang sức.', NULL, 'Thời trang'),
('BV009', 'Tư vấn chọn quần áo trẻ em', '2023-11-09', 'Mẹo mua quần áo cho bé yêu.', NULL, 'Hướng dẫn'),
('BV010', 'Xu hướng thời trang 2024', '2023-11-10', 'Dự đoán xu hướng thời trang năm tới.', NULL, 'Thời trang');

-- Thêm dữ liệu vào bảng Hinh_thuc_giao_hang
INSERT INTO Hinh_thuc_giao_hang (ID_Giao_hang, Hinh_thuc_GH, Mo_ta_GH)
VALUES 
('GH001', 'Giao hàng tiêu chuẩn', 'Thời gian 3-5 ngày làm việc.'),
('GH002', 'Giao hàng nhanh', 'Thời gian 1-2 ngày làm việc.'),
('GH003', 'Giao hàng tiết kiệm', 'Thời gian 5-7 ngày làm việc.'),
('GH004', 'Giao hàng trong ngày', 'Áp dụng trong nội thành.'),
('GH005', 'Giao hàng miễn phí', 'Áp dụng với đơn hàng từ 500K.'),
('GH006', 'Giao hàng COD', 'Thanh toán khi nhận hàng.'),
('GH007', 'Giao hàng quốc tế', 'Thời gian 7-15 ngày làm việc.'),
('GH008', 'Giao hàng bằng drone', 'Chỉ áp dụng tại một số địa điểm.'),
('GH009', 'Giao hàng siêu tốc', 'Nhận hàng trong 2 giờ.'),
('GH010', 'Giao hàng thông minh', 'Tùy chọn thời gian giao hàng.');


-- Thêm dữ liệu vào bảng Trang
INSERT INTO Trang (ID_Trang, Ten_trang, Ngay_tao_trang, Ngay_update)
VALUES 
('TR001', 'Trang chủ', '2023-11-01', '2023-11-05'),
('TR002', 'Giới thiệu', '2023-11-01', '2023-11-05'),
('TR003', 'Sản phẩm', '2023-11-01', '2023-11-05'),
('TR004', 'Khuyến mãi', '2023-11-01', '2023-11-05'),
('TR005', 'Liên hệ', '2023-11-01', '2023-11-05'),
('TR006', 'Chính sách bảo mật', '2023-11-01', '2023-11-05'),
('TR007', 'Điều khoản sử dụng', '2023-11-01', '2023-11-05'),
('TR008', 'Tin tức', '2023-11-01', '2023-11-05'),
('TR009', 'Hướng dẫn mua hàng', '2023-11-01', '2023-11-05'),
('TR010', 'FAQ', '2023-11-01', '2023-11-05');

-- Thêm dữ liệu vào bảng Su_kien
INSERT INTO Su_kien (ID_Su_kien, Ten_SK, Ngay_BD, Ngay_KT, Noi_dung_SK, Anh_SK)
VALUES 
('SK001', 'Khuyến mãi Tết', '2023-12-01', '2023-12-31', 'Chương trình khuyến mãi dịp Tết', NULL),
('SK002', 'Black Friday', '2023-11-25', '2023-11-30', 'Giảm giá lớn ngày Black Friday', NULL),
('SK003', 'Cyber Monday', '2023-12-01', '2023-12-02', 'Ưu đãi công nghệ', NULL),
('SK004', 'Giảm giá mùa đông', '2023-11-15', '2023-12-15', 'Khuyến mãi sản phẩm mùa đông', NULL),
('SK005', 'Mừng năm mới', '2024-01-01', '2024-01-07', 'Khuyến mãi đầu năm', NULL),
('SK006', 'Valentine Sale', '2024-02-10', '2024-02-14', 'Ưu đãi dành cho các cặp đôi', NULL),
('SK007', 'Quốc tế phụ nữ', '2024-03-01', '2024-03-08', 'Khuyến mãi dành cho phái đẹp', NULL),
('SK008', 'Ưu đãi hè', '2024-06-01', '2024-06-30', 'Giảm giá lớn cho mùa hè', NULL),
('SK009', 'Back to School', '2024-08-01', '2024-08-15', 'Ưu đãi cho học sinh, sinh viên', NULL),
('SK010', 'Khuyến mãi đặc biệt', '2024-11-01', '2024-11-10', 'Ưu đãi siêu đặc biệt', NULL);

-- Thêm dữ liệu vào bảng Giam_gia
INSERT INTO Giam_gia (ID_Giam_gia, ID_Su_kien, ID_SP, Codes, Gia_giam)
VALUES 
('GG001', 'SK001', 'SP001', 'SALE10', 10000),
('GG002', 'SK002', 'SP002', 'SALE20', 20000),
('GG003', 'SK003', 'SP003', 'SALE30', 30000),
('GG004', 'SK004', 'SP004', 'SALE40', 40000),
('GG005', 'SK005', 'SP005', 'SALE50', 50000),
('GG006', 'SK006', 'SP006', 'SALE60', 60000),
('GG007', 'SK007', 'SP007', 'SALE70', 70000),
('GG008', 'SK008', 'SP008', 'SALE80', 80000),
('GG009', 'SK009', 'SP009', 'SALE90', 90000),
('GG010', 'SK010', 'SP010', 'SALE100', 100000);

-- Thêm dữ liệu vào bảng Gio_hang
INSERT INTO Gio_hang (ID_Gio_hang, ID_SP, ID_KH, So_luong)
VALUES 
('GH001', 'SP001', 'KH001', 2),
('GH002', 'SP002', 'KH002', 1),
('GH003', 'SP003', 'KH003', 3),
('GH004', 'SP004', 'KH004', 1),
('GH005', 'SP005', 'KH005', 2),
('GH006', 'SP006', 'KH006', 4),
('GH007', 'SP007', 'KH007', 2),
('GH008', 'SP008', 'KH008', 1),
('GH009', 'SP009', 'KH009', 3),
('GH010', 'SP010', 'KH010', 5);

-- Thêm dữ liệu vào bảng Don_hang
INSERT INTO Don_hang (ID_DH, Ngay_dat, Trang_thai_thanh_toan, Phi_giao_hang, Giam_gia, Tong_tien, Trang_thai_giao_hang, ID_Thanh_toan, ID_GH, ID_KH)
VALUES 
('DH001', '2023-11-01', 'Đã thanh toán', 20000, 10000, 500000, 'Đang giao', 'TT001', 'GH001', 'KH001'),
('DH002', '2023-11-02', 'Chưa thanh toán', 30000, 0, 300000, 'Chưa giao', 'TT002', 'GH002', 'KH002'),
('DH003', '2023-11-03', 'Đã thanh toán', 25000, 5000, 700000, 'Đã giao', 'TT003', 'GH003', 'KH003'),
('DH004', '2023-11-04', 'Đã thanh toán', 40000, 20000, 1000000, 'Đang giao', 'TT004', 'GH004', 'KH004'),
('DH005', '2023-11-05', 'Đã thanh toán', 15000, 0, 250000, 'Đã giao', 'TT005', 'GH005', 'KH005'),
('DH006', '2023-11-06', 'Chưa thanh toán', 50000, 0, 450000, 'Chưa giao', 'TT006', 'GH006', 'KH006'),
('DH007', '2023-11-07', 'Đã thanh toán', 20000, 10000, 650000, 'Đang giao', 'TT007', 'GH007', 'KH007'),
('DH008', '2023-11-08', 'Chưa thanh toán', 35000, 0, 750000, 'Chưa giao', 'TT008', 'GH008', 'KH008'),
('DH009', '2023-11-09', 'Đã thanh toán', 30000, 5000, 850000, 'Đã giao', 'TT009', 'GH009', 'KH009'),
('DH010', '2023-11-10', 'Đã thanh toán', 40000, 15000, 950000, 'Đang giao', 'TT010', 'GH010', 'KH010');

-- Thêm dữ liệu vào bảng Chi_tiet_don_hang
INSERT INTO Chi_tiet_don_hang (ID_Chi_tiet_DH, So_luong_SP, Kich_co, Tong_tien, Cach_thanh_toan, Ten_SP, Dia_chi, SDT, Email, Ghi_chu, ID_SP, ID_DH)
VALUES 
('CTDH001', 2, 'M', 200000, 'Thanh toán online', 'Áo thun', 'Hanoi', '0123456789', 'user1@example.com', NULL, 'SP001', 'DH001'),
('CTDH002', 1, 'L', 150000, 'Thanh toán COD', 'Quần jean', 'Saigon', '0123456790', 'user2@example.com', NULL, 'SP002', 'DH002'),
('CTDH003', 3, 'XL', 300000, 'Thanh toán online', 'Áo khoác', 'Danang', '0123456791', 'user3@example.com', 'Giao nhanh', 'SP003', 'DH003'),
('CTDH004', 1, 'S', 500000, 'Thanh toán COD', 'Giày thể thao', 'Hue', '0123456792', 'user4@example.com', NULL, 'SP004', 'DH004'),
('CTDH005', 2, 'M', 400000, 'Thanh toán online', 'Túi xách', 'Hai Phong', '0123456793', 'user5@example.com', NULL, 'SP005', 'DH005'),
('CTDH006', 4, 'L', 600000, 'Thanh toán COD', 'Đồng hồ', 'Quang Ninh', '0123456794', 'user6@example.com', 'Hàng dễ vỡ', 'SP006', 'DH006'),
('CTDH007', 2, 'M', 700000, 'Thanh toán online', 'Áo len', 'Vinh', '0123456795', 'user7@example.com', NULL, 'SP007', 'DH007'),
('CTDH008', 1, 'S', 800000, 'Thanh toán COD', 'Nước hoa', 'Nha Trang', '0123456796', 'user8@example.com', 'Giao buổi tối', 'SP008', 'DH008'),
('CTDH009', 3, 'L', 900000, 'Thanh toán online', 'Balo', 'Phu Quoc', '0123456797', 'user9@example.com', NULL, 'SP009', 'DH009'),
('CTDH010', 5, 'XL', 1000000, 'Thanh toán COD', 'Ốp điện thoại', 'Can Tho', '0123456798', 'user10@example.com', NULL, 'SP010', 'DH010');

-- Thêm dữ liệu vào bảng Binh_luan
INSERT INTO Binh_luan (ID_binh_luan, Binh_luan, Ngay_BL, Star, ID_KH, ID_SP)
VALUES 
('BL001', 'Sản phẩm rất tốt, giao hàng nhanh.', '2023-11-01', 5, 'KH001', 'SP001'),
('BL002', 'Hàng đẹp, đúng như mô tả.', '2023-11-02', 4, 'KH002', 'SP002'),
('BL003', 'Sản phẩm chất lượng, giá hợp lý.', '2023-11-03', 5, 'KH003', 'SP003'),
('BL004', 'Đóng gói cẩn thận, giao hàng đúng hẹn.', '2023-11-04', 4, 'KH004', 'SP004'),
('BL005', 'Màu sắc đẹp, chất liệu tốt.', '2023-11-05', 5, 'KH005', 'SP005'),
('BL006', 'Sản phẩm hơi khác hình ảnh nhưng vẫn ổn.', '2023-11-06', 3, 'KH006', 'SP006'),
('BL007', 'Giao hàng chậm nhưng chất lượng tốt.', '2023-11-07', 4, 'KH007', 'SP007'),
('BL008', 'Hài lòng, sẽ mua lại lần sau.', '2023-11-08', 5, 'KH008', 'SP008'),
('BL009', 'Sản phẩm dễ sử dụng, tiện lợi.', '2023-11-09', 4, 'KH009', 'SP009'),
('BL010', 'Gói hàng đẹp, dịch vụ chu đáo.', '2023-11-10', 5, 'KH010', 'SP010');

-- Thêm dữ liệu vào bảng Lich_su_ban_hang
INSERT INTO Lich_su_ban_hang (ID_Lich_su, ID_DH, ID_KH)
VALUES 
('LS001', 'DH001', 'KH001'),
('LS002', 'DH002', 'KH002'),
('LS003', 'DH003', 'KH003'),
('LS004', 'DH004', 'KH004'),
('LS005', 'DH005', 'KH005'),
('LS006', 'DH006', 'KH006'),
('LS007', 'DH007', 'KH007'),
('LS008', 'DH008', 'KH008'),
('LS009', 'DH009', 'KH009'),
('LS010', 'DH010', 'KH010');