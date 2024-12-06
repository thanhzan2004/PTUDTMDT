// Lấy phần tử nút "Back to Top"
  const backToTopButton = document.getElementById('back-to-top');

// Hiện nút khi cuộn xuống
window.addEventListener('scroll', () => {
if (window.scrollY > 300) { // Nếu cuộn xuống hơn 300px
    backToTopButton.style.display = 'flex';
} else {
    backToTopButton.style.display = 'none';
}
});

// Khi nhấn vào nút, cuộn lên đầu trang
backToTopButton.addEventListener('click', () => {
window.scrollTo({
    top: 0,
    behavior: 'smooth', // Cuộn mượt
});
});
