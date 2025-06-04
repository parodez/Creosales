<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="assets/js/sidebar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />
    <link rel="stylesheet" href="assets/css/passedCustomers_design.css" />
    <script src="assets/js/passedCustomers.js" defer></script>
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
                <img src="assets/images/CreoSales-logo.png" alt="CREOTEC Logo" id="logoImg">
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
            <li><a href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <h2 class="mb-4">Passed Customers Dashboard</h2>

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
                            <th style="width: 20%;">
                                Customer
                                <span class="sort-icon <?php echo $orderBy == 'potentialcustomer_name' ? ($orderDir == 'ASC' ? 'asc' : 'desc') : ''; ?>"
                                    onclick="sortBy('potentialcustomer_name', '<?php echo $orderBy == 'potentialcustomer_name' && $orderDir == 'ASC' ? 'DESC' : 'ASC'; ?>')">
                                    <i class="bi <?php echo $orderDir == 'ASC' ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                </span>
                            </th>
                            <th style="width: 8%; text-align: center;">
                                Population
                                <br>Count
                            </th>
                            <th style="width: 13%; text-align: center;">
                                Programs
                            </th>
                            <th style="width: 10%; text-align: center;">
                                Services
                            </th>
                            <th style="width: 10%; text-align: center;">
                                Partners
                            </th>
                            <th style="width: 10%; text-align: center;">
                                Facilities
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($passedCustomerData as $passedCustomer): ?>
                            <tr>
                                <td><?= $passedCustomer['name']?></td>
                                <td style="text-align: center;"><?= $passedCustomer['population']?></td>
                                <td style="text-align: center;"><?= $passedCustomer['programs']?></td>
                                <td style="text-align: center;"><?= $passedCustomer['services']?></td>
                                <td style="text-align: center;"><?= $passedCustomer['partners']?></td>
                                <td style="text-align: center;"><?= $passedCustomer['facilities']?></td>
                            </tr>
                        <?php endforeach?>
                        <!-- <?php
                        if ($passedCustomerData && $passedCustomerData->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                            <td>{$row['potentialcustomer_name']}</td>
                                            <td>{$row['potentialcustomer_location']}</td>
                                            <td style='text-align: left;'>{$row['sector_name']}</td>
                                            <td style='text-align: center; color:"
                                    . (strtolower($row['evaluation_result']) == 'passed' ? 'green' : (strtolower($row['evaluation_result']) == 'failed' ? 'red' : 'white'))
                                    . ";'>
                                                {$row['evaluation_result']}
                                            </td>
                                            <td>
                                                <div class='action-buttons'>
                                                    <a href='evaluation_profile.php?id={$row['potentialcustomer_id']}' class='action-button edit-button' title='Edit'>
                                                        <i class='bi bi-pencil' style='color: white;'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                        }
                        ?> -->
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
            
            <?php if ($user_type == 0): ?>
                <a class="export" href="#" id="exportAllClientsBtn"><i class="bi bi-clipboard-data"></i> <span>Export all the client data</span></a>
            <?php endif; ?>
            <?php if ($user_type == 1): ?>
                <a class="export" href="#" id="exportEvaluatedAllClientsBtn"><i class="bi bi-clipboard-data"></i> <span>Export all evaluated clients</span></a>
            <?php endif; ?>

            <!-- <button>
                Export All Evaluated Clients
            </button> -->

            <nav aria-label="Page navigation" class="pagination-box">
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
        <?php
        function getPageUrl($pageNum)
        {
            $params = $_GET;
            $params['page'] = $pageNum;
            return '?' . http_build_query($params);
        }
        ?>
    </script>
</body>

</html>