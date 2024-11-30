-- Tắt kiểm tra ràng buộc khóa ngoại tạm thời
SET foreign_key_checks = 0;

-- Xóa tất cả dữ liệu
DELETE FROM Gio_hang;
DELETE FROM Lich_su_ban_hang;
DELETE FROM Giam_gia;
DELETE FROM Binh_luan;
DELETE FROM Danh_sach_yeu_thich;
DELETE FROM Chi_tiet_don_hang;
DELETE FROM Don_hang;

DELETE FROM Su_kien;
DELETE FROM Bai_viet;
DELETE FROM Hinh_thuc_giao_hang;
DELETE FROM Thanh_toan;
DELETE FROM San_pham;
DELETE FROM Mau_sac;
DELETE FROM Kich_co;
DELETE FROM Danh_muc;
DELETE FROM Khach_hang;
DELETE FROM Tai_khoan;
DELETE FROM Trang;

-- Bật lại kiểm tra ràng buộc khóa ngoại
SET foreign_key_checks = 1;
