<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include '../Backend/fetch_data_creosales.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../Backend/LoginSystemBackend/validate_login.php');
    exit();
}


$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

if ($userType === 0) {
    $totalClientsQuery = "SELECT COUNT(*) as total_clients FROM tbl_client";
    $totalClientsResult = mysqli_query($conn, $totalClientsQuery);
    $totalClientsRow = mysqli_fetch_assoc($totalClientsResult);
    $totalClients = $totalClientsRow['total_clients'];
} else {
    $evaluatedClientsQuery = "SELECT COUNT(DISTINCT e.client_id) as evaluated_clients 
                              FROM tbl_evaluation e
                              JOIN tbl_client cl ON e.client_id = cl.client_id
                              WHERE cl.user_id = ?";
    $stmt = $conn->prepare($evaluatedClientsQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $evaluatedClients = $row['evaluated_clients'];
}


$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="icon" href="../assets/images/CreoSales-logo.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/sidebar.css" />
    <link rel="stylesheet" href="../assets/css/header_footer.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="../assets/js/sidebar.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color:  #130a2e;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Profile */
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

        /* New Tab Styles */
        .tab-container {
            background: #4100BF;
            color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 30px;
            flex: 2;
            max-width: auto;
            overflow-x: auto;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            color: #eee;
            position: relative;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }


        .tab.active {
            color:  #eee;
            font-weight: bold;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #1c1040;
        }

        .tab-content {
            color: #eee;
            display: none;
        }

        .tab-content.active {
            color: #eee;
            display: block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #eee;
            margin-bottom: 5px;
        }

        .main-content {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            transition: margin-left 0.3s, width 0.3s;
            width: calc(100% - 250px);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .content-container {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        /* New styles for profile header */

        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: auto;
        }

        .profile-header {
            background-color: #4100BF;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            flex: 1;
            max-width: 350px;
            text-align: center;
            overflow-x: auto;
        }

        .profile-header h2 {
            margin: 0;
            color: #eee;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .profile-header p {
            margin: 0;
            color: #eee;
            font-size: 16px;
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: auto;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: auto;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: auto;
        }
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: auto;
        }
        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: auto;
        }

        .notes-textarea {
            color: black;
        }

        .notes-textarea.gray-out {
            color: gray;
            font-style: italic;
        }

        .export-btn {
            background-color: #90EE90;
            border-color: #90EE90;
            color: black;
        }

        .export-btn:hover {
            background-color: #76c776;
            border-color: #76c776;
        }

        .save-btn {
            background-color: rgb(231, 115, 115);
            border-color: rgb(231, 115, 115);
            color: black;

        }

        .save-btn:hover {
            background-color: rgb(231, 115, 115);
            border-color: rgb(231, 115, 115);
        }

        /* image and result design */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
        }

        .carousel-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-button {
            position: absolute;
            top: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .carousel-button:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .carousel-button.left {
            left: 10px;
        }

        .carousel-button.right {
            right: 10px;
        }

        #evaluation-info-box {
            width: 100%;
            margin-top: 10px;
            background: #4100BF;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .result-slider {
            position: relative;
            overflow: hidden;
            padding: 10px 0;
        }

        .result-options {
            display: flex;
            transition: transform 0.3s ease;
            width: 300%;
        }

        .result-option {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .result-option input[type="radio"] {
            display: none;
        }

        .result-option span {
            font-weight: bold;
        }

        .result-option.conditional {
            background-color: #f0f0f0;
            color: #666;
        }

        .result-option.fail {
            background-color: #ffe6e6;
            color: #dc3545;
        }

        .result-option.pass {
            background-color: #e6ffe6;
            color: #28a745;
        }

        .result-option.selected {
            transform: scale(1.05);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .slider-nav {
            position: absolute;
            width: 100%;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            pointer-events: none;
        }

        .slider-nav button {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            color: white;
            padding-top: 24px;
            padding-bottom: 24px;
            padding-left: 9px;
            padding-right: 9px;
            cursor: pointer;
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .result-option {
                padding: 8px;
                font-size: 14px;
            }

            .slider-nav button {
                padding: 3px 8px;
            }
        }
        .border-element {
            border: 2px;
            padding: 5px;         
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 30px;
            padding-bottom: 30px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px; /* fixed max width */
            max-height: 90vh; /* fixed max height relative to viewport */
        }

        .modal-content, #modalCaption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }
        .modal-content-contact {
            margin: auto;
            display: block;
            width: 80%;
            max-height: 90vh; /* fixed max height relative to viewport */
        }

        .modal-content-contact, #modalCaption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {transform: scale(0)}
            to {transform: scale(1)}
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        #modalCaption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }


        /* Image wrapper to fix image sizes */
        .image-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Add styles for mini images carousel */
        .mini-images-carousel {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }
        .mini-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        .mini-image:hover {
            border-color: #4100BF;
        }
        .custom-table {
            background-color: #4100BF;
            color: white;
        }
        .custom-table th,
        .custom-table td {
            background-color: transparent;
            color: white;
        }
        
        /* Add background color to rows in the subcriteria table */
        #subcriteriaTable tbody tr,
        #subcriteriaTable tbody tr td {
            background-color: #4100BF;
            color: white;
        }
        #contactTable tbody tr th,
        #contactTable tbody tr td {
            background-color: #4100BF;
            color: white;
        }

        @media (max-width: 1120px) {
            .content-container {
                flex-direction: column;
                padding: 10px; 
            }
            .tab-container {
                width: 100%;
            }
            .profile-container {
                width: 100%;
                max-width: none;
            }
            .main-content {
                width: 100%;
            }
        }

        .carousel-container .no-image {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            font-size: 18px;
            font-weight: bold;
        }

        /* Modified .img-container */
        .img-container {
            width: auto;
            height: 400px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto; 
        }

        /* Add rule to ensure images in .img-container are fully visible */
        .img-container img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
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
            <h4 style="font-weight:bold"><?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?></h4>
            <p style="font-size: small;"><?php echo htmlspecialchars($user['user_department']); ?></p>
            <p style="font-size: small;"><?php echo htmlspecialchars($user['user_position']); ?></p>
        </div>
        <ul>
            
            <li style="font-size: 18px; display: flex; align-items: center;">
                <a href="evaluation_page.php" style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <i class="bi bi-people"></i>
                        <span>Clients</span>
                    </div>
                    <span style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                        <?php echo htmlspecialchars($user_type === 0 ? $totalClients : $evaluatedClients); ?>
                    </span>
                </a>
            </li>
            <li style=" font-size: 18px;"><a href="dashboard/dashboard.php"><i class="bi bi-house"></i> <span>Dashboard</span></a></li>
            <li style="font-size: 18px;"><a href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
        </ul>
    </div>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
 
    <div class="main-content">
        <div class="content-container">
            <div class="profile-container" >
                <div class="profile-header">
                    <h2><?php echo htmlspecialchars($client['client_name']); ?></h2>
                    <p><?php echo htmlspecialchars($client['sector_name']); ?></p>
                </div>
                <div id="evaluation-info-box" class="profile-header" style="display: none;">
                    <!-- Image Carousel -->
                    <div class="carousel-container mb-4">
                        <div id="imageCarousel" class="position-relative">
                            <div class="image-wrapper" onclick="openImageModal()">
                                <div class="no-image">
                                <img id="currentImage" src="" alt="No Image Found" class="w-100 h-100 object-fit-cover rounded">
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
                                        <div class="rounded p-2 text-center" style="background-color: transparent; color: white;">
                                            <p class="mb-1" style="color: white;">Rating</p>
                                            <p id="modalRating" style="color: white;">No rating found</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="rounded p-2 text-center" style="background-color: transparent; color: white;">
                                            <p class="mb-1" style="color: white;">Result</p>
                                            <p id="modalResult" style="color: white;">No result found</p>
                                        </div>
                                    </div>
                                </div>
                                <p id="modalEvalDate" class="small text-center mt-2" style="background-color: transparent; color: white;">
                                    Date: Not available
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Summary -->
                    <div class="bg-light rounded p-3">
                        <h3 class="fs-5 fw-bold mb-3">Evaluation Result</h3>
                        <form id="evaluationForm" method="POST">
                            <input type="hidden" name="evaluation_id" value="<?php echo htmlspecialchars($eval_summary['evaluation_id'] ?? ''); ?>">
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
                            <input type="hidden" name="evaluation_id" value="<?php echo $eval_summary['evaluation_id']; ?>">
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
                            <div id="nameField"><?php echo htmlspecialchars($client['client_name'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Sector Name</div>
                            <div id="sectorNameField"><?php echo htmlspecialchars($client['sector_name'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div id="locationField"><?php echo htmlspecialchars($client['client_location'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Region</div>
                            <div><?php echo htmlspecialchars($location['region_name'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Region Description</div>
                            <div><?php echo htmlspecialchars($location['region_description'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <hr style="margin-top: 20px; border-color: #ddd;">
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" id="moreDetailsBtn">View Contact Details</button>
                        <button type="button" class="btn btn-primary" id="editProfileBtn">Edit</button>
                        <button type="button" class="btn btn-success" id="saveProfileBtn" style="display: none;">Save</button>
                        <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display: none;">Cancel</button>
                    </div>
                        <!-- Modal for More Details -->
                        <div id="moreDetailsModal" class="modal">
                            <div class="modal-content-contact" style="position: relative; padding: 20px; background-color: #4100BF; color: white; border-radius: 10px; overflow-x: auto;">
                                <span class="close" onclick="closeMoreDetailsModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
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
                            <div class="modal-content-contact" style="position: relative; padding: 20px; background-color: #4100BF; color: white; max-width: 400px; border-radius: 10px;">
                                <span class="close" onclick="closeAddContactModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
                                <h2 style="margin-top: 0;">Add Contact</h2>
                                <form id="addContactForm">
                                    <div class="mb-2">
                                        <label for="addPosition" class="form-label">Position</label>
                                        <input type="text" class="form-control" id="addPosition" name="contactperson_position" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="addName" class="form-label">Contact Person</label>
                                        <input type="text" class="form-control" id="addName" name="contactperson_name" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="addEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="addEmail" name="contactperson_email" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="addNumber" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="addNumber" name="contactperson_number" required>
                                    </div>
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-success">Add</button>
                                        <button type="button" class="btn btn-secondary" onclick="closeAddContactModal()">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const addBtn = document.querySelector('.contact-add-btn');
                            const addContactModal = document.getElementById('addContactModal');
                            const addContactForm = document.getElementById('addContactForm');
                            const moreDetailsModal = document.getElementById('moreDetailsModal');

                            if (addBtn) {
                                addBtn.addEventListener('click', function () {
                                    addContactModal.style.display = 'block';
                                });
                            }

                            window.closeAddContactModal = function () {
                                addContactModal.style.display = 'none';
                                addContactForm.reset();
                            };

                            // Close modal when clicking outside
                            window.addEventListener('click', function (event) {
                                if (event.target === addContactModal) {
                                    closeAddContactModal();
                                }
                            });

                            // Add input restriction for contact number in add contact form
                            document.getElementById('addNumber').addEventListener('input', function() {
                                this.value = this.value.replace(/[^0-9\-]/g, '');
                            });

                            // Submit handler for add contact
                            addContactForm.addEventListener('submit', function (e) {
                                e.preventDefault();
                                const formData = new FormData(addContactForm);
                                formData.append('action', 'add_contactperson');
                                formData.append('client_id', <?php echo $client_id; ?>);

                                fetch('../Backend/fetch_data_creosales.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        Swal.fire('Success', 'Contact added successfully!', 'success').then(() => {
                                            closeAddContactModal();
                                            // Optionally, refresh the contact list
                                            document.getElementById('moreDetailsBtn').click();
                                        });
                                    } else {
                                        Swal.fire('Error', data.message || 'Failed to add contact.', 'error');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire('Error', 'Unexpected error occurred.', 'error');
                                });
                            });

                            function attachEditHandler(btn) {
                                btn.addEventListener('click', function editHandler() {
                                    const tr = btn.closest('tr');
                                    if (!tr) return;

                                    const contactpersonId = tr.dataset.contactpersonId;
                                    const position = tr.dataset.position;
                                    const name = tr.dataset.name;
                                    const email = tr.dataset.email;
                                    const number = tr.dataset.number;
                                    const originalHTML = tr.innerHTML;

                                    tr.innerHTML = `
                                        <td><input type="text" class="form-control form-control-sm" value="${position}"></td>
                                        <td><input type="text" class="form-control form-control-sm" value="${name}"></td>
                                        <td><input type="email" class="form-control form-control-sm" value="${email}"></td>
                                        <td><input type="text" class="form-control form-control-sm contact-number-input" value="${number}"></td>
                                        <td>
                                            <button class="btn btn-sm btn-success contact-save-btn">Save</button>
                                            <button class="btn btn-sm btn-secondary contact-cancel-btn">Cancel</button>
                                        </td>
                                    `;

                                    // Preserve data attributes
                                    Object.assign(tr.dataset, {
                                        contactpersonId,
                                        position,
                                        name,
                                        email,
                                        number
                                    });

                                    tr.style.backgroundColor = '#4100BF';
                                    tr.style.color = 'white';

                                    // Number input restriction
                                    tr.querySelector('.contact-number-input').addEventListener('input', function() {
                                        this.value = this.value.replace(/[^0-9\-]/g, '');
                                    });

                                    // Cancel handler
                                    tr.querySelector('.contact-cancel-btn').addEventListener('click', function() {
                                        tr.innerHTML = originalHTML;
                                        tr.style.backgroundColor = '#4100BF';
                                        tr.style.color = 'white';
                                        const newEditBtn = tr.querySelector('.contact-edit-btn');
                                        if (newEditBtn) attachEditHandler(newEditBtn);
                                    });

                                    // Save handler
                                    tr.querySelector('.contact-save-btn').addEventListener('click', function() {
                                        const inputs = tr.querySelectorAll('input');
                                        const formData = new FormData();
                                        formData.append('action', 'update_contactperson');
                                        formData.append('contactperson_id', contactpersonId);
                                        formData.append('contactperson_position', inputs[0].value.trim());
                                        formData.append('contactperson_name', inputs[1].value.trim());
                                        formData.append('contactperson_email', inputs[2].value.trim());
                                        formData.append('contactperson_number', inputs[3].value.trim());

                                        fetch('../Backend/fetch_data_creosales.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status === 'success') {
                                                Swal.fire('Success', 'Contact updated successfully!', 'success')
                                                    .then(() => location.reload());
                                            } else {
                                                Swal.fire('Error', data.message || 'Failed to update contact.', 'error');
                                            }
                                        })
                                        .catch(() => {
                                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                                        });
                                    });
                                });
                            }

                            // Initial attachment of edit handlers
                            document.querySelectorAll('.contact-edit-btn').forEach(btn => {
                                attachEditHandler(btn);
                            });

                            document.getElementById('moreDetailsBtn').addEventListener('click', function () {
                                const clientId = <?php echo $client_id; ?>;

                                fetch(`../Backend/fetch_data_creosales.php?action=fetch_contact_details&client_id=${clientId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            const tbody = document.querySelector('#moreDetailsModal table tbody');
                                            tbody.innerHTML = '';

                                            if (data.data.length > 0) {
                                                data.data.forEach(detail => {
                                                    const row = document.createElement('tr');
                                                    row.dataset.contactpersonId = detail.contactperson_id;
                                                    row.dataset.position = detail.contactperson_position || '';
                                                    row.dataset.name = detail.contactperson_name || '';
                                                    row.dataset.email = detail.contactperson_email || '';
                                                    row.dataset.number = detail.contactperson_number || '';
                                                    
                                                    row.innerHTML = `
                                                        <td>${detail.contactperson_position || 'N/A'}</td>
                                                        <td>${detail.contactperson_name || 'N/A'}</td>
                                                        <td>${detail.contactperson_email || 'N/A'}</td>
                                                        <td>${detail.contactperson_number || 'N/A'}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary contact-edit-btn">Edit</button>
                                                        </td>
                                                    `;
                                                    row.style.backgroundColor = '#4100BF';
                                                    row.style.color = 'white';
                                                    tbody.appendChild(row);
                                                });

                                                // Add edit button click handlers
                                                document.querySelectorAll('.contact-edit-btn').forEach(btn => {
                                                    attachEditHandler(btn);
                                                });
                                            } else {
                                                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No data found.</td></tr>';
                                            }

                                            document.getElementById('moreDetailsModal').style.display = 'block';
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                            });

                            function closeMoreDetailsModal() {
                                document.getElementById('moreDetailsModal').style.display = 'none';
                            }

                            window.addEventListener('click', function (event) {
                                const modal = document.getElementById('moreDetailsModal');
                                if (event.target === modal) {
                                    closeMoreDetailsModal();
                                }
                            });
                        });
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
                                                <td onclick="openCriteriaModal('<?php echo htmlspecialchars($evaluation['criteria_criterion']); ?>')">
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
                                    <button type="button" class="btn btn-success export-btn">Export</button>
                                    <button type="button" class="btn btn-primary save-btn" onclick="saveAllChanges()">Save</button>
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
        // Define imagesData and other variables at the top level
        const imagesData = <?php echo json_encode($images); ?>;
        let currentImageIndex = 0;
        let autoPlayInterval;

        // Function to update image display
        function updateImageDisplay() {
            const imgElement = document.getElementById('currentImage');
            const nameElement = document.getElementById('imageName');
            const dateElement = document.getElementById('imageDate');

            if (imgElement && nameElement && dateElement) {
                if (imagesData && imagesData.length > 0) {
                    const currentImage = imagesData[currentImageIndex];

                    if (currentImage.images_path) {
                        imgElement.src = currentImage.images_path;
                        imgElement.style.display = 'block';
                        if (imgElement.nextElementSibling) {
                            imgElement.nextElementSibling.style.display = 'none';
                        }
                    } else {
                        imgElement.src = '';
                        imgElement.alt = 'No Image Found';
                        imgElement.style.display = 'none';
                        if (imgElement.nextElementSibling) {
                            imgElement.nextElementSibling.style.display = 'flex';
                        }
                    }

                    nameElement.textContent = currentImage.images_name || 'No name available';
                    dateElement.textContent = formatDate(currentImage.images_date) || 'No date available';
                } else {
                    imgElement.src = '';
                    imgElement.alt = 'No Image Found';
                    imgElement.style.display = 'none';
                    if (imgElement.nextElementSibling) {
                        imgElement.nextElementSibling.style.display = 'flex';
                    }
                    nameElement.textContent = 'No image found';
                    dateElement.textContent = '';
                }
            } else {
                console.error('Image element or details elements not found');
            }
        }

        // Function to update evaluation summary
        function updateEvaluationSummary() {
            const evalSummary = <?php echo json_encode($eval_summary); ?>;
            if (evalSummary) {
                // Update rating - use the select element
                const ratingSelect = document.getElementById('ratingSelect');
                if (ratingSelect) {
                    // Set the selected option
                    for (let i = 0; i < ratingSelect.options.length; i++) {
                        if (ratingSelect.options[i].value === evalSummary.evaluation_rating) {
                            ratingSelect.selectedIndex = i;
                            break;
                        }
                    }
                }

                // Update result - use the radio buttons
                const resultOptions = document.querySelectorAll('.result-option input[type="radio"]');
                resultOptions.forEach(radio => {
                    if (radio.value === evalSummary.evaluation_result) {
                        radio.checked = true;
                        // Trigger click on parent to update visual state
                        radio.closest('.result-option').click();
                    }
                });

                // Update date
                const dateElement = document.getElementById('evalDate');
                if (dateElement) {
                    dateElement.textContent = `Date: ${formatDate(evalSummary.evaluation_date)}`;
                }
            }
        }

        // Helper function to format dates
        function formatDate(dateString) {
            if (!dateString) return 'Not available';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Navigation functions
        function previousImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                updateImageDisplay();
                updateModalImage();
            }
        }

        function nextImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                updateImageDisplay();
                updateModalImage();
            }
        }

        // Add event listeners for navigation buttons globally
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.carousel-button.left').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (btn.closest('#imageModal')) {
                        previousModalImage();
                    } else {
                        previousImage();
                    }
                });
            });
            document.querySelectorAll('.carousel-button.right').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (btn.closest('#imageModal')) {
                        nextModalImage();
                    } else {
                        nextImage();
                    }
                });
            });
        });

        function updateModalImage() {
            const modalImg = document.getElementById("modalImage");
            const captionText = document.getElementById("modalCaption");

            if (modalImg && captionText) {
                if (imagesData && imagesData.length > 0) {
                    const currentImage = imagesData[currentImageIndex];
                    const imageName = currentImage.images_name || 'No name available';
                    const imageDate = formatDate(currentImage.images_date) || 'No date available';

                    modalImg.src = currentImage.images_path || ''; // No image
                    modalImg.alt = 'No Image Found';
                    captionText.innerHTML = `<b>${imageName}</b><br>${imageDate}`;
                } else {
                    modalImg.src = ''; // No image
                    modalImg.alt = 'No Image Found';
                    captionText.innerHTML = '<b>No image found</b>';
                }

                // Add mini images carousel
                const miniImagesContainer = document.createElement('div');
                miniImagesContainer.classList.add('mini-images-carousel');
                imagesData.forEach((image, index) => {
                    const miniImage = document.createElement('img');
                    miniImage.src = image.images_path || ''; // No image
                    miniImage.alt = 'No Image Found';
                    miniImage.classList.add('mini-image');
                    miniImage.addEventListener('click', () => {
                        currentImageIndex = index;
                        updateImageDisplay();
                        updateModalImage();
                    });
                    miniImagesContainer.appendChild(miniImage);
                });
                captionText.appendChild(miniImagesContainer);
            } else {
                console.error('Modal image or caption elements not found');
            }
        }

        // New modal navigation functions to prevent updating the carousel
        function previousModalImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                updateModalImage();
            }
        }

        function nextModalImage() {
            if (imagesData && imagesData.length > 0) {
                currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                updateModalImage();
            }
        }

        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab
                tab.classList.add('active');

                // Show corresponding content
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        //Add Error handling to prevent null references
        function safeUpdateElement(id, updateFn) {
            const element = document.getElementById(id);
            if (element) {
                updateFn(element);
            } else {
                console.warn(`Element with ID ${id} not found`);
            }
        }

        //notes
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".notes-textarea").forEach(textarea => {

                const initialTextareaValues = {};
                document.querySelectorAll(".notes-textarea").forEach(textarea => {
                    initialTextareaValues[textarea.name] = textarea.value;

                    if (textarea.value.trim() === "empty notes") {
                        textarea.classList.add("gray-out");
                    }

                    textarea.addEventListener("focus", function() {
                        if (this.value.trim() === "empty notes") {
                            this.value = "";
                            this.classList.remove("gray-out");
                        }
                    });

                    textarea.addEventListener("blur", function() {
                        if (this.value.trim() === "") {
                            this.value = "empty notes";
                            this.classList.add("gray-out");
                        }
                    });
                });
            });
        });

        //show pop-up container
        document.addEventListener('DOMContentLoaded', function() {
            const evaluationInfoBox = document.getElementById('evaluation-info-box');

            // Get the PHP data
            const evalSummary = <?php echo json_encode($eval_summary); ?>;

            // Function to update image display
            function updateImageDisplay() {
                const imgElement = document.getElementById('currentImage');
                const nameElement = document.getElementById('imageName');
                const dateElement = document.getElementById('imageDate');

                if (imgElement && nameElement && dateElement) {
                    if (imagesData && imagesData.length > 0) {
                        const currentImage = imagesData[currentImageIndex];

                        // Update image source - check if images_path exists
                        if (currentImage.images_path) {
                            imgElement.src = currentImage.images_path;
                        } else {
                            imgElement.src = ''; // No image
                            imgElement.alt = 'No Image Found';
                        }

                        // Update image details
                        nameElement.textContent = currentImage.images_name || 'No name available';
                        dateElement.textContent = formatDate(currentImage.images_date) || 'No date available';
                    } else {
                        // Display "No image found" when no data is fetched
                        imgElement.src = ''; // No image
                        imgElement.alt = 'No Image Found';
                        nameElement.textContent = 'No image found';
                        dateElement.textContent = '';
                    }
                } else {
                    console.error('Image element or details elements not found');
                }
            }

            // Function to update evaluation summary
            function updateEvaluationSummary() {
                if (evalSummary) {
                    // Update rating - use the select element
                    const ratingSelect = document.getElementById('ratingSelect');
                    if (ratingSelect) {
                        // Set the selected option
                        for (let i = 0; i < ratingSelect.options.length; i++) {
                            if (ratingSelect.options[i].value === evalSummary.evaluation_rating) {
                                ratingSelect.selectedIndex = i;
                                break;
                            }
                        }
                    }

                    // Update result - use the radio buttons
                    const resultOptions = document.querySelectorAll('.result-option input[type="radio"]');
                    resultOptions.forEach(radio => {
                        if (radio.value === evalSummary.evaluation_result) {
                            radio.checked = true;
                            // Trigger click on parent to update visual state
                            radio.closest('.result-option').click();
                        }
                    });

                    // Update date
                    const dateElement = document.getElementById('evalDate');
                    if (dateElement) {
                        dateElement.textContent = `Date: ${formatDate(evalSummary.evaluation_date)}`;
                    }
                }
            }

            // Helper function to format dates
            function formatDate(dateString) {
                if (!dateString) return 'Not available';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            // Navigation functions
            function previousImage() {
                if (imagesData && imagesData.length > 0) {
                    currentImageIndex = (currentImageIndex - 1 + imagesData.length) % imagesData.length;
                    updateImageDisplay();
                }
            }

            function nextImage() {
                if (imagesData && imagesData.length > 0) {
                    currentImageIndex = (currentImageIndex + 1) % imagesData.length;
                    updateImageDisplay();
                }
            }

            // Set up auto-play functionality
            function startAutoPlay() {
                stopAutoPlay(); // Clear any existing interval
                autoPlayInterval = setInterval(nextImage, 4000); // Change image every 3 seconds
            }

            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                }
            }

            // Show/hide evaluation info box on tab click
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    if (tab.getAttribute('data-tab') === 'evaluation') {
                        evaluationInfoBox.style.display = 'block';
                        // Initialize displays when tab is shown
                        updateImageDisplay();
                        updateEvaluationSummary();
                        startAutoPlay();
                    } else {
                        evaluationInfoBox.style.display = 'none';
                        stopAutoPlay();
                    }
                });
            });

            // Initial setup if evaluation tab is active
            if (document.querySelector('.tab[data-tab="evaluation"]').classList.contains('active')) {
                evaluationInfoBox.style.display = 'block';
                updateImageDisplay();
                updateEvaluationSummary();
                startAutoPlay();
            }
        });

        // merge save buttons into one
        function saveAllChanges() {
            Swal.fire({
                title: 'Save Changes',
                text: 'Are you sure you want to save all changes?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save changes!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#e77373',
                cancelButtonColor: '#6c757d'

            }).then((result) => {
                if (result.isConfirmed) {
                    // Get form data from both forms
                    const evaluationFormData = new FormData(document.getElementById('evaluationForm'));
                    const notesFormData = new FormData(document.querySelector('#evaluation form'));

                    // Combine both forms' data
                    const combinedFormData = new FormData();

                    // Add evaluation form data
                    for (let [key, value] of evaluationFormData.entries()) {
                        combinedFormData.append(key, value);
                    }

                    // Add notes form data
                    for (let [key, value] of notesFormData.entries()) {
                        combinedFormData.append(key, value);
                    }

                    // Send the combined data
                    fetch('../Backend/update_evaluation.php', {
                        method: 'POST',
                        body: combinedFormData
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(`Server responded with status: ${response.status}`);
                        }

                        // Check the content type to handle different responses
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, get text and try to parse it or handle as error
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    throw new Error('The server returned an invalid response: ' + text.substring(0, 100));
                                }
                            });
                        }
                    }).then(data => {
                        if (data.status === "success") {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#e77373'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error saving changes: ' + data.message,
                                icon: 'error',
                                confirmButtonColor: '#e77373'
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'An unexpected error occurred while saving changes. ' + error.message,
                            icon: 'error',
                            confirmButtonColor: '#e77373'
                        });
                    });
                }
            });
        }

        //animation on sliding selection passed,failed and conditional
        document.addEventListener('DOMContentLoaded', function() {

            const resultSlider = document.querySelector('.result-options');
            const resultOptions = document.querySelectorAll('.result-option');
            let currentResultIndex = 0;

            const evaluationSaveBtn = document.querySelector('#evaluationForm .save-btn');
            const notesSaveBtn = document.querySelector('#evaluation .save-btn');

            // Initialize selected result
            resultOptions.forEach((option, index) => {
                if (option.querySelector('input[type="radio"]').checked) {
                    currentResultIndex = index;
                    updateResultSlider();
                }
            });

            // Handle result option clicks
            resultOptions.forEach((option, index) => {
                option.addEventListener('click', () => {
                    currentResultIndex = index;
                    option.querySelector('input[type="radio"]').checked = true;
                    updateResultSlider();
                    updateSelectedStyles();
                });
            });

            // Handle slider navigation
            document.querySelector('.prev-result').addEventListener('click', () => {
                if (currentResultIndex > 0) {
                    currentResultIndex--;
                    updateResultSlider();
                    resultOptions[currentResultIndex].click();
                }
            });

            document.querySelector('.next-result').addEventListener('click', () => {
                if (currentResultIndex < resultOptions.length - 1) {
                    currentResultIndex++;
                    updateResultSlider();
                    resultOptions[currentResultIndex].click();
                }
            });

            // Update slider position
            function updateResultSlider() {
                const translateX = -(currentResultIndex * (100 / 3));
                resultSlider.style.transform = `translateX(${translateX}%)`;
                updateSliderNavigation();
            }

            // Update navigation button states
            function updateSliderNavigation() {
                document.querySelector('.prev-result').style.visibility =
                    currentResultIndex === 0 ? 'hidden' : 'visible';
                document.querySelector('.next-result').style.visibility =
                    currentResultIndex === resultOptions.length - 1 ? 'hidden' : 'visible';
            }

            // Update selected styles
            function updateSelectedStyles() {
                resultOptions.forEach(option => option.classList.remove('selected'));
                resultOptions[currentResultIndex].classList.add('selected');
            }

            if (evaluationSaveBtn) {
                evaluationSaveBtn.textContent = 'Save';
                evaluationSaveBtn.removeEventListener('click', null);
                evaluationSaveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            if (notesSaveBtn) {
                notesSaveBtn.textContent = 'Save';
                notesSaveBtn.removeEventListener('click', null);
                notesSaveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            // Override form submissions
            const evaluationForm = document.getElementById('evaluationForm');
            if (evaluationForm) {
                evaluationForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }

            const notesForm = document.querySelector('#evaluation form');
            if (notesForm) {
                notesForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveAllChanges();
                });
            }


            // Enable touch swiping for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            resultSlider.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            resultSlider.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0 && currentResultIndex < resultOptions.length - 1) {
                        // Swipe left
                        document.querySelector('.next-result').click();
                    } else if (diff < 0 && currentResultIndex > 0) {
                        // Swipe right
                        document.querySelector('.prev-result').click();
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
    const initialTextareaValues = {};
    const initialSelectValues = {};
    const initialRadioValues = {};
    let hasUnsavedChanges = false;

    // Store initial values of textareas
    document.querySelectorAll(".notes-textarea").forEach(textarea => {
        initialTextareaValues[textarea.name] = textarea.value;
        
        // Add change listener for textareas
        textarea.addEventListener('input', function() {
            checkForChanges();
        });
    });

    // Store initial values of select elements
    document.querySelectorAll("select").forEach(select => {
        initialSelectValues[select.name] = select.value;
        
        // Add change listener for selects
        select.addEventListener('change', function() {
            checkForChanges();
        });
    });

    // Store initial values of radio buttons
    document.querySelectorAll("input[type='radio']").forEach(radio => {
        if (radio.checked) {
            initialRadioValues[radio.name] = radio.value;
        }
        
        // Add change listener for radios
        radio.addEventListener('change', function() {
            if (radio.checked) {
                checkForChanges();
            }
        });
    });

    // Specifically handle the rating select element
    const ratingSelect = document.getElementById('ratingSelect');
    if (ratingSelect) {
        initialSelectValues[ratingSelect.name] = ratingSelect.value;
        ratingSelect.addEventListener('change', function() {
            checkForChanges();
        });
    }

    // Handle result option clicks
    document.querySelectorAll('.result-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                checkForChanges();
            }
        });
    });

    // Function to check for unsaved changes
    function checkForChanges() {
        hasUnsavedChanges = false;

        // Check textareas
        document.querySelectorAll(".notes-textarea").forEach(textarea => {
            if (textarea.value !== initialTextareaValues[textarea.name]) {
                hasUnsavedChanges = true;
            }
        });

        // Check select elements
        document.querySelectorAll("select").forEach(select => {
            if (select.value !== initialSelectValues[select.name]) {
                hasUnsavedChanges = true;
            }
        });

        // Check radio buttons
        document.querySelectorAll("input[type='radio']").forEach(radio => {
            if (radio.checked && initialRadioValues[radio.name] && 
                radio.value !== initialRadioValues[radio.name]) {
                hasUnsavedChanges = true;
            }
        });
    }

    // Add event listener for export button
    const exportBtn = document.querySelector('.export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Check for unsaved changes before proceeding
            checkForChanges();
            
            if (hasUnsavedChanges) {
                Swal.fire({
                    title: 'Unsaved Changes',
                    text: 'Please save your changes before exporting the edited data to Excel.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#90EE90'
                });
            } else {
                Swal.fire({
                    title: 'Export Data',
                    text: 'Do you want to export this client\'s data to Excel?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, export it!',
                    cancelButtonText: 'No, cancel',
                    confirmButtonColor: '#90EE90',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Get client_id from URL parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const clientId = urlParams.get('id');

                        // Redirect to export handler
                        window.location.href = `../Backend/ExportSystemBackend/export_handler.php?client_id=${clientId}`;
                    }
                });
            }
        });
    }

    // Add event listener for save button to reset the initial values after saving
    const saveButtons = document.querySelectorAll('.save-btn');
    if (saveButtons.length > 0) {
        saveButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // This is handled in the saveAllChanges function's success callback
            });
        });
    }
});

// Modify the saveAllChanges function to update initial values after successful save
function saveAllChanges() {
    Swal.fire({
        title: 'Save Changes',
        text: 'Are you sure you want to save all changes?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save changes!',
        cancelButtonText: 'No, cancel',
        confirmButtonColor: '#e77373',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get form data from both forms
            const evaluationFormData = new FormData(document.getElementById('evaluationForm'));
            const notesFormData = new FormData(document.querySelector('#evaluation form'));

            // Combine both forms' data
            const combinedFormData = new FormData();

            // Add evaluation form data
            for (let [key, value] of evaluationFormData.entries()) {
                combinedFormData.append(key, value);
            }

            // Add notes form data
            for (let [key, value] of notesFormData.entries()) {
                combinedFormData.append(key, value);
            }

            // Send the combined data
            fetch('../Backend/update_evaluation.php', {
                method: 'POST',
                body: combinedFormData
            }).then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }

                // Check the content type to handle different responses
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, get text and try to parse it or handle as error
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error('The server returned an invalid response: ' + text.substring(0, 100));
                        }
                    });
                }
            }).then(data => {
                if (data.status === "success") {
                    // Update initial values to reflect saved state
                    const initialTextareaValues = {};
                    const initialSelectValues = {};
                    const initialRadioValues = {};

                    // Update textareas
                    document.querySelectorAll(".notes-textarea").forEach(textarea => {
                        initialTextareaValues[textarea.name] = textarea.value;
                    });

                    // Update selects
                    document.querySelectorAll("select").forEach(select => {
                        initialSelectValues[select.name] = select.value;
                    });

                    // Update radios
                    document.querySelectorAll("input[type='radio']").forEach(radio => {
                        if (radio.checked) {
                            initialRadioValues[radio.name] = radio.value;
                        }
                    });

                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#e77373'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error saving changes: ' + data.message,
                        icon: 'error',
                        confirmButtonColor: '#e77373'
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred while saving changes. ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#e77373'
                });
            });
        }
    });
}

function openImageModal() {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const captionText = document.getElementById("modalCaption");
    const currentImage = document.getElementById("currentImage");
    const imageName = document.getElementById("imageName").textContent;
    const imageDate = document.getElementById("imageDate").textContent;
    const rating = document.getElementById("ratingSelect").value;
    const result = document.querySelector('.result-option input[type="radio"]:checked').value;
    const evalDate = document.getElementById("evalDate").textContent;

    modal.style.display = "block";
    modalImg.src = currentImage.src;
    modalImg.alt = currentImage.alt;
    captionText.innerHTML = `<b>${imageName}</b><br>${imageDate}`;
    document.getElementById("modalRating").textContent = rating || 'No rating found';
    document.getElementById("modalResult").textContent = result || 'No result found';
    document.getElementById("modalEvalDate").textContent = evalDate;

    // Add mini images carousel
    const miniImagesContainer = document.createElement('div');
    miniImagesContainer.classList.add('mini-images-carousel');
    imagesData.forEach((image, index) => {
        const miniImage = document.createElement('img');
        miniImage.src = image.images_path || ''; // No image
        miniImage.alt = 'No Image Found';
        miniImage.classList.add('mini-image');
        miniImage.addEventListener('click', () => {
            currentImageIndex = index;
            updateImageDisplay();
            updateModalImage();
        });
        miniImagesContainer.appendChild(miniImage);
    });
    captionText.appendChild(miniImagesContainer);
}

function closeImageModal() {
    const modal = document.getElementById("imageModal");
    modal.style.display = "none";
    const captionText = document.getElementById("modalCaption");
    while (captionText.firstChild) {
        captionText.removeChild(captionText.firstChild);
    }
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target === modal) {
        closeImageModal();
    }
});

    </script>
    <script>
    function openCriteriaModal(criteria) {
        const modal = document.getElementById("criteriaModal");
        const criteriaDetails = document.getElementById("criteriaDetails");
        const subcriteriaDetails = document.getElementById("subcriteriaDetails");

        criteriaDetails.textContent = criteria;
        subcriteriaDetails.innerHTML = ''; // Clear previous subcriteria details

        // Fetch subcriteria details via AJAX
        fetch(`../Backend/fetch_subcriteria.php?criteria=${encodeURIComponent(criteria)}&client_id=<?php echo $client_id; ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(subcriteria => {
                        const tr = document.createElement('tr');
                        const tdName = document.createElement('td');
                        tdName.textContent = subcriteria.subcriteria_criterion;
                        const tdRating = document.createElement('td');
                        tdRating.textContent = subcriteria.subrating_score;

                        // Apply color based on rating
                        const rating = parseFloat(subcriteria.subrating_score);
                        if (rating > 3.5) {
                            tdRating.style.backgroundColor = '#8fbc8f'; // Passed - lighter green
                            tdRating.style.color = '#000000';   
                        } else if (rating >= 3 && rating <= 3.5) {
                            tdRating.style.backgroundColor = '#c0c0c0'; // Conditional - lighter yellow
                            tdRating.style.color = '#000000';
                        } else {
                            tdRating.style.backgroundColor = '#fa8072'; // Failed - lighter red
                            tdRating.style.color = '#000000';
                        }

                        tr.appendChild(tdName);
                        tr.appendChild(tdRating);
                        subcriteriaDetails.appendChild(tr);
                    });
                } else {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 2;
                    td.textContent = 'No data found.';
                    tr.appendChild(td);
                    subcriteriaDetails.appendChild(tr);
                }
            })
            .catch(error => {
                console.error('Error fetching subcriteria:', error);
                subcriteriaDetails.textContent = 'Error fetching subcriteria.';
            });

        modal.style.display = "block";
    }

    function closeCriteriaModal() {
        const modal = document.getElementById("criteriaModal");
        modal.style.display = "none";
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById("criteriaModal");
        if (event.target === modal) {
            closeCriteriaModal();
        }
    });
</script>
    <!-- Modal Structure -->
    <div id="criteriaModal" class="modal">
        <div class="modal-content" style="position: relative; padding: 20px; background-color: #4100BF; color: white;">
            <span class="close" onclick="closeCriteriaModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
            <h2 id="criteriaDetails" style="margin-top: 0;"></h2>
            <table id="subcriteriaTable" class="table table-bordered" >
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('editProfileBtn');
        const saveBtn = document.getElementById('saveProfileBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');
        const viewDetailsBtn = document.getElementById('moreDetailsBtn'); // View Contact Details button

        const fields = [
            { id: 'nameField', name: 'client_name' },
           
            { id: 'sectorNameField', name: 'sector_name', isDropdown: true }, // Mark sectorNameField as dropdown
            { id: 'locationField', name: 'client_location' }
        ];

        let originalValues = {};
        let isEditing = false;

        editBtn.addEventListener('click', function () {
            if (!isEditing) {
                originalValues = {};
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    originalValues[field.id] = element.textContent;

                    if (field.isDropdown) {
                        element.innerHTML = `
                            <select class="form-select" name="${field.name}">
                                <option value="School" ${element.textContent.trim() === 'School' ? 'selected' : ''}>School</option>
                                <option value="Government" ${element.textContent.trim() === 'Government' ? 'selected' : ''}>Government</option>
                                <option value="Sponsor" ${element.textContent.trim() === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                                <option value="Industry" ${element.textContent.trim() === 'Industry' ? 'selected' : ''}>Industry</option>
                            </select>
                        `;
                    } else if (field.type === 'number') {
                        element.innerHTML = `<input type="text" class="form-control" name="${field.name}" value="${element.textContent.trim()}" oninput="this.value = this.value.replace(/[^0-9\-]/g, '')">`;
                    } else {
                        element.innerHTML = `<input type="text" class="form-control" name="${field.name}" value="${element.textContent.trim()}">`;
                    }
                });
                toggleEditState(true);
            }
        });

        cancelBtn.addEventListener('click', function () {
            restoreOriginalValues();
            toggleEditState(false);
            isEditing = false; 
        });

        saveBtn.addEventListener('click', function () {
            const changes = [];
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                const input = element.querySelector('input, select'); 
                const oldValue = originalValues[field.id];
                let newValue = input ? input.value.trim() : element.textContent.trim();

                if (!newValue) {
                    newValue = "Not Available";
                }

                if (oldValue !== newValue) {
                    changes.push({ label: field.name.replace('_', ' '), oldValue, newValue });
                }

                if (field.isDropdown) {
                    element.innerHTML = `
                        <select class="form-select" name="${field.name}">
                            <option value="School" ${newValue === 'School' ? 'selected' : ''}>School</option>
                            <option value="Government" ${newValue === 'Government' ? 'selected' : ''}>Government</option>
                            <option value="Sponsor" ${newValue === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                            <option value="Industry" ${newValue === 'Industry' ? 'selected' : ''}>Industry</option>
                        </select>
                    `;
                } else {
                    element.textContent = newValue;
                }
            });

            if (changes.length > 0) {
                openChangesModal(changes);
            } else {
                Swal.fire({
                    title: 'No Changes',
                    text: 'No changes were made.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6c757d'
                }).then(() => {
                    location.reload(); // Refresh the page when no changes are made
                });
            }
        });

        function toggleEditState(editing) {
            isEditing = editing;

            const editBtn = document.getElementById('editProfileBtn');
            const saveBtn = document.getElementById('saveProfileBtn');
            const cancelBtn = document.getElementById('cancelEditBtn');
            const viewDetailsBtn = document.getElementById('moreDetailsBtn'); // View Contact Details button

            editBtn.style.display = editing ? 'none' : 'inline-block';
            saveBtn.style.display = editing ? 'inline-block' : 'none';
            cancelBtn.style.display = editing ? 'inline-block' : 'none';
            viewDetailsBtn.style.display = editing ? 'none' : 'inline-block'; // Toggle View Contact Details button

            if (!editing) {
                fields.forEach(field => {
                    const element = document.getElementById(field.id);
                    if (field.isDropdown) {
                        element.innerHTML = originalValues[field.id];
                    } else {
                        element.textContent = originalValues[field.id];
                    }
                });
            }
        }

        function restoreOriginalValues() {
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    element.innerHTML = originalValues[field.id] || 'N/A';
                }
            });
        }

        function openChangesModal(changes) {
            const changesList = document.getElementById('changesList');
            changesList.innerHTML = '';

            changes.forEach(change => {
                const li = document.createElement('li');
                li.textContent = `${change.label}: ${change.oldValue} → ${change.newValue}`;
                changesList.appendChild(li);
            });

            const modal = document.getElementById('changesModal');
            modal.style.display = 'block';

      
            toggleEditState(true);
        }

     
        window.closeChangesModal = function () {
            const modal = document.getElementById('changesModal');
            modal.style.display = 'none';

            toggleEditState(true);
        };

        window.confirmChanges = function () {
            closeChangesModal();
            saveChangesToServer();
        };

        function saveChangesToServer() {
            const formData = new FormData();
            formData.append('action', 'update_client');
            formData.append('client_id', <?php echo $client_id; ?>);

            fields.forEach(field => {
                const input = document.querySelector(`#${field.id} input, #${field.id} select`);
                let value = input ? input.value.trim() : document.getElementById(field.id).textContent.trim();

                if (!value) {
                    value = "Not Available";
                }

                formData.append(field.name, value);
            });

            fetch('../Backend/fetch_data_creosales.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        fields.forEach(field => {
                            const element = document.getElementById(field.id);
                            const input = document.querySelector(`#${field.id} input, #${field.id} select`);
                            if (field.isDropdown) {
                                element.innerHTML = `
                                    <select class="form-select" name="${field.name}">
                                        <option value="School" ${input.value === 'School' ? 'selected' : ''}>School</option>
                                        <option value="Government" ${input.value === 'Government' ? 'selected' : ''}>Government</option>
                                        <option value="Sponsor" ${input.value === 'Sponsor' ? 'selected' : ''}>Sponsor</option>
                                        <option value="Industry" ${input.value === 'Industry' ? 'selected' : ''}>Industry</option>
                                    </select>
                                `;
                            } else {
                                element.textContent = input ? input.value : element.textContent;
                            }
                        });
                        Swal.fire('Success', 'Profile updated successfully!', 'success').then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                })
                .finally(() => {
                    toggleEditState(false);
                });
        }
    });
</script>
<div id="changesModal" class="modal">
    <div class="modal-content" style="position: relative; padding: 20px; background-color: #4100BF; color: white;">
        <span class="close" onclick="closeChangesModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</span>
        <h2 style="margin-top: 0;">Changes Summary</h2>
        <ul id="changesList" style="list-style: none; padding: 0; margin: 0;">
        </ul>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success" onclick="confirmChanges()">Confirm</button>
            <button type="button" class="btn btn-secondary" onclick="closeChangesModal()">Cancel</button>
        </div>
    </div>
</div>
</body>

</html>