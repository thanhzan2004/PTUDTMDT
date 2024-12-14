-- Dữ liệu cho bảng Danh_muc
INSERT INTO Danh_muc (ID_Danh_muc, Ten_DM) VALUES
('DM001', 'Sữa rửa mặt'),
('DM002', 'Kem dưỡng'),
('DM003', 'Tẩy trang'),
('DM004', 'Serum'),
('DM005', 'Mặt nạ');

-- Dữ liệu cho bảng San_pham
INSERT INTO San_pham (ID_SP, Ten_SP, Gia_SP, Anh_SP, Mo_ta_SP, Cong_dung, HD_SD, So_luong_ton_kho, ID_Danh_muc) VALUES
('SP001', 'Sữa Rửa Mặt Trà Xanh', 250000, 'link_anh_sua_tra_xanh.jpg', 'Sữa rửa mặt giúp làm sạch sâu và cung cấp độ ẩm cho da.', 'Làm sạch da, cấp ẩm, se khít lỗ chân lông', 'Sử dụng vào buổi sáng và tối, tạo bọt rồi massage nhẹ nhàng trên da.', 100, 'DM001'),
('SP002', 'Kem Dưỡng Ẩm Hương Hoa', 350000, 'link_anh_kem_duong_hoa.jpg', 'Kem dưỡng ẩm giúp da mềm mại, mịn màng suốt cả ngày.', 'Dưỡng ẩm, làm sáng da, phục hồi da khô', 'Thoa lên da mặt sau khi rửa mặt, sử dụng đều đặn mỗi sáng tối.', 50, 'DM002'),
('SP003', 'Tẩy Trang Nước Dừa', 150000, 'link_anh_tay_trang_dua.jpg', 'Nước tẩy trang nhẹ nhàng loại bỏ lớp makeup mà không làm khô da.', 'Tẩy trang, làm sạch lớp make-up', 'Dùng bông tẩy trang thấm đều và lau nhẹ lên da.', 80, 'DM003'),
('SP004', 'Serum Vitamin C', 500000, 'link_anh_serum_vitamin_c.jpg', 'Serum Vitamin C giúp làm sáng và đều màu da.', 'Chống lão hóa, làm sáng da, cải thiện sắc tố da', 'Thoa serum lên mặt sau khi dùng toner, sử dụng mỗi ngày.', 120, 'DM004'),
('SP005', 'Mặt Nạ Ngủ Ban Đêm', 200000, 'link_anh_mat_na_ngu.jpg', 'Mặt nạ giúp da phục hồi nhanh chóng, tạo cảm giác mềm mịn vào sáng hôm sau.', 'Cung cấp độ ẩm, phục hồi da, làm dịu da', 'Sử dụng vào buổi tối, thoa một lớp mỏng và để qua đêm.', 150, 'DM005');

-- Dữ liệu cho bảng don_hang_chi_tiet
INSERT INTO don_hang_chi_tiet (ID_DH, ID_SP, Ten_SP, So_luong_SP, Gia_SP, Cach_thanh_toan, Dia_chi, Ngay_dat, Trang_thai_thanh_toan, Trang_thai_giao_hang, Tong_tien, SDT, Ten_khach_hang, Email, Ghi_chu) VALUES
('DH001', 'SP001', 'Sữa Rửa Mặt Trà Xanh', 2, 250000, 'Thanh toán khi nhận hàng', '123 Đường ABC, Quận 1, TP.HCM', '2024-12-14', 'Chưa thanh toán', 'Chưa giao hàng', 500000, '0912345678', 'Nguyễn Minh Tuan', 'nguyenminhtuan@email.com', 'Yêu cầu giao hàng nhanh'),
('DH002', 'SP002', 'Kem Dưỡng Ẩm Hương Hoa', 1, 350000, 'Chuyển khoản ngân hàng', '456 Đường DEF, Quận 3, TP.HCM', '2024-12-13', 'Đã thanh toán', 'Đã giao hàng', 350000, '0987654321', 'Lê Thị Mai', 'lethimai@email.com', 'Không có ghi chú'),
('DH003', 'SP003', 'Tẩy Trang Nước Dừa', 3, 150000, 'Thanh toán khi nhận hàng', '789 Đường GHI, Quận 10, TP.HCM', '2024-12-12', 'Chưa thanh toán', 'Chưa giao hàng', 450000, '0978123456', 'Phạm Hoàng Anh', 'phamhoanganh@email.com', 'Giao hàng vào cuối tuần'),
('DH004', 'SP004', 'Serum Vitamin C', 1, 500000, 'Chuyển khoản ngân hàng', '321 Đường JKL, Quận 7, TP.HCM', '2024-12-11', 'Đã thanh toán', 'Đã giao hàng', 500000, '0912456789', 'Trần Đức Long', 'tranduclong@email.com', 'Cảm ơn cửa hàng'),
('DH005', 'SP005', 'Mặt Nạ Ngủ Ban Đêm', 2, 200000, 'Thanh toán khi nhận hàng', '654 Đường MNO, Quận 5, TP.HCM', '2024-12-10', 'Chưa thanh toán', 'Chưa giao hàng', 400000, '0932123456', 'Nguyễn Thị Lan', 'nguyenlan@email.com', 'Giao hàng vào sáng mai');

-- Dữ liệu cho bảng Bai_viet
INSERT INTO Bai_viet (Tua_de, Ngay_viet, Noi_dung, Anh_bai_viet, Phan_loai) VALUES
('Cách Chăm Sóc Da Mặt Hằng Ngày', '2024-12-01', 'Bài viết này chia sẻ cách chăm sóc da mặt đơn giản và hiệu quả với các sản phẩm chăm sóc da cơ bản như sữa rửa mặt, kem dưỡng và serum.', 'link_anh_bai_viet_1.jpg', 'Chăm sóc da'),
('10 Sản Phẩm Dưỡng Da Tốt Nhất Năm 2024', '2024-12-05', 'Khám phá những sản phẩm dưỡng da hot nhất trong năm 2024, từ serum, kem dưỡng đến mặt nạ ban đêm.', 'link_anh_bai_viet_2.jpg', 'Sản phẩm'),
('Tẩy Trang Đúng Cách: Điều Bạn Cần Biết', '2024-12-08', 'Để có làn da khỏe mạnh, việc tẩy trang đúng cách là rất quan trọng. Bài viết này sẽ giúp bạn hiểu cách chọn lựa tẩy trang hiệu quả cho từng loại da.', 'link_anh_bai_viet_3.jpg', 'Làm đẹp'),
('Serum Vitamin C: Bí Quyết Làm Sáng Da', '2024-12-10', 'Vitamin C là một thành phần quan trọng trong việc làm sáng da và chống lão hóa. Bài viết này chia sẻ những lợi ích của serum Vitamin C.', 'link_anh_bai_viet_4.jpg', 'Sản phẩm'),
('Mặt Nạ Ngủ Ban Đêm: Phục Hồi Da Tối Ưu', '2024-12-12', 'Mặt nạ ngủ ban đêm giúp phục hồi da sau một ngày dài. Hãy khám phá những lợi ích và cách sử dụng mặt nạ ngủ hiệu quả.', 'link_anh_bai_viet_5.jpg', 'Chăm sóc da');
