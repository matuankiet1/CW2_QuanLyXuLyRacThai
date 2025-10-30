const selectAllCheckbox = document.querySelector("#selectAllCheckbox");
const checkboxes = document.querySelectorAll(".checkbox");
const btnDeleteAll = document.querySelector("#btnDeleteAll");

// Xử lý sự kiện check/uncheck tất cả
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
        const isChecked = this.checked;
        checkboxes.forEach((checkbox) => {
            checkbox.checked = isChecked;
        });
        updateDeleteButton();
    });
}

// Xử lý sự kiện khi check/uncheck từng item
checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        // Kiểm tra nếu tất cả checkbox con được check
        const allChecked = Array.from(checkboxes).every(
            (cb) => cb.checked
        );
        selectAllCheckbox.checked = allChecked;
        updateDeleteButton();
    });
});

// Cập nhật trạng thái nút xóa
function updateDeleteButton() {
    const checkedCount = Array.from(checkboxes).filter(
        (cb) => cb.checked
    ).length;
    btnDeleteAll.style.display = checkedCount >= 1 ? "inline-flex" : "none";
}
