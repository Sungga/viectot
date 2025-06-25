document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".custom-dropdown").forEach(dropdown => {
        const selected = dropdown.querySelector(".selected");
        const list = dropdown.querySelector(".dropdown-list");
        const items = dropdown.querySelectorAll(".dropdown-list li");

        // Mở dropdown khi click
        selected.addEventListener("click", function (event) {
            event.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
            // Đóng tất cả dropdown khác trước khi mở dropdown này
            document.querySelectorAll(".dropdown-list").forEach(el => {
                if (el !== list) el.style.display = "none";
            });
            list.style.display = list.style.display === "block" ? "none" : "block";
        });

        // Chọn giá trị từ danh sách
        items.forEach(item => {
            item.addEventListener("click", function () {
                selected.textContent = this.textContent; // Hiển thị lựa chọn
                selected.setAttribute("data-value", this.getAttribute("data-value")); // Gán giá trị
                list.style.display = "none"; // Đóng dropdown
            });
        });
    });

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener("click", function () {
        document.querySelectorAll(".dropdown-list").forEach(list => {
            list.style.display = "none";
        });
    });
});
