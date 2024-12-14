document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".sidebar ul li a");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab, index) => {
        tab.addEventListener("click", function (event) {
            event.preventDefault();

            // Xóa active trên tất cả tab
            tabs.forEach(tab => tab.classList.remove("active"));
            tabContents.forEach(content => content.classList.remove("active"));

            // Kích hoạt tab và nội dung hiện tại
            tab.classList.add("active");
            tabContents[index].classList.add("active");
        });
    });
});
