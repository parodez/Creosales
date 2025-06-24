<div class="sidebar" id="sidebar">
    <div class="profile">
        <h4 style="font-weight:bold"><?php echo $currentUser->getFullName(); ?></h4>
        <p style="font-size: small;"><?php echo $currentUser->department; ?></p>
        <p style="font-size: small;"><?php echo $currentUser->position; ?></p>
    </div>
    <ul>
        <li style="font-size: 18px; display: flex; align-items: center;">
            <a href="evaluations.php"
                style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center;">
                    <i class="bi bi-people"></i>
                    <span>Evaluations</span>
                </div>
                <span
                    style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                    <?php echo htmlspecialchars($currentUserType === 0 ? $totalClients : $userTotalEvaluatedCustomers); ?>
                </span>
            </a>
        </li>
        <li style="font-size: 18px; display: flex; align-items: center;">
            <a href="passedCustomers.php"
                style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center;">
                    <i class="bi bi-people"></i>
                    <span>Passed Customers</span>
                </div>
                <span
                    style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                    <?php echo htmlspecialchars($currentUserType === 0 ? $evaluationResults['Passed'] : $evaluationResults['Passed']); ?>
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