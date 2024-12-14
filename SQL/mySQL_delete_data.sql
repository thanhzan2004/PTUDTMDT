-- Tắt kiểm tra ràng buộc khóa ngoại tạm thời
SET foreign_key_checks = 0;

-- Xóa tất cả dữ liệu
DELETE FROM don_hang_chi_tiet;
DELETE FROM Bai_viet;
DELETE FROM San_pham;
DELETE FROM Danh_muc;

-- Bật lại kiểm tra ràng buộc khóa ngoại
SET foreign_key_checks = 1;
