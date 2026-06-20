// Modern Dashboard Charts Initialization
document.addEventListener('DOMContentLoaded', function() {
    initRevenueChart();
    initOrderStatusChart();
});

let revenueChartInstance = null;

function initRevenueChart() {
    const chartData = window.dashboardData?.revenue || {};
    const chartEl = document.getElementById('revenueChart');
    
    if (!chartData.labels || !chartEl) {
        return;
    }

    const revenueCtx = chartEl.getContext('2d');
    
    // Create soft gradient for area under line
    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

    const config = {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Doanh Thu (₫)',
                data: chartData.data,
                borderColor: '#2563EB',
                borderWidth: 3,
                backgroundColor: gradient,
                tension: 0.35,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563EB',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false, // Cleaner without legend as the card title explains it
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: '#1E293B',
                    titleFont: {
                        family: 'Inter',
                        weight: 'bold',
                        size: 13
                    },
                    bodyFont: {
                        family: 'Inter',
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 11
                        },
                        color: '#64748B'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F1F5F9'
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 11
                        },
                        color: '#64748B',
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000) + 'M ₫';
                            }
                            if (value >= 1000) {
                                return (value / 1000) + 'K ₫';
                            }
                            return value + ' ₫';
                        }
                    }
                }
            }
        }
    };

    revenueChartInstance = new Chart(revenueCtx, config);

    // Wire up interactive filters
    const filterButtons = document.querySelectorAll('.btn-chart-filter');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const range = this.getAttribute('data-range');
            let limit = 30; // default
            
            if (range === '7') {
                limit = 7;
            } else if (range === '30') {
                limit = 30;
            } else if (range === '90') {
                limit = 90; // will show max available
            } else if (range === '365') {
                limit = 365;
            }

            // Slice labels and data from the end
            const totalLength = chartData.labels.length;
            const startIndex = Math.max(0, totalLength - limit);
            const newLabels = chartData.labels.slice(startIndex);
            const newData = chartData.data.slice(startIndex);

            // Update chart data
            revenueChartInstance.data.labels = newLabels;
            revenueChartInstance.data.datasets[0].data = newData;
            revenueChartInstance.update();
        });
    });
}

function initOrderStatusChart() {
    const chartData = window.dashboardData?.status || [];
    const chartEl = document.getElementById('orderStatusChart');
    
    if (!chartData.length || !chartEl) {
        return;
    }

    const statusCtx = chartEl.getContext('2d');
    
    const statusLabels = {
        'pending': 'Chờ Xử Lý',
        'processing': 'Đang Xử Lý',
        'shipped': 'Đang Giao', // translated from shipped/shipping
        'delivered': 'Đã Giao',
        'cancelled': 'Đã Hủy'
    };

    const statusColors = {
        'pending': '#F59E0B',      // Warning Amber
        'processing': '#2563EB',   // Primary Blue
        'shipped': '#0ea5e9',      // Info Sky Blue
        'delivered': '#10B981',    // Success Emerald
        'cancelled': '#EF4444'     // Danger Red
    };

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.map(s => statusLabels[s.status] || s.status),
            datasets: [{
                data: chartData.map(s => s.count),
                backgroundColor: chartData.map(s => statusColors[s.status] || s.color),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: 'Inter',
                            size: 12,
                            weight: '500'
                        },
                        color: '#1E293B'
                    }
                },
                tooltip: {
                    padding: 10,
                    cornerRadius: 6,
                    backgroundColor: '#1E293B'
                }
            }
        }
    });
}
