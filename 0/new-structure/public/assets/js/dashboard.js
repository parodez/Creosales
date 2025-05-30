document.addEventListener('DOMContentLoaded', function() {
const ctxBar = document.getElementById('barChart').getContext('2d');
const sectorColors = {
    "Passed": "#00ff00",
    "Conditional": "#ffff00",
    "Failed": "#ff0000",
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
                borderWidth: 1,
                borderRadius: 0,
                borderSkipped: false,
                barPercentage: 0.7,
            }]
        },
        options: {
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
                        boxWidth: 12,
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
const viewAllBtn = document.getElementById('viewAllBtn');
const summaryItems = document.querySelectorAll('.summary-item');
let detailsVisible = false;

viewAllBtn.addEventListener('click', function() {
    detailsVisible = !detailsVisible;
    summaryItems.forEach(item => {
        const detailItem = item.querySelector('.detail-item');

        if (detailsVisible) {
            item.classList.add('expanded');
            detailItem.classList.remove('hidden');
            viewAllBtn.innerHTML = 'View Less <i class="fas fa-chevron-up ml-1"></i>';
        } else {
            item.classList.remove('expanded');
            detailItem.classList.add('hidden');
            viewAllBtn.innerHTML = 'View All <i class="fas fa-chevron-down ml-1"></i>';
        }
    });
});

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
        window.location.href = '../../Backend/LoginSystemBackend/logout.php';
    }
});
});

//

const mainContent = document.getElementById('mainContent');
const footer = document.getElementById('footer');
const logoImg = document.getElementById('logoImg');

document.addEventListener('DOMContentLoaded', function() {

    // Define consistent colors for sectors
    const sectorColors = {
        "School": "#ffce56",
        "Government": "#4729a6",
        "Sponsor": "#63d3e1",
        "Industry": "#ff6384"
    };

    // Line chart data
    const lineChartData = {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [{
            label: 'Total Score',
            data: [85, 87, 88, 86, 89, 90, 91, 92, 93, 94, 95, 87.5],
            borderColor: sectorColors["School"],
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Completed Tasks',
            data: [30, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 35],
            borderColor: sectorColors["Government"],
            backgroundColor: 'rgba(71, 41, 166, 0.2)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Performance Index',
            data: [7.5, 7.8, 8.0, 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 8.7, 8.8, 8.2],
            borderColor: sectorColors["Industry"],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            fill: true,
            tension: 0.4
        }]
    };

    // Create the line chart
    const ctx = document.getElementById('lineChart')?.getContext('2d');
    let lineChart;
    if (ctx) {
        lineChart = new Chart(ctx, {
            type: 'line',
            data: lineChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuad'
                }
            }
        });
    }
    
});

