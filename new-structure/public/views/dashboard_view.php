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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <script src="assets/js/sidebar.js" defer></script>
    <link rel="stylesheet" href="assets/css/dashboard_design.css" />
    <script src="assets/js/dashboard.js" defer></script>
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />

</head>
<style>
* {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
}
</style>

<body>

    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <?php require_once __DIR__ . '/partials/sidebar.php'; ?>

    <section class="main-content" id="mainContent">
        <div class="dashboard-container">
            <h2>Overview Rating of Potential Customers by Sector</h2>
            <div class="chart-with-activity responsive-row">
                <div class="chart-container">
                    <canvas id="barChart" height="500"></canvas>
                </div>
                <div class="summary-details">
                    <div class="summary-item">
                        <h3>Number of Potential Customers</h3>
                        <p style="color: #EDFF00;">School: <?php echo $clientsPerSector['School'] ?? 'N/A'; ?></p>
                        <p style="color: #60FFA0">Government: <?php echo $clientsPerSector['Government'] ?? 'N/A'; ?>
                        </p>
                        <p style="color: #9F6BA0">Sponsor: <?php echo $clientsPerSector['Sponsor'] ?? 'N/A'; ?></p>
                        <p style="color: #2274A5">Industry: <?php echo $clientsPerSector['Industry'] ?? 'N/A'; ?></p>
                    </div>
                    <div class="summary-item">
                        <h3>Average Client Result</h3>
                        <p class="<?php echo strtolower($overallResult); ?>"><?php echo $overallResult; ?></p>
                    </div>
                    <div class="summary-item">
                        <h3>Last Update</h3>
                        <p><?php echo $latestEvaluation['date']; ?></p>
                        <p>By: <?php echo $latestEvaluation['user'] ?></p>
                        <p>Position: <?php echo $latestEvaluation['user_position'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script>
    const evaluationResults = <?php echo $evaluationResults_json; ?>;

    // TODO REMOVE ONCE DONE DEBUGGING
    const sessionData = <?php echo $sessionData; ?>
    </script>

</body>

</html>