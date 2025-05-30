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

