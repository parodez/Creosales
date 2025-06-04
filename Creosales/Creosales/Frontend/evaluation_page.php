<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

include('../Backend/fetch_data_creosales.php'); // Ensure this is included

// Debugging: Check session data
if (!isset($_SESSION['user']) || !isset($_SESSION['user_type'])) {
    die("Error: User session not found. Session data: " . print_r($_SESSION, true));
}

if (!isset($_SESSION['user_id'])) { // Corrected to check $_SESSION['user_id']
    die("Error: User ID not found in session. Session data: " . print_r($_SESSION, true));
}

$user = $_SESSION['user'];
$user_id = $_SESSION['user_id']; // Corrected to use $_SESSION['user_id']
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation System</title>
    <link rel="icon" href="../assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/sidebar.css" />
    <link rel="stylesheet" href="../assets/css/header_footer.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../assets/js/sidebar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Global override to prioritize Montserrat */
        * {
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: #130a2e;
            display: flex;
            flex-direction: column;
            color: white;
            /* Ensure text color is white */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
        }

        @media screen and (max-width: 768px) {
            .main-content {
                width: 100%;
                margin: 0;
                margin-top: 70px;
            }

            .sidebar.collapsed+.main-content {
                margin-left: 0;
            }

            .filter-container {
                padding: 10px;
            }

            .filter-group {
                min-width: 100%;
            }
        }

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
        }

        .profile h4 {
            margin-top: 10px;
            font-size: 18px;
        }

        .main-content {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            padding-top: 35px;
            transition: margin-left 0.3s, width 0.3s;
            width: calc(100% - 250px);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed+.main-content {
            margin-left: 70px;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            overflow-x: auto;
            /* Enable horizontal scrolling */
        }

        /* Flashcard Style for Table */
        .flashcard-table {
            width: 100%;
            min-width: 800px;
            /* Ensure minimum width to prevent squishing */
            border-collapse: collapse;
            table-layout: fixed;
            background-color: #1c1040;
        }

        .flashcard-table tr {
            transition: background 0.3s;
            background-color: #1c1040;
        }

        .flashcard-table tr:hover {
            background-color: #1c1040;
        }

        .flashcard-table td,
        .flashcard-table th {
            padding: 15px;
            vertical-align: middle;
            word-wrap: break-word;
            /* Ensure word wrap */
            white-space: normal;
            /* Ensure word wrap */
        }

        .flashcard-table th {
            background: #4100BF;
            color: white;
            text-align: left;
            border-bottom: none;
            font-weight: 500;
            position: relative;
            min-width: 150px;
            /* Set minimum width for columns */
            white-space: nowrap;
            /* Prevent text wrapping in headers */
        }

        /* edit icon */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: white;
            transition: transform 0.2s;
        }

        .action-button:hover {
            transform: scale(1.1);
        }

        /* Sort tab styling */
        .filter-container {
            background-color: #1c1040;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            color: white;
            /* Ensure text color is white */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow-x: auto;
            /* Enable horizontal scrolling */
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #eee;
            display: block;
        }

        .search-box {
            flex: 2;
            min-width: 300px;
        }

        /* Custom sort icons */
        .sort-icon {
            cursor: pointer;
            display: inline-block;
            margin-left: 1px;
            vertical-align: middle;
            width: 20px;
            height: 25px;
            position: relative;
            transition: transform 0.2s ease-in-out;
        }

        .sort-icon.asc .bi-arrow-up,
        .sort-icon.desc .bi-arrow-down {
            width: 16px;
            height: 16px;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 40px;
            /* Add spacing to prevent overlap with footer */
            overflow-x: auto;
            /* Enable horizontal scrolling */
            position: relative;
            z-index: 1;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            color: #4100BF;
            border-radius: 4px;
            margin: 0 3px;
        }

        .pagination .page-item.active .page-link {
            background-color: #4100BF;
            border-color: #4100BF;
            color: #eee;
        }

        .page-info {
            color: #eee;
            font-size: 14px;
        }

        .swal2-footer .export-btn {
            background-color: #4100BF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .swal2-footer .export-btn:hover {
            background-color: #5a00e0;
        }

        .swal2-footer .export-btn-all {
            flex: 1 1 100%;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <div class="hamburger" id="hamburgerBtn">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="logo-container">
                <img src="../assets/images/CreoSales-logo.png" alt="CREOTEC Logo" id="logoImg">
            </div>
            <div class="logo-text" style="font-weight: bold; color: #4729a6; font-size: 25px;">
                <span class="logo-text-1">CREOSALES</span><br>
            </div>
        </div>
    </div>


    <div class="sidebar" id="sidebar">
        <div class="profile">
            <h4><?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?></h4>
            <p><?php echo htmlspecialchars($user['user_department']); ?></p>
            <p><?php echo htmlspecialchars($user['user_position']); ?></p>
        </div>
        <ul>
            <li><a href="dashboard/dashboard.php"><i class="bi bi-house"></i> <span>Dashboard</span></a></li>
            <?php if ($user_type == 0): ?>
                <li><a href="#" id="exportAllClientsBtn"><i class="bi bi-clipboard-data"></i> <span>Export all the client data</span></a></li>
            <?php endif; ?>
            <?php if ($user_type == 1): ?>
                <li><a href="#" id="exportEvaluatedAllClientsBtn"><i class="bi bi-clipboard-data"></i> <span>Export all evaluated clients</span></a></li>
            <?php endif; ?>
            <li><a href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <h2 class="mb-4">Evaluation</h2>

        <!-- Filter Container -->
        <div class="filter-container">
            <div class="filter-row">
                <div class="filter-group search-box">
                    <label class="filter-label">Search</label>
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search clients..." value="<?php echo $searchTerm; ?>">
                    </div>
                </div>
                <?php if ($user_type == 0): ?>
                    <div class="filter-group">
                        <label class="filter-label">Evaluated by</label>
                        <select id="adminFilter" class="form-select" onchange="applyFilters()">
                            <option value="">All Users Evaluated</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['user_id']; ?>" <?php echo ($adminFilter == $user['user_id']) ? 'selected' : ''; ?>><?php echo $user['full_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="filter-group">
                    <label class="filter-label">Sector</label>
                    <select id="sectorFilter" class="form-select" onchange="applyFilters()">
                        <option value="">All Sectors</option>
                        <?php foreach ($sectors as $sector): ?>
                            <option value="<?php echo $sector; ?>" <?php echo ($sectorFilter == $sector) ? 'selected' : ''; ?>><?php echo $sector; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Performance Review</label>
                    <select id="reviewFilter" class="form-select" onchange="applyFilters()">
                        <option value="">All Reviews</option>
                        <?php foreach ($reviews as $review): ?>
                            <option value="<?php echo $review; ?>" <?php echo ($reviewFilter == $review) ? 'selected' : ''; ?>><?php echo $review; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="flashcard-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">
                                Client Name
                                <span class="sort-icon <?php echo $orderBy == 'client_name' ? ($orderDir == 'ASC' ? 'asc' : 'desc') : ''; ?>"
                                    onclick="sortBy('client_name', '<?php echo $orderBy == 'client_name' && $orderDir == 'ASC' ? 'DESC' : 'ASC'; ?>')">
                                    <i class="bi <?php echo $orderDir == 'ASC' ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                </span>
                            </th>
                            <th style="width: 30%;">Client Location</th>
                            <th style="width: 15%; text-align: left;">Sector</th>
                            <th style="width: 15%; text-align: center; font-size: small;">Performance Review</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                            <td>{$row['client_name']}</td>
                                            <td>{$row['client_location']}</td>
                                            <td style='text-align: left;'>{$row['sector_name']}</td>
                                            <td style='text-align: center; color:"
                                    . (strtolower($row['evaluation_result']) == 'passed' ? 'green' : (strtolower($row['evaluation_result']) == 'failed' ? 'red' : 'white'))
                                    . ";'>
                                                {$row['evaluation_result']}
                                            </td>
                                            <td>
                                                <div class='action-buttons'>
                                                    <a href='evaluation_profile.php?id={$row['client_id']}' class='action-button edit-button' title='Edit'>
                                                        <i class='bi bi-pencil' style='color: white;'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <div class="page-info">
                Showing <?php echo ($totalRecords > 0) ? min($offset + 1, $totalRecords) : 0; ?> to
                <?php echo ($totalRecords > 0) ? min($offset + $recordsPerPage, $totalRecords) : 0; ?> of
                <?php echo $totalRecords; ?> entries
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- First Page -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo getPageUrl(1); ?>" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>

                    <!-- Previous Page -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo getPageUrl(max(1, $page - 1)); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($startPage + 4, $totalPages);

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo getPageUrl($i); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page -->
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo getPageUrl(min($totalPages, $page + 1)); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>

                    <!-- Last Page -->
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo getPageUrl($totalPages); ?>" aria-label="Last">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>
    <div class="footer">
        © 2025 Creosales | Creotec Philippines. All Rights Reserved.
    </div>

    <script>
        // Helper function to create the page URL
        <?php
        function getPageUrl($pageNum)
        {
            $params = $_GET;
            $params['page'] = $pageNum;
            return '?' . http_build_query($params);
        }
        ?>

        // Function to handle sorting
        function sortBy(column, direction) {
            updateUrlParams('orderBy', column);
            updateUrlParams('orderDir', direction);
            window.location.reload();
        }

        // Function to apply filters using AJAX
        function applyFilters() {
            const sector = document.getElementById('sectorFilter').value;
            const review = document.getElementById('reviewFilter').value;
            const search = document.getElementById('searchInput').value;
            const admin = document.getElementById('adminFilter') ? document.getElementById('adminFilter').value : '';

            const params = new URLSearchParams({
                sector: sector,
                review: review,
                search: search,
                admin: admin,
                page: 1 // Reset to first page when applying filters
            });

            fetch('evaluation_page.php?' + params.toString())
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    document.querySelector('.table-container').innerHTML = doc.querySelector('.table-container').innerHTML;
                    document.querySelector('.pagination-container').innerHTML = doc.querySelector('.pagination-container').innerHTML;
                })
                .catch(error => console.error('Error:', error));
        }

        // Debounce function to limit the rate at which a function can fire
        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        // Event listener for search input (real-time search with debounce)
        document.getElementById('searchInput').addEventListener('input', debounce(function() {
            applyFilters();
        }, 500));

        // Function to update URL parameters
        function updateUrlParams(key, value) {
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
            window.history.replaceState({}, '', url);
        }

        // Event listener for search input (press Enter key)
        document.getElementById('searchInput').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                applyFilters();
            }
        });

        // Add event listener for export button for admin
        const exportAllClientsBtn = document.getElementById('exportAllClientsBtn');
        if (exportAllClientsBtn) {
            exportAllClientsBtn.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Export Data',
                    text: 'Do you want to export all client data to Excel?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, export it!',
                    cancelButtonText: 'No, cancel',
                    confirmButtonColor: '#90EE90',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../Backend/ExportSystemBackend/export_all_clients.php';
                    }
                });
            });
        }

        // Add event listener for export button for user
        const exportEvaluatedAllClientsBtn = document.getElementById('exportEvaluatedAllClientsBtn');
        if (exportEvaluatedAllClientsBtn) {
            exportEvaluatedAllClientsBtn.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Export Data',
                    text: 'Select the sector to export evaluated client data to Excel:',
                    icon: 'question',
                    showConfirmButton: false,
                    showCloseButton: true, // Add close button
                    footer: '<div style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 10px;">' +
                        '<button id="exportIndustryBtn" class="swal2-styled export-btn">Industry</button>' +
                        '<button id="exportSchoolBtn" class="swal2-styled export-btn">School</button>' +
                        '<button id="exportGovernmentBtn" class="swal2-styled export-btn">Government</button>' +
                        '<button id="exportSponsorBtn" class="swal2-styled export-btn">Sponsor</button>' +
                        '<button id="exportAllBtn" class="swal2-styled export-btn export-btn-all">Export All Sectors</button>' +
                        '</div>'
                });

                document.getElementById('exportAllBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to export all evaluated client data to Excel?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Backend/ExportSystemBackend/user_export_all.php';
                        }
                    });
                });

                document.getElementById('exportSchoolBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to export evaluated client data for School to Excel?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Backend/ExportSystemBackend/user_export_school.php';
                        }
                    });
                });

                document.getElementById('exportGovernmentBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to export evaluated client data for Government to Excel?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Backend/ExportSystemBackend/user_export_government.php';
                        }
                    });
                });

                document.getElementById('exportSponsorBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to export evaluated client data for Sponsor to Excel?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Backend/ExportSystemBackend/user_export_sponsor.php';
                        }
                    });
                });

                document.getElementById('exportIndustryBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to export evaluated client data for Industry to Excel?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, export it!',
                        cancelButtonText: 'No, cancel',
                        confirmButtonColor: '#90EE90',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Backend/ExportSystemBackend/user_export_industry.php';
                        }
                    });
                });
            });
        }

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
                    window.location.href = '../Backend/LoginSystemBackend/logout.php';
                }
            });
        });
    </script>
    <style>
        .custom-logout-popup .swal2-popup {
            background-color: #1c1040;
            color: white;
            border-radius: 10px;
        }

        .custom-logout-popup .swal2-title {
            color: #e77373;
        }

        .custom-logout-popup .swal2-confirm {
            background-color: #e77373;
            color: white;
        }

        .custom-logout-popup .swal2-cancel {
            background-color: #6c757d;
            color: white;
        }
    </style>
</body>

</html>