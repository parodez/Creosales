<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
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

    <?php require_once __DIR__ . '/partials/header.php' ?>

    <?php require_once __DIR__ . '/partials/sidebar.php' ?>

    <div class="main-content">
        <h2 class="mb-4">Passed Customers Dashboard</h2>

        <!-- Filter Container -->
        <!-- <div class="filter-container">
            <div class="filter-row">
                <div class="filter-group search-box">
                    <label class="filter-label">Search</label>
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search clients..."
                            value="<?php echo $searchTerm; ?>">
                    </div>
                </div>
                <?php if ($user_type == 0): ?>
                <div class="filter-group">
                    <label class="filter-label">Evaluated by</label>
                    <select id="adminFilter" class="form-select" onchange="applyFilters()">
                        <option value="">All Users Evaluated</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['user_id']; ?>"
                            <?php echo ($adminFilter == $user['user_id']) ? 'selected' : ''; ?>>
                            <?php echo $user['full_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="filter-group">
                    <label class="filter-label">Sector</label>
                    <select id="sectorFilter" class="form-select" onchange="applyFilters()">
                        <option value="">All Sectors</option>
                        <?php foreach ($sectors as $sector): ?>
                        <option value="<?php echo $sector; ?>"
                            <?php echo ($sectorFilter == $sector) ? 'selected' : ''; ?>><?php echo $sector; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Performance Review</label>
                    <select id="reviewFilter" class="form-select" onchange="applyFilters()">
                        <option value="">All Reviews</option>
                        <?php foreach ($reviews as $review): ?>
                        <option value="<?php echo $review; ?>"
                            <?php echo ($reviewFilter == $review) ? 'selected' : ''; ?>><?php echo $review; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div> -->

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="flashcard-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">
                                Customer
                                <span
                                    class="sort-icon <?php echo $orderBy == 'potentialcustomer_name' ? ($orderDir == 'ASC' ? 'asc' : 'desc') : ''; ?>"
                                    onclick="sortBy('potentialcustomer_name', '<?php echo $orderBy == 'potentialcustomer_name' && $orderDir == 'ASC' ? 'DESC' : 'ASC'; ?>')">
                                    <i
                                        class="bi <?php echo $orderDir == 'ASC' ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($passedCustomers as $passedCustomer): ?>
                        <tr>
                            <td>
                                <!-- <?php var_dump($potentialCustomer); ?> -->
                                <a href='#' onclick="show_customer_data(<?= $passedCustomer->id ?>)">
                                    <?= $passedCustomer->name ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="information-panel">
                <h1 style="align-items: center;">Customer Information</h1>
                <br>
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Name</h4>
                        <p id="name">Customer Name</p>
                    </div>
                    <div class="info-item">
                        <h4>Sector</h4>
                        <p id="sector">Customer Sector</p>
                    </div>
                    <div class="info-item">
                        <h4>Contact Name</h4>
                        <p id="contact_name">Customer Sector</p>
                    </div>
                    <div class="info-item">
                        <h4>Contact Position</h4>
                        <p id="contact_position">Customer Sector</p>
                    </div>
                    <div class="info-item">
                        <h4>Contact Email</h4>
                        <p id="contact_email">Customer Sector</p>
                    </div>
                    <div class="info-item">
                        <h4>Contact Number</h4>
                        <p id="contact_number">Customer Sector</p>
                    </div>
                    <div class="info-item">
                        <h4>Existing Programs</h4>
                        <p id="programs">
                            Program 1
                            <br>
                            Program 2
                        </p>
                    </div>
                    <div class="info-item">
                        <h4>Existing Services</h4>
                        <p id="services">
                            Service 1
                            <br>
                            Service 2
                        </p>
                    </div>
                    <div class="info-item">
                        <h4>Existing Partners</h4>
                        <p id="partners">
                            Partner 1
                            <br>
                            Partner 2
                        </p>
                    </div>
                    <div class="info-item" style="grid-column: span 3;">
                        <h4>Existing Facilities</h4>
                        <p id='facilities'>
                            Facilities 1
                            <br>
                            Facilities 2
                        </p>
                    </div>
                    <div class="info-item">
                        <h4 id="population">Population: 1000000</h4>
                        <p id="g1">Grade 1: </p>
                        <p id="g2">Grade 2: </p>
                        <p id="g3">Grade 3: </p>
                    </div>
                    <div class="info-item">
                        <p id="g4">Grade 4: </p>
                        <p id="g5">Grade 5: </p>
                        <p id="g6">Grade 6: </p>
                        <p id="g7">Grade 7: </p>
                        <p id="g8">Grade 8: </p>
                    </div>
                    <div class="info-item">
                        <p id="g9">Grade 9: </p>
                        <p id="g10">Grade 10: </p>
                        <p id="g11">Grade 11: </p>
                        <p id="g12">Grade 12: </p>
                        <p id="g13">Grade 13: </p>
                    </div>
                </div>
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
            <a class="export" href="#" id="exportAllClientsBtn"><i class="bi bi-clipboard-data"></i> <span>Export all
                    the client data</span></a>
            <?php endif; ?>
            <?php if ($user_type == 1): ?>
            <a class="export" href="#" id="exportEvaluatedAllClientsBtn"><i class="bi bi-clipboard-data"></i>
                <span>Export all evaluated clients</span></a>
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
                        <a class="page-link" href="<?php echo getPageUrl(min($totalPages, $page + 1)); ?>"
                            aria-label="Next">
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

    <?php require_once __DIR__ . '/partials/footer.php' ?>

    <script>
    <?php
        function getPageUrl($pageNum)
        {
            $params = $_GET;
            $params['page'] = $pageNum;
            return '?' . http_build_query($params);
        }
        ?>
    const passedCustomers_json = <?= json_encode($passedCustomers) ?>
    </script>
</body>

</html>