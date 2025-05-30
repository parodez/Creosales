<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CREOSALES</title>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />
    <link rel="stylesheet" href="assets/css/dashboard_design.css" />
    <script src="assets/js/sidebar.js" defer></script>
    <script src="assets/js/dashboard.js" defer></script>
    <!-- <script src="assets/js/dashboard_script.js" defer></script> -->
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
                    <h2>Overview Rating of Potential Customers by Sector</h2>
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
        </div>
    </div>
    <div class="footer">
        © 2025 Creosales | Creotec Philippines. All Rights Reserved.
    </div>
    <script>
        const averageRatingBySector = <?php echo $averageRatingBySector; ?>;
        const evaluationResults = <?php echo $evaluationResults_json; ?>;
    </script>
</body>
</html>