import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    // Thống kê rác theo tháng
    const wasteCtx = document.getElementById('wasteChart');
    if (wasteCtx) {
        new Chart(wasteCtx, {
            type: 'bar',
            data: {
                labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10'],
                datasets: [{
                    label: 'Rác thu gom (kg)',
                    data: [245, 312, 287, 356, 423, 389, 467, 512, 489, 534],
                    backgroundColor: '#22c55e'
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Phân loại rác thải
    const typeCtx = document.getElementById('wasteTypeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: ['Nhựa', 'Giấy', 'Kim loại', 'Thủy tinh', 'Khác'],
                datasets: [{
                    data: [35, 28, 18, 12, 7],
                    backgroundColor: ['#22c55e','#10b981','#14b8a6','#06b6d4','#3b82f6']
                }]
            }
        });
    }

    // Xu hướng sinh viên
    const trendCtx = document.getElementById('studentTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10'],
                datasets: [{
                    label: 'Số sinh viên',
                    data: [89,102,95,115,128,118,142,156,148,167],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
