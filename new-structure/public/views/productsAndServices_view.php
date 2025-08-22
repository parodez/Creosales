<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products and Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />
    <link rel="stylesheet" href="assets/css/productsAndServices_design.css" />

    <script src="assets/js/productsAndServices.js" defer></script>

</head>

<body class="bg-gray-100 min-h-screen p-4">
    <?php require_once __DIR__ . '/partials/header.php' ?>

    <div class="p-6" id="main-content">
        <div id="top-container">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Products and Services
            </h1>
            <h2>
                <a id='btn-black' href="#" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add
                    <i class="fas fa-plus"></i>
                </a>
            </h2>
        </div>

        <!-- Table Container -->
        <div class="table-wrapper tab-content active" id="products">
            <table id="productsTable">
                <thead>
                    <tr>
                        <th scope="col" class="col-id">
                            ID</th>
                        <th scope="col" class="col-item">
                            Item</th>
                        <th scope="col" class="col-desc">
                            Description</th>
                        <th scope="col" class="col-cost">
                            Cost</th>
                        <th scope="col" class="col-srp">
                            SRP</th>
                        <th scope="col" class="col-service">
                            Service</th>
                        <th scope="col" class="col-actions">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- POPUP MODALS -->

    <!-- EDIT -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="popupFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupFormLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="products_id" class="form-label">ID</label>
                            <input type="text" class="form-control" id="products_id" name="products_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="products_item" class="form-label">Name</label>
                            <input type="text" class="form-control" id="products_item" name="products_item" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="products_description"
                                name="products_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_cost" class="form-label">Cost</label>
                            <input type="number" class="form-control" id="products_cost" name="products_cost" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_srp" class="form-label">SRP</label>
                            <input type="number" class="form-control" id="products_srp" name="products_srp" required>
                        </div>
                        <div class="mb-3">
                            <label for="services_id" class="form-label">Service</label>
                            <select class="form-control" id="services_id" name="services_id" required>
                                <!-- <?php foreach ($services as $service): ?>
                                <option value=<?= $service['services_id']; ?>>
                                    <?= htmlspecialchars($service['services_type']); ?>
                                </option>
                                <?php endforeach; ?> -->
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADD -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="popupFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupFormLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="addForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="products_item" class="form-label">Name</label>
                            <input type="text" class="form-control" id="products_item" name="products_item" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="products_description"
                                name="products_description" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_cost" class="form-label">Cost</label>
                            <input type="number" class="form-control" id="products_cost" name="products_cost" required>
                        </div>
                        <div class="mb-3">
                            <label for="products_srp" class="form-label">SRP</label>
                            <input type="number" class="form-control" id="products_srp" name="products_srp" required>
                        </div>
                        <div class="mb-3">
                            <label for="services_id" class="form-label">Service</label>
                            <select class="form-control" id="services_id" name="services_id" required>
                                <option value="" disabled selected>Select a service</option>
                                <?php foreach ($services as $service): ?>
                                <option value=<?= $service['services_id']; ?>>
                                    <?= htmlspecialchars($service['services_type']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/partials/footer.php' ?>
</body>

</html>