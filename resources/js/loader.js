document.addEventListener("DOMContentLoaded", function () {
    // CSS cho loader
    const style = document.createElement("style");
    style.textContent = `
    @keyframes spin-fast { 0% {transform:rotate(0)} 100% {transform:rotate(360deg)} }
    .animate-spin-fast { animation: spin-fast 0.8s linear infinite; }
  `;
    document.head.appendChild(style);

    // Tạo loader
    const loader = document.createElement("div");
    loader.id = "pageLoader";
    loader.className =
        "fixed inset-0 z-[9999] hidden flex items-center justify-center bg-transparent backdrop-blur";
    loader.innerHTML = `
    <div class="flex flex-col items-center space-y-4">
      <div class="w-12 h-12 border-4 border-transparent border-t-green-500 border-l-green-400 rounded-full animate-spin-fast"></div>
      <p class="text-sm text-gray-700 animate-pulse">Đang xử lý, vui lòng chờ...</p>
    </div>
  `;
    document.body.appendChild(loader);
    // Bật loader + khóa tương tác
    function showPageLoader() {
        const loader = document.getElementById("pageLoader");
        loader.classList.remove("hidden");
        // khóa thao tác
        document.body.classList.add("overflow-hidden");
        document.body.classList.add("pointer-events-none"); // chặn click
        loader.classList.add("pointer-events-auto"); // nhưng cho overlay nhận click
    }

    // Khóa toàn bộ inputs trong form + đổi trạng thái nút submit
    function lockForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add("opacity-60", "cursor-not-allowed");
            // thêm spinner nhỏ trong nút
            const spinner = document.createElement("span");
            spinner.className = "ml-2 inline-block align-middle";
            spinner.innerHTML = `<svg class="animate-spin inline" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3"/></svg>`;
            submitBtn.appendChild(spinner);
            submitBtn.setAttribute("aria-busy", "true");
        }
        // Disable tất cả input/textarea/select (trừ token và hidden)
        form.querySelectorAll("input, textarea, select").forEach((el) => {
            if (el.name === "_token") return;
            if (el.type === "hidden") return;
            if (el.tagName === "SELECT") {
                el.disabled = true;
            } else {
                el.readOnly = true;
            }
        });

        // Disable các button khác
        form.querySelectorAll("button").forEach((btn) => {
            if (btn !== submitBtn) btn.disabled = true;
        });
    }

    // Gắn cho tất cả form
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener(
            "submit",
            function (e) {
                console.log("submit form");

                // nếu form invalid -> không bật loader
                if (!form.checkValidity()) {
                    return;
                }
                lockForm(form);
                showPageLoader();
            },
            { once: true } // tránh bind nhiều lần
        );
    });
});
