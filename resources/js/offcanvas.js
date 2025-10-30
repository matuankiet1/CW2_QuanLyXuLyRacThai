const offCanvas = document.getElementById("offCanvas");
const overlayOffCanvas = document.getElementById("overlayOffCanvas");
const openOffCanvasBtn = document.getElementById("openOffCanvasBtn");
const closeOffCanvasBtn = document.getElementById("closeOffCanvasBtn");
const resetFillterBtn = document.getElementById("resetFilter");

function openOffCanvas() {
    overlayOffCanvas.classList.remove("hidden");
    offCanvas.classList.remove("-translate-x-full");
}

function closeOffCanvas() {
    overlayOffCanvas.classList.add("hidden");
    offCanvas.classList.add("-translate-x-full");
}

openOffCanvasBtn.addEventListener("click", openOffCanvas);
closeOffCanvasBtn.addEventListener("click", closeOffCanvas);
overlayOffCanvas.addEventListener("click", closeOffCanvas);
resetFillterBtn.addEventListener("click", function (e) {
    e.preventDefault(); 
    const filterForm = document.getElementById("filterForm");
    filterForm.reset();
});
