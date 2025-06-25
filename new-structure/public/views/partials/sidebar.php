<?php
//* GET CURRENT USER DATA FROM CACHE
$currentUser = $users[$currentUser_id];

if (!isset($currentUser)) {
    $currentUser = $users[$_SESSION['user_id']];
}
if (!isset($totalCustomers)) {
    $totalCustomers = $customerFetcher->getTotalCustomers();
}
if (!isset($userTotalEvaluatedCustomers)) {
    $userTotalEvaluatedCustomers = $customerFetcher->getCustomersEvaluatedByUser($currentUser->id);
}
if (!isset($passedCustomersCount)) {
    $passedCustomersCount = $customerFetcher->getEvaluationResultCount('Passed');
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar" id="sidebar">
    <div class="profile">
        <h4 style="font-weight:bold"><?php echo $currentUser->getFullName(); ?></h4>
        <p style="font-size: small;"><?php echo $currentUser->department; ?></p>
        <p style="font-size: small;"><?php echo $currentUser->position; ?></p>
    </div>
    <ul>
        <!-- DASHBOARD BUTTON -->
        <?php if ($currentPage === 'dashboard.php'): ?>
        <li style="font-size: 18px; pointer-events: none; color: gray;"><a href="">
                <?php else: ?>
        <li style="font-size: 18px;"><a href="dashboard.php">
                <?php endif; ?>
                <i class="bi bi-house"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- EVALUATIONS BUTTON -->
        <?php if ($currentPage === 'evaluations.php'): ?>
        <li style="font-size: 18px; display: flex; align-items: center; pointer-events: none; color: gray;"><a href=""
                style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <?php else: ?>
        <li style="font-size: 18px; display: flex; align-items: center;">
            <a href="evaluations.php"
                style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <?php endif; ?>
                <div style="display: flex; align-items: center;">
                    <i class="bi bi-people"></i>
                    <span>Evaluations</span>
                </div>
                <span
                    style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                    <?php echo htmlspecialchars($currentUserType === 0 ? $totalCustomers : $userTotalEvaluatedCustomers); ?>
                </span>
            </a>
        </li>

        <?php if ($currentPage === 'passedCustomers.php'): ?>
        <li style="font-size: 18px; display: flex; align-items: center; pointer-events: none; color: gray;">
            <a href="" style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <?php else: ?>
        <li style="font-size: 18px; display: flex; align-items: center;">
            <a href="passedCustomers.php"
                style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                <?php endif; ?>
                <div style="display: flex; align-items: center;">
                    <i class="bi bi-people"></i>
                    <span>Passed Customers</span>
                </div>
                <span
                    style="background-color: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; font-weight: bold; padding: 3px 15px; border-radius: 5px;">
                    <?php echo htmlspecialchars($passedCustomersCount); ?>
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