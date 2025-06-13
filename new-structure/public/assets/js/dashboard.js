document.addEventListener('DOMContentLoaded', function() {
const ctxBar = document.getElementById('barChart').getContext('2d');
const sectorColors = {
    "Passed": "#006B3D",
    "Conditional": "#3581D8",
    "Failed": "#C23B21",
};
let barChart;
if (ctxBar) {
    barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ["Passed", "Conditional", "Failed"],
            datasets: [{
                label: 'Overall Evaluation Results',
                data: [
                    evaluationResults['Passed'] || 0,
                    evaluationResults['Conditional'] || 0,
                    evaluationResults['Failed'] || 0,
                ],
                backgroundColor: [
                    sectorColors["Passed"],
                    sectorColors["Conditional"],
                    sectorColors["Failed"]
                ],
                borderColor: [
                    '#ffffff',
                    '#ffffff',
                    '#ffffff'
                ],
                borderWidth: 0.5,
                borderRadius: 0,
                borderSkipped: false,
                barPercentage: 1,
            }]
        },
        options: {
            // barThickness: 10,
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(evaluationResults) + 2,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#fff',
                        stepSize: 0.5,
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#fff',
                        font: {
                            size: 10
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#fff',
                        boxWidth: 10,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 12
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            }
        }
    });
}

// Set sector colors based on chart colors
const sectorItems = document.querySelectorAll('.sector-item');
sectorItems.forEach((item, index) => {
    const sectorName = item.querySelector('span').textContent.split(':')[0].trim();
    item.style.setProperty('--sector-color', sectorColors[sectorName]);
});

// View All functionality
// const viewAllBtn = document.getElementById('viewAllBtn');
// const summaryItems = document.querySelectorAll('.summary-item');
// let detailsVisible = false;

// viewAllBtn.addEventListener('click', function() {
//     detailsVisible = !detailsVisible;
//     summaryItems.forEach(item => {
//         const detailItem = item.querySelector('.detail-item');

//         if (detailsVisible) {
//             item.classList.add('expanded');
//             detailItem.classList.remove('hidden');
//             viewAllBtn.innerHTML = 'View Less <i class="fas fa-chevron-up ml-1"></i>';
//         } else {
//             item.classList.remove('expanded');
//             detailItem.classList.add('hidden');
//             viewAllBtn.innerHTML = 'View All <i class="fas fa-chevron-down ml-1"></i>';
//         }
//     });
// });

// Add resize observer to handle chart resizing
const chartBoxes = document.querySelectorAll('.chart-box.wide.elegant, .chart-with-activity');
const resizeObserver = new ResizeObserver(entries => {
    for (let entry of entries) {
        const width = entry.contentRect.width;
        const chartContent = entry.target.querySelector('.chart-content, .chart-with-activity');

        if (chartContent) {
            if (width < 992) {
                chartContent.classList.add('responsive-row');
                chartContent.classList.add('responsive-column');
            } else {
                chartContent.classList.remove('responsive-column');
                chartContent.classList.add('responsive-row');
            }
        }

        if (barChart) barChart.resize();
    }
});

chartBoxes.forEach(chartBox => resizeObserver.observe(chartBox));
});

document.getElementById('logoutBtn').addEventListener('click', function(event) {
event.preventDefault();
Swal.fire({
    title: 'Are you sure?',
    text: 'Do you really want to log out?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, log out',
    cancelButtonText: 'No, stay logged in',
    confirmButtonColor: '#e77373',
    cancelButtonColor: '#6c757d',
    customClass: {
        popup: 'custom-logout-popup'
    }
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = '../backend/LoginSystem/logout.php';
    }
});
});

