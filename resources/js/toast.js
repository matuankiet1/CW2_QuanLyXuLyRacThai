// New
window.showToast = function (type, message) {
    // Đợi DOM ready trước khi thực thi
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => {
            window.showToast(type, message);
        });
        return;
    }
    const toastContainer = createToastContainer();
    const newToast = createNewToast(type, message);

    toastContainer.appendChild(newToast);
    requestAnimationFrame(() => {
        newToast.classList.remove("hidden", "opacity-0", "translate-y-2");
        newToast.classList.add("opacity-100", "translate-y-0");
    });

    setTimeout(() => {
        newToast.classList.add("opacity-0", "translate-y-2");
        setTimeout(() => newToast.remove(), 300);
    }, 4000);
};

// Khởi tạo container và template khi DOM ready
document.addEventListener("DOMContentLoaded", function () {
    if (!document.querySelector(".toast-container")) {
        createToastContainer();
    }
});

function createToastContainer() {
    let toastContainer = document.querySelector(".toast-container");

    if (!toastContainer) {
        toastContainer = document.createElement("div");
        toastContainer.className =
            "toast-container fixed top-0 right-0 z-50 p-3 flex flex-col gap-3";
        document.body.appendChild(toastContainer);
    }

    return toastContainer;
}

function createNewToast(type, message) {
    const toast = document.createElement("div");
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");
    toast.className =
        "pointer-events-auto flex items-center gap-3 rounded-lg shadow-lg bg-white ring-1 ring-black/10 px-4 py-3 transition transform duration-300 ease-out opacity-0 translate-y-2";

    let iconSrc = "/images/success-icon.png";

    if (type === "error") {
        iconSrc = "/images/error-icon-4.png";
    } else if (type === "info") {
        iconSrc = "/images/info-icon.png";
    }

    toast.innerHTML = `
        <img class="toast-icon h-6 w-6" src="${iconSrc}" alt="toast-icon">
        <span class="message">${message || "Notification"}</span>
        <button type="button" aria-label="Close"
          class="ml-2 inline-flex h-6 w-6 items-center justify-center rounded-md hover:bg-white/10
                 focus:outline-none focus:ring-2 focus:ring-white/70 focus:ring-offset-2 focus:ring-offset-slate-900">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18M6 6l12 12" />
          </svg>
        </button>
    `;

    // Thêm sự kiện đóng toast khi click vào nút close
    const closeButton = toast.querySelector("button");
    closeButton.addEventListener("click", () => {
        toast.classList.add("opacity-0", "translate-y-2");
        setTimeout(() => toast.remove(), 300);
    });

    return toast;
}
