<?php
include('Backend/connection.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: Backend/LoginSystemBackend/validate_login.php');
    exit();
}

$user = $_SESSION['user'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null; // Ensure user_type is set


// Fetch user details
$userId = $_SESSION['user_id'];
$userQuery = "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userDetails = $userResult->fetch_assoc();

// Adjust total clients count based on user type
if ($user_type === 0) {
    // Admin: Fetch all clients
    $totalClientsQuery = "SELECT COUNT(*) as total_clients FROM tbl_client";
    $totalClientsResult = mysqli_query($conn, $totalClientsQuery);
    $totalClientsRow = mysqli_fetch_assoc($totalClientsResult);
    $totalClients = $totalClientsRow['total_clients'];
} else {
    // Regular user: Fetch only clients evaluated by the logged-in user
    $totalClientsQuery = "SELECT COUNT(DISTINCT e.client_id) as total_clients 
                          FROM tbl_evaluation e
                          JOIN tbl_client cl ON e.client_id = cl.client_id
                          WHERE cl.user_id = ?";
    $totalClientsStmt = $conn->prepare($totalClientsQuery);
    $totalClientsStmt->bind_param("i", $userId);
    $totalClientsStmt->execute();
    $totalClientsResult = $totalClientsStmt->get_result();
    $totalClientsRow = $totalClientsResult->fetch_assoc();
    $evaluatedClients = $totalClientsRow['total_clients'];
}

// Query to get the average rating by sector
$query = "SELECT s.sector_name, AVG(e.evaluation_rating) as avg_rating 
          FROM tbl_evaluation e
          JOIN tbl_client cl ON e.client_id = cl.client_id
          JOIN tbl_sector s ON cl.sector_id = s.sector_id
          GROUP BY s.sector_name
          ORDER BY s.sector_name";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['sector_name']] = round($row['avg_rating'], 1);
}

$chartData = json_encode($data);

// Query to get the total average rating of all clients
$totalAvgQuery = "SELECT AVG(evaluation_rating) as total_avg_rating FROM tbl_evaluation";
$totalAvgResult = mysqli_query($conn, $totalAvgQuery);
$totalAvgRow = mysqli_fetch_assoc($totalAvgResult);
$totalAvgRating = round($totalAvgRow['total_avg_rating'], 1);

// Query to get the last update date and the user who made the update
$lastUpdateQuery = "SELECT MAX(e.evaluation_date) as last_update, e.client_id, cl.user_id 
                    FROM tbl_evaluation e
                    JOIN tbl_client cl ON e.client_id = cl.client_id";
$lastUpdateResult = mysqli_query($conn, $lastUpdateQuery);
$lastUpdateRow = mysqli_fetch_assoc($lastUpdateResult);
$lastUpdateDate = $lastUpdateRow['last_update'] ? date('M d, Y', strtotime($lastUpdateRow['last_update'])) : 'No updates found';
$lastUpdateUserId = $lastUpdateRow['user_id'];

// Query to get the user details who made the last update
$userQuery = "SELECT user_firstname, user_lastname, user_position 
              FROM tbl_user 
              WHERE user_id = $lastUpdateUserId";
$userResult = mysqli_query($conn, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$lastUpdateUserName = $userRow ? $userRow['user_firstname'] . ' ' . $userRow['user_lastname'] : 'Unknown';
$lastUpdateUserPosition = $userRow ? $userRow['user_position'] : 'Unknown';

// Query to get the number of clients evaluated on the last update date
$clientsEvaluatedQuery = "SELECT COUNT(*) as clients_evaluated 
                          FROM tbl_evaluation 
                          WHERE evaluation_date = '{$lastUpdateRow['last_update']}'";
$clientsEvaluatedResult = mysqli_query($conn, $clientsEvaluatedQuery);
$clientsEvaluatedRow = mysqli_fetch_assoc($clientsEvaluatedResult);
$clientsEvaluatedCount = $clientsEvaluatedRow['clients_evaluated'] ?? 0;

// CLIENTS START
// prpb

// Queries the number of clients per sector
$query = "SELECT s.sector_name, COUNT(cl.client_id) as client_count
          FROM tbl_client cl
          JOIN tbl_sector s ON cl.sector_id = s.sector_id
          GROUP BY s.sector_name
          ORDER BY FIELD(s.sector_name, 'School', 'Government', 'Sponsor', 'Industry')";
$result = mysqli_query($conn, $query);

// Initialize clientData values
$clientData = [
    'School' => 0,
    'Government' => 0,
    'Sponsor' => 0,
    'Industry' => 0
];

// Stores the count of each client per sector
$totalClients = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $clientData[$row['sector_name']] = $row['client_count'];
    $totalClients += $row['client_count'];
}

// CLIENTS END

// EVALUATIONS START
// prpb

// Queries the count of instances of each evaluation_result
$evaluationResultQuery = "SELECT evaluation_result, COUNT(*) as count 
                          FROM tbl_evaluation 
                          GROUP BY evaluation_result";
$evaluationResultResult = mysqli_query($conn, $evaluationResultQuery);

// Initialize evaluationResults values
$evaluationResults = [
    'Conditional' => 0,
    'Passed' => 0,
    'Failed' => 0
];

// Stores the count of each instance per result
while ($row = mysqli_fetch_assoc($evaluationResultResult)) {
    $evaluationResults[$row['evaluation_result']] = $row['count'];
}

// EVALUATIONS END

// Determine overall client result
$overallResult = 'Conditional';
if ($evaluationResults['Passed'] > $evaluationResults['Failed'] && $evaluationResults['Passed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Passed';
} elseif ($evaluationResults['Failed'] > $evaluationResults['Passed'] && $evaluationResults['Failed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Failed';
}

$evaluationCounts = [];

// Fetch the number of clients evaluated by the logged-in user
$evaluationCountQuery = "SELECT s.sector_name, COUNT(e.evaluation_id) as evaluated_count
                         FROM tbl_evaluation e
                         JOIN tbl_client cl ON e.client_id = cl.client_id
                         JOIN tbl_sector s ON cl.sector_id = s.sector_id
                         WHERE cl.user_id = ?
                         GROUP BY s.sector_name";
$evaluationCountStmt = $conn->prepare($evaluationCountQuery);
$evaluationCountStmt->bind_param("i", $_SESSION['user_id']);
$evaluationCountStmt->execute();
$evaluationCountResult = $evaluationCountStmt->get_result();

while ($row = $evaluationCountResult->fetch_assoc()) {
    $evaluationCounts[$row['sector_name']] = $row['evaluated_count'];
}

// Fetch all clients for the logged-in user for additional debugging
$clientQuery = "SELECT * FROM tbl_client WHERE user_id = ?";
$clientStmt = $conn->prepare($clientQuery);
$clientStmt->bind_param("i", $_SESSION['user_id']);
$clientStmt->execute();
$clientResult = $clientStmt->get_result();

// Define consistent colors for sectors
$sectorColors = [
    "School" => "#22758e",
    "Government" => "#4729a6",
    "Sponsor" => "#8c6b70",
    "Industry" => "#832d6d"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CREOSALES</title>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="Frontend/dashboard/dashboard_design.css">
    <link rel="stylesheet" href="assets/css/sidebar.css" />
    <link rel="stylesheet" href="assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <script src="assets/js/sidebar.js" defer></script>
    <script src="Frontend/dashboard/dashboard_script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    * {
        font-family: 'Montserrat', sans-serif;
        font-size: 20px;
    }
</style>

<body>
    <div class="header">
        <div class="header-left">
            <div class="hamburger" id="hamburgerBtn">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="logo-container">
                <img src="assets/images/CreoSales-logo.png" alt="CREOTEC Logo" id="logoImg">
            </div>
            <div class="logo-text" style="font-weight: bold; color: #4729a6; font-size: 25px;">
                <span class="logo-text-1">CREOSALES</span><br>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="profile">
            <h4 style="font-weight:bold"><?php echo $user['user_firstname'] . ' ' . $user['user_lastname']; ?></h4>
            <p style="font-size: small;"><?php echo $user['user_department']; ?></p>
            <p style="font-size: small;"><?php echo $user['user_position']; ?></p>
        </div>
        <ul>
            <li style="font-size: 18px; display: flex; align-items: center;">
                <a href="../evaluation_page.php" style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </div>
                    <span style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                        <?php echo htmlspecialchars($user_type === 0 ? $totalClients : $evaluatedClients); ?>
                    </span>
                </a>
            </li>

            <li style="font-size: 18px;">
                <a href="#" id="logoutBtn">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content" id="mainContent">
        <div class="dashboard-container">
            <div class="summary-admin-grid">
                <div class="summary-section">
                    <h2>Overview Rating of Clients by Sector</h2>
                    <div class="chart-with-activity responsive-row">
                        <div class="chart-container">
                            <canvas id="barChart" width="1000" height="500"></canvas>
                        </div>
                    </div>
                    <div class="view-all-container">
                        <button id="viewAllBtn" class="view-all-btn btn btn-link">
                            View All <i class="fas fa-chevron-down ml-1"></i>
                        </button>
                    </div>
                    <div class="summary-details stats">
                        <div class="summary-item">
                            <h3>Overall Client Rating</h3>
                            <p><?php echo $totalAvgRating ? $totalAvgRating . ' out of 5.0 ⭐' : 'No rating records'; ?></p>
                            <div class="detail-item hidden">
                                <p style="color: <?php echo $sectorColors['School']; ?>">School: <?php echo $data['School'] ?? 'N/A'; ?> Rating</p>
                                <p style="color: <?php echo $sectorColors['Government']; ?>">Government: <?php echo $data['Government'] ?? 'N/A'; ?> Rating</p>
                                <p style="color: <?php echo $sectorColors['Sponsor']; ?>">Sponsor: <?php echo $data['Sponsor'] ?? 'N/A'; ?> Rating</p>
                                <p style="color: <?php echo $sectorColors['Industry']; ?>">Industry: <?php echo $data['Industry'] ?? 'N/A'; ?> Rating</p>
                            </div>
                        </div>
                        <div class="summary-item">
                            <h3>Average Client Result</h3>
                            <p class="<?php echo strtolower($overallResult); ?>"><?php echo $overallResult; ?></p>
                            <div class="detail-item hidden">
                                <p class="text-success">Passed: <?php echo $evaluationResults['Passed'] ?? 0; ?> clients</p>
                                <p class="text-secondary">Conditional: <?php echo $evaluationResults['Conditional'] ?? 0; ?> clients</p>
                                <p class="text-danger">Failed: <?php echo $evaluationResults['Failed'] ?? 0; ?> clients</p>
                            </div>
                        </div>

                        <div class="summary-item">
                            <h3>Last Update</h3>
                            <p><?php echo $lastUpdateDate; ?></p>
                            <div class="detail-item hidden">
                                <p>Updated by: <?php echo $lastUpdateUserName; ?></p>
                                <p>Position: <?php echo $lastUpdateUserPosition; ?></p>
                                <p>Clients Evaluated: <?php echo $clientsEvaluatedCount; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-activity" style="<?php echo $user_type == 0 ? 'display:none;' : ''; ?>">
                    <?php if ($user_type == 0): ?>
                        <h3>Admin Activity</h3>
                    <?php endif; ?>

                    <?php if ($user_type == 1): ?>
                        <h3>User Activity</h3>
                    <?php endif; ?>

                    <?php foreach ($sectorColors as $sector => $color): ?>
                        <div class="activity-item <?php echo strtolower($sector); ?>">
                            <span class="indicator" style="background-color: <?php echo $color; ?>"></span>
                            <span><?php echo $sector; ?> Evaluated: <?php echo $evaluationCounts[$sector] ?? 0; ?> <?php echo ($evaluationCounts[$sector] ?? 0) == 1 ? 'client' : 'clients'; ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="activity-info text-right mt-4">
                        <small><?php echo $userDetails['user_firstname'] . ' ' . $userDetails['user_lastname']; ?></small><br>
                        <small><?php echo $userDetails['user_position']; ?></small>
                    </div>
                </div>
            </div>
            <div class="charts-grid">
                <div class="chart-box wide elegant">
                    <div class="chart-title">Numbers of clients</div>
                    <div class="chart-content responsive-row">
                        <div class="chart-canvas-wrapper">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div class="sector-count">
                            <h3 class="sector-title">Sector</h3>
                            <?php foreach ($clientData as $sector=>$client_count): ?>
                                <div class="sector-item sector-<?php echo strtolower($sector); ?>">
                                    <span><?php echo $sector ?>: <?php echo $client_count; ?> <?php echo $client_count == 1 ? 'client' : 'clients'; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="chart-info">
                        <p class="total-clients">Total Clients: <?php echo $totalClients; ?></p>
                    </div>
                </div>
                <div class="chart-box narrow static-size">
                    <div class="chart-title">Calendar</div>
                    <div class="calendar-container">
                        <div class="calendar">
                            <div class="calendar-header">
                                <button id="prevMonth" class="btn btn-link"><i class="fas fa-chevron-left"></i></button>
                                <span id="currentMonth">February 2025</span>
                                <button id="nextMonth" class="btn btn-link"><i class="fas fa-chevron-right"></i></button>
                            </div>
                            <div class="weekdays">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            <div class="calendar-grid" id="calendarGrid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        © 2025 Creosales | Creotec Philippines. All Rights Reserved.
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = <?php echo $chartData; ?>;
            const ctxBar = document.getElementById('barChart').getContext('2d');
            const sectorColors = {
                "School": "#22758e",
                "Government": "#4729a6",
                "Sponsor": "#8c6b70",
                "Industry": "#832d6d"
            };
            const neonColors = {
                "School": "#22758e",
                "Government": "#4729a6",
                "Sponsor": "#8c6b70",
                "Industry": "#832d6d"
            };
            let barChart;
            if (ctxBar) {
                barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: ["School", "Government", "Sponsor", "Industry"],
                        datasets: [{
                            label: 'Average Overall Rating',
                            data: [
                                chartData['School'] || 0,
                                chartData['Government'] || 0,
                                chartData['Sponsor'] || 0,
                                chartData['Industry'] || 0
                            ],
                            backgroundColor: [
                                sectorColors["School"],
                                sectorColors["Government"],
                                sectorColors["Sponsor"],
                                sectorColors["Industry"]
                            ],
                            borderColor: [
                                sectorColors["School"],
                                sectorColors["Government"],
                                sectorColors["Sponsor"],
                                sectorColors["Industry"]
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
                                max: 5.0,
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

            const ctxPie = document.getElementById('pieChart').getContext('2d');
            let pieChart;
            if (ctxPie) {
                pieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: ["School", "Government", "Sponsor", "Industry"],
                        datasets: [{
                            data: [
                                // prpb
                                chartData['School'] || 0,
                                chartData['Government'] || 0,
                                chartData['Sponsor'] || 0,
                                chartData['Industry'] || 0
                            ],
                            backgroundColor: [
                                sectorColors["School"],
                                sectorColors["Government"],
                                sectorColors["Sponsor"],
                                sectorColors["Industry"]
                            ],
                            borderColor: '#1c1040',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: {
                                left: 10,
                                right: 10,
                                top: 10,
                                bottom: 10
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

                    if (pieChart) pieChart.resize();
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
    </script>
</body>
</html>