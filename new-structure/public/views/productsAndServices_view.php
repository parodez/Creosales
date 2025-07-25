<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabbed Table Interface</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" /> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />

    <style>
    table {
        width: 100%;
        table-layout: fixed;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: left;
    }

    thead {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: rgb(202, 192, 192);
    }

    tbody tr:hover {
        background-color: #e6f7ff;
    }

    .col-item {
        width: 4%;
    }

    .col-item {
        width: 23%;
    }

    .col-desc {
        width: 27%;
    }

    .col-cost {
        width: 8%;
    }

    .col-srp {
        width: 8%;
    }

    .col-service {
        width: 15%;
    }

    .col-actions {
        width: 15%;
    }

    .table-wrapper {
        height: 300px;
        /* limit height */
        overflow-y: auto;
        /* scroll vertically if overflow */
        border: 1px solid #ccc;
        /* optional: to show edge */
    }

    .table-wrapper thead th {
        position: sticky;
        top: 0;
        background: #f9f9f9;
        z-index: 1;
    }

    td,
    th {
        height: 40px;
        /* fixed row height */
        padding: 8px;
        text-align: left;
    }
    </style>
</head>

<body class="bg-gray-100 min-h-screen p-4">
    <?php require_once __DIR__ . '/partials/header.php' ?>

    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden mx-auto" style="margin-top:50px">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Products and Services</h1>

            <!-- Table Container -->
            <div class="table-wrapper tab-content active" id="robots">
                <!-- Customer Data Table (Initially visible) -->
                <table id="robotsTable">
                    <thead class="bg-gray-50">
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
                        <?php foreach ($products as $product): ?>
                        <tr data-id="<?= $product['products_id']; ?>" data-item="<?= $product['products_item']; ?>"
                            data-description="<?= $product['products_description']; ?>"
                            data-cost="<?= $product['products_cost']; ?>" data-srp="<?= $product['products_srp']; ?>"
                            data-service="<?= $product['services_id'] ?>">
                            <th scope="row"><?= htmlspecialchars($product['products_id']); ?></th>
                            <th><?= htmlspecialchars($product['products_item']); ?></th>
                            <td><?= htmlspecialchars($product['products_description']); ?></td>
                            <td><?= htmlspecialchars($product['products_cost']); ?></td>
                            <td><?= htmlspecialchars($product['products_srp']); ?></td>
                            <td><?= htmlspecialchars($product['services_type']); ?></td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal" class="edit-btn">
                                    Edit</a>
                                <a href="#" data-id="<?= $product['products_id']; ?>"class="delete-btn">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal">Add Product</a>
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

    <script>
    //EDIT
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            document.getElementById('products_id').value = row.dataset.id;
            document.getElementById('products_item').value = row.dataset.item;
            document.getElementById('products_description').value = row.dataset.description;
            document.getElementById('products_cost').value = row.dataset.cost;
            document.getElementById('products_srp').value = row.dataset.srp;
            document.getElementById('services_id').value = row.dataset.service;
        });
    });

    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent default form submit

        const form = document.getElementById('editForm');
        const data = Object.fromEntries(new FormData(form).entries());

        try {
            const response = await fetch(
                'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
            const result = await response.json();
            console.log('Parsed Response: ', result);
            if (result.success === true) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                this.reset();
                location.reload();
            } else throw new Error(result.message);
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    });

    //ADD 
    document.getElementById('addForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = new FormData(this);

        try {
            const response = await fetch(
                'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                    method: 'POST',
                    body: data
                });
            
            const result = await response.json();
            console.log('Parsed Response: ', result);
            if (result.success === true) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                modal.hide();
                this.reset();
                location.reload();
            } else throw new Error(result.message);
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    });

    //DELETE
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', async function (e) {
                e.preventDefault();

                const products_id = {products_id: this.dataset.id};

                if (!confirm(`Are you sure you want to delete product with product ID ${products_id['products_id']}?`)) return;

                try {
                    const response = await fetch(
                        'http://localhost:8080/Creosales/new-structure/backend/api/product/', {
                            method: 'DELETE',
                            body: JSON.stringify(products_id)
                    });

                    const result = await response.json();

                    if (result.success === true) {
                        location.reload();
                    }
                else throw new Error(result.message);
                } catch (error) {
                    console.error('Error: ', error);
                    alert('An error ocurred while processing your request.');
                }
            })
        })
    })
    </script>
</body>

</html>