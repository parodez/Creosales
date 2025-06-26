<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="icon" href="assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="assets/js/sidebar.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/evaluations_profile_design.css" />
    <script src="assets/js/evaluations_profile.js" defer></script>
</head>

<body>

    <?php require_once __DIR__ . '/partials/header.php' ?>
    <?php require_once __DIR__ . '/partials/sidebar.php' ?>

    <!-- <div class="header">
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
    </div> -->

    <!-- <div class="sidebar" id="sidebar">
        <div class="profile">
            <h4 style="font-weight:bold">
                <?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?></h4>
            <p style="font-size: small;"><?php echo htmlspecialchars($user['user_department']); ?></p>
            <p style="font-size: small;"><?php echo htmlspecialchars($user['user_position']); ?></p>
        </div>
        <ul>

            <li style="font-size: 18px; display: flex; align-items: center;">
                <a href="evaluation_page.php"
                    style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </div>
                    <span
                        style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                        <?php echo htmlspecialchars($currentUserType === 0 ? $totalClients : $evaluatedClients); ?>
                    </span>
                </a>
            </li>
            <li style=" font-size: 18px;"><a href="dashboard/dashboard.php"><i class="bi bi-house"></i>
                    <span>Dashboard</span></a></li>
            <li style="font-size: 18px;"><a href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div> -->

    <div class="main-content">
        <div class="content-container">
            <div class="profile-container">
                <div class="profile-header">
                    <h2><?php echo htmlspecialchars($potentialCustomer->name); ?></h2>
                    <p><?php echo htmlspecialchars($potentialCustomer->sector); ?></p>
                </div>
                <div id="evaluation-info-box" class="profile-header" style="display: none;">
                    <!-- Image Carousel -->
                    <div class="carousel-container mb-4">
                        <div id="imageCarousel" class="position-relative">
                            <div class="image-wrapper" onclick="openImageModal()">
                                <div class="no-image">
                                    <img id="currentImage" src="" alt="No Image Found"
                                        class="w-100 h-100 object-fit-cover rounded">
                                </div>
                            </div>
                            <button class="carousel-button left">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-button right">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="mt-2 text-secondary">
                            <p id="imageName" class="fw-bold mb-1">No image name</p>
                            <p id="imageDate" class="small">No date available</p>
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <div id="imageModal" class="modal">
                        <span class="close" onclick="closeImageModal()">&times;</span>
                        <div class="modal-content" style="background-color: transparent;">
                            <!-- Modified modal image container -->
                            <div class="img-container">
                                <img id="modalImage" class="w-100 h-auto rounded" alt="No Image Found">
                            </div>
                            <div id="modalCaption" style="background-color: transparent; color: white;">

                            </div>
                            <div class="slider-nav" style="background-color: transparent;">
                                <button class="carousel-button left">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="carousel-button right">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="rounded p-3 mt-3" style="background-color: transparent; color: white;">
                                <h3 class="fs-5 fw-bold mb-3">Evaluation Result</h3>
                                <div class="row g-2" style="background-color: transparent; color: white;">
                                    <div class="col-6">
                                        <div class="rounded p-2 text-center"
                                            style="background-color: transparent; color: white;">
                                            <p class="mb-1" style="color: white;">Rating</p>
                                            <p id="modalRating" style="color: white;">No rating found</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="rounded p-2 text-center"
                                            style="background-color: transparent; color: white;">
                                            <p class="mb-1" style="color: white;">Result</p>
                                            <p id="modalResult" style="color: white;">No result found</p>
                                        </div>
                                    </div>
                                </div>
                                <p id="modalEvalDate" class="small text-center mt-2"
                                    style="background-color: transparent; color: white;">
                                    Date: Not available
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Summary -->
                    <div class="bg-light rounded p-3">
                        <h3 class="fs-5 fw-bold mb-3">Evaluation Result</h3>
                        <form id="evaluationForm" method="POST">
                            <input type="hidden" name="evaluation_id"
                                value="<?php echo htmlspecialchars($eval_summary['evaluation_id'] ?? ''); ?>">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="bg-white rounded p-2 text-center">
                                        <p class="text-secondary mb-1">Rating</p>
                                        <?php if ($eval_summary): ?>
                                        <select id="ratingSelect" name="rating" class="form-select">
                                            <option value="">Select Rating</option>
                                            <?php
                                                for ($i = 1.0; $i <= 5.0; $i += 0.1) {
                                                    $formatted = number_format($i, 1);
                                                    $selected = ($eval_summary['evaluation_rating'] == $formatted) ? 'selected' : '';
                                                    echo "<option value='$formatted' $selected>$formatted</option>";
                                                }
                                                ?>
                                        </select>
                                        <?php else: ?>
                                        <p class="text-secondary">No rating found</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white rounded p-2 text-center">
                                        <p class="text-secondary mb-1">Result</p>
                                        <?php if ($eval_summary): ?>
                                        <div class="result-slider">
                                            <div class="result-options">
                                                <div class="result-option fail" data-value="Failed">
                                                    <input type="radio" name="result" value="Failed"
                                                        <?php echo ($eval_summary['evaluation_result'] == 'Failed') ? 'checked' : ''; ?>>
                                                    <span>Failed</span>
                                                </div>
                                                <div class="result-option conditional" data-value="Conditional">
                                                    <input type="radio" name="result" value="Conditional"
                                                        <?php echo ($eval_summary['evaluation_result'] == 'Conditional') ? 'checked' : ''; ?>>
                                                    <span>Conditional</span>
                                                </div>
                                                <div class="result-option pass" data-value="Passed">
                                                    <input type="radio" name="result" value="Passed"
                                                        <?php echo ($eval_summary['evaluation_result'] == 'Passed') ? 'checked' : ''; ?>>
                                                    <span>Passed</span>
                                                </div>
                                            </div>
                                            <div class="slider-nav">
                                                <button type="button" class="prev-result"> </button>
                                                <button type="button" class="next-result"> </button>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <p class="text-secondary">No result found</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <p id="evalDate" class="text-secondary small text-center mt-2">
                                Date: <?php echo $eval_summary['evaluation_date'] ?? 'Not available'; ?>
                            </p>
                            <input type="hidden" name="evaluation_id"
                                value="<?php echo $eval_summary['evaluation_id']; ?>">
                            <div class="text-end mt-3">
                                <?php if ($eval_summary): ?>
                                <?php else: ?>
                                <button type="button" class="btn btn-secondary" disabled>No Data</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-container">
                <div class="tabs">
                    <button class="tab active" data-tab="profile">Profile</button>
                    <button class="tab" data-tab="evaluation">Evaluation</button>
                </div>

                <div class="tab-content active" id="profile">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div id="nameField">
                                <?php echo htmlspecialchars($potentialCustomer->name ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Sector Name</div>
                            <div id="sectorNameField">
                                <?php echo htmlspecialchars($potentialCustomer->sector ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div id="locationField">
                                <?php echo htmlspecialchars($potentialCustomer->location->fullAddress ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Region</div>
                            <div>
                                <?php echo htmlspecialchars($potentialCustomer->location->region['name'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Region Description</div>
                            <div>
                                <?php echo htmlspecialchars($potentialCustomer->location->region['description'] ?? 'N/A'); ?>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: 20px; border-color: #ddd;">
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" id="moreDetailsBtn">View Contact Details</button>
                        <button type="button" class="btn btn-primary" id="editProfileBtn">Edit</button>
                        <button type="button" class="btn btn-success" id="saveProfileBtn"
                            style="display: none;">Save</button>
                        <button type="button" class="btn btn-secondary" id="cancelEditBtn"
                            style="display: none;">Cancel</button>
                    </div>
                    <!-- Modal for More Details -->
                    <div id="moreDetailsModal" class="modal">
                        <div class="modal-content-contact"
                            style="position: relative; padding: 20px; background-color: #4100BF; color: white; border-radius: 10px; overflow-x: auto;">
                            <span class="close" onclick="closeMoreDetailsModal()"
                                style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
                            <h2 style="margin-top: 0;">Additional Details</h2>
                            <table id="contactTable" class="table table-bordered" style="color: white;">
                                <thead>
                                    <tr>
                                        <th style="background-color: #4100BF; color: white;">Position</th>
                                        <th style="background-color: #4100BF; color: white;">Contact Person</th>
                                        <th style="background-color: #4100BF; color: white;">Email</th>
                                        <th style="background-color: #4100BF; color: white;">Contact Number</th>
                                        <th style="background-color: #4100BF; color: white;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>n/a</td>
                                        <td>n/a</td>
                                        <td>n/a</td>
                                        <td>n/a</td>
                                        <td>n/a</td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-sm btn-secondary contact-add-btn">Add</button>
                        </div>
                    </div>
                    <!-- Add Contact Modal -->
                    <div id="addContactModal" class="modal">
                        <div class="modal-content-contact"
                            style="position: relative; padding: 20px; background-color: #4100BF; color: white; max-width: 400px; border-radius: 10px;">
                            <span class="close" onclick="closeAddContactModal()"
                                style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
                            <h2 style="margin-top: 0;">Add Contact</h2>
                            <form id="addContactForm">
                                <div class="mb-2">
                                    <label for="addPosition" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="addPosition"
                                        name="contactperson_position" required>
                                </div>
                                <div class="mb-2">
                                    <label for="addName" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" id="addName" name="contactperson_name"
                                        required>
                                </div>
                                <div class="mb-2">
                                    <label for="addEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="addEmail" name="contactperson_email"
                                        required>
                                </div>
                                <div class="mb-2">
                                    <label for="addNumber" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="addNumber" name="contactperson_number"
                                        required>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-success">Add</button>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="closeAddContactModal()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script>
                    </script>
                </div>

                <div class="tab-content" id="evaluation">
                    <form id="evaluationForm" method="POST">
                        <div class="card-body">
                            <?php if (!empty($evaluations)) : ?>
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Rating</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluations as $evaluation) : ?>
                                    <tr>
                                        <td
                                            onclick="openCriteriaModal('<?php echo htmlspecialchars($evaluation['criteria_criterion']); ?>')">
                                            <?php echo htmlspecialchars($evaluation['criteria_criterion'] ?? 'N/A'); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($evaluation['rating_score'] ?? 'N/A'); ?></td>
                                        <td>
                                            <textarea name="rating_notes[<?php echo $evaluation['rating_id']; ?>]"
                                                class="form-control notes-textarea" rows="3"><?php
                                                                                                        echo !empty($evaluation['rating_notes']) ?
                                                                                                            htmlspecialchars($evaluation['rating_notes']) : 'N/A';
                                                                                                        ?></textarea>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <button type="button" class="btn btn-primary save-btn"
                                    onclick="saveAllChanges()">Save</button>
                            </div>
                            <?php else : ?>
                            <p class="text-center">No Evaluation Data Found.</p>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">© 2025 Creosales | Creotec Philippines. All Rights Reserved.</div>

    <script>
    </script>
    <!-- Modal Structure -->
    <div id="criteriaModal" class="modal">
        <div class="modal-content" style="position: relative; padding: 20px; background-color: #4100BF; color: white;">
            <span class="close" onclick="closeCriteriaModal()"
                style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
            <h2 id="criteriaDetails" style="margin-top: 0;"></h2>
            <table id="subcriteriaTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: #4100bf; color: #eee;">Subcriteria</th>
                        <th style="background-color: #4100bf; color: #eee;">Rating</th>
                    </tr>
                </thead>
                <tbody id="subcriteriaDetails">
                </tbody>
            </table>
        </div>
    </div>
    <script>
    </script>
    <style>
    </style>
    <script>
    </script>
    <div id="changesModal" class="modal">
        <div class="modal-content" style="position: relative; padding: 20px; background-color: #4100BF; color: white;">
            <span class="close" onclick="closeChangesModal()"
                style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
            <h2 style="margin-top: 0;">Changes Summary</h2>
            <ul id="changesList" style="list-style: none; padding: 0; margin: 0;">
            </ul>
            <div class="text-end mt-3">
                <button type="button" class="btn btn-success" onclick="confirmChanges()">Confirm</button>
                <button type="button" class="btn btn-secondary" onclick="closeChangesModal()">Cancel</button>
            </div>
        </div>
    </div>
    <script>
    const clientId = <?php echo $potentialCustomer->id ?>;
    const imagesData = <?php echo json_encode($imagesData) ?>;
    const evalSummary = <?php echo json_encode($eval_summary) ?>;
    </script>
</body>

</html>