const mainContent = document.getElementById('mainContent');
const footer = document.getElementById('footer');
const logoImg = document.getElementById('logoImg');

document.addEventListener('DOMContentLoaded', function() {
    
    class Calendar {
        constructor() {
            this.date = new Date();
            this.currentMonth = this.date.getMonth();
            this.currentYear = this.date.getFullYear();
            this.monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            
            // Get DOM elements
            this.monthDisplay = document.getElementById('currentMonth');
            this.calendarGrid = document.getElementById('calendarGrid');
            this.prevButton = document.getElementById('prevMonth');
            this.nextButton = document.getElementById('nextMonth');
            
            // Bind event listeners
            this.prevButton.addEventListener('click', () => this.previousMonth());
            this.nextButton.addEventListener('click', () => this.nextMonth());
            
            this.render();
        }
        
        render() {       
            this.calendarGrid.innerHTML = '';            
            this.monthDisplay.textContent = `${this.monthNames[this.currentMonth]} ${this.currentYear}`;    
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);     
            
            for (let i = 0; i < firstDay.getDay(); i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                this.calendarGrid.appendChild(emptyDay);
            }
            
            for (let day = 1; day <= lastDay.getDate(); day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                if (day === this.date.getDate() && 
                    this.currentMonth === this.date.getMonth() && 
                    this.currentYear === this.date.getFullYear()) {
                    dayElement.classList.add('current-day');
                }
                
                this.calendarGrid.appendChild(dayElement);
            }
        }
        
        previousMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.render();
        }
        
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.render();
        }
    }
    
    // Initialize the calendar
    const calendar = new Calendar();

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

