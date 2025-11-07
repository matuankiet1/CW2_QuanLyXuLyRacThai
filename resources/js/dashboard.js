// Lê Tâm: Nếu code chưa sử dụng tới thì tôi xin phép comment lại, vì tôi có code ở dưới

// document.addEventListener("DOMContentLoaded", function () {
//     // Dữ liệu
//     const monthlyData = [
//         { month: "T1", waste: 245, participants: 89 },
//         { month: "T2", waste: 312, participants: 102 },
//         { month: "T3", waste: 287, participants: 95 },
//         { month: "T4", waste: 356, participants: 115 },
//         { month: "T5", waste: 423, participants: 128 },
//         { month: "T6", waste: 389, participants: 118 },
//         { month: "T7", waste: 467, participants: 142 },
//         { month: "T8", waste: 512, participants: 156 },
//         { month: "T9", waste: 489, participants: 148 },
//         { month: "T10", waste: 534, participants: 167 },
//     ];

//     const wasteTypeData = [
//         { name: "Nhựa", value: 35 },
//         { name: "Giấy", value: 28 },
//         { name: "Kim loại", value: 18 },
//         { name: "Thủy tinh", value: 12 },
//         { name: "Khác", value: 7 },
//     ];

//     const topStudents = [
//         { name: "Nguyễn Văn A", points: 450, waste: 89 },
//         { name: "Trần Thị B", points: 420, waste: 82 },
//         { name: "Lê Văn C", points: 395, waste: 78 },
//         { name: "Phạm Thị D", points: 380, waste: 75 },
//         { name: "Hoàng Văn E", points: 365, waste: 71 },
//     ];

//     // --- Biểu đồ cột: Rác thải theo tháng ---
//     new Chart(document.getElementById("wasteChart"), {
//         type: "bar",
//         data: {
//             labels: monthlyData.map((d) => d.month),
//             datasets: [
//                 {
//                     label: "Rác thu gom (kg)",
//                     data: monthlyData.map((d) => d.waste),
//                     backgroundColor: "#22c55e",
//                 },
//             ],
//         },
//         options: { responsive: true, plugins: { legend: { display: true } } },
//     });

//     // --- Biểu đồ tròn: Phân loại rác ---
//     new Chart(document.getElementById("wasteTypeChart"), {
//         type: "pie",
//         data: {
//             labels: wasteTypeData.map((d) => d.name),
//             datasets: [
//                 {
//                     data: wasteTypeData.map((d) => d.value),
//                     backgroundColor: [
//                         "#22c55e",
//                         "#10b981",
//                         "#14b8a6",
//                         "#06b6d4",
//                         "#3b82f6",
//                     ],
//                 },
//             ],
//         },
//     });

//     // --- Biểu đồ đường: Số sinh viên tham gia ---
//     new Chart(document.getElementById("participantsChart"), {
//         type: "line",
//         data: {
//             labels: monthlyData.map((d) => d.month),
//             datasets: [
//                 {
//                     label: "Số sinh viên",
//                     data: monthlyData.map((d) => d.participants),
//                     borderColor: "#10b981",
//                     backgroundColor: "#10b98133",
//                     tension: 0.3,
//                     fill: true,
//                 },
//             ],
//         },
//         options: { responsive: true },
//     });

//     // --- Danh sách top sinh viên ---
//     const container = document.getElementById("topStudents");
//     topStudents.forEach((s, i) => {
//         const li = document.createElement("li");
//         li.innerHTML = `
//             <div class="flex items-center gap-4">
//                 <div class="w-8 h-8 bg-green-100 text-green-700 font-medium rounded-full flex items-center justify-center">
//                     ${i + 1}
//                 </div>
//                 <div class="flex-1">
//                     <p class="text-sm font-medium text-gray-800">${s.name}</p>
//                     <div class="flex items-center gap-4 text-xs text-gray-500 mt-1">
//                         <span>${s.points} điểm</span>
//                         <span>•</span>
//                         <span>${s.waste} kg</span>
//                     </div>
//                 </div>
//                 <div class="w-32 bg-gray-100 rounded-full overflow-hidden h-2">
//                     <div class="bg-green-500 h-full rounded-full" style="width: ${
//                         (s.points / 500) * 100
//                     }%"></div>
//                 </div>
//             </div>
//         `;
//         container.appendChild(li);
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const userDropdown = document.getElementById("user-dropdown");
        const btn = userDropdown.querySelector(".btn-user-dropdown");
        const menu = userDropdown.querySelector(".menu-user-dropdown");
        const items = Array.from(
            menu.querySelectorAll('[role="menuItemUserDropdown"]')
        );

        function openMenu() {
            menu.style.opacity = "1";
            menu.style.transform = "scale(1)";
            menu.style.pointerEvents = "auto";
            btn.setAttribute("aria-expanded", "true");
        }
        function closeMenu() {
            menu.style.opacity = "0";
            menu.style.transform = "scale(0.95)";
            menu.style.pointerEvents = "none";
            btn.setAttribute("aria-expanded", "false");
        }

        function isOpen() {
            return btn.getAttribute("aria-expanded") === "true";
        }

        // Toggle on click
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            isOpen()
                ? closeMenu()
                : (openMenu(), setTimeout(() => items[0]?.focus(), 0));
        });

        // Click outside to close
        document.addEventListener("click", (e) => {
            if (!userDropdown.contains(e.target)) closeMenu();
        });
    })();
});
