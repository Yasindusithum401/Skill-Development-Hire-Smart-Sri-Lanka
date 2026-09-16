<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? null;
$userName = $_SESSION['name'] ?? '';
$profileImg = $_SESSION['profile_image'] ?? null;
?>
<nav class="navbar">
    <div class="logo">
        <a href="index.php"><img src="logo_image.png" alt="Hire Smart Sri Lanka"></a>
    </div>

    <ul class="navtype">
        <li><a href="index.php">Home</a></li>
        <li><a href="jobs.php" <?php echo !$isLoggedIn ? 'class="open-login-modal" data-nav-guard="1"' : ''; ?>>Jobs</a></li>
        <?php if ($isLoggedIn && $userRole === 'recruiter'): ?>
            <li><a href="dashboard.php" class="fw-bold text-primary">Dashboard</a></li>
        <?php elseif ($isLoggedIn && $userRole === 'seeker'): ?>
            <li><a href="my_applications.php" class="fw-bold text-primary">My Applications</a></li>
        <?php endif; ?>
        <li><a href="cv_builder.php" <?php echo !$isLoggedIn ? 'class="open-login-modal" data-nav-guard="1"' : ''; ?>>CV Builder</a></li>
        <li><a href="about.php" <?php echo !$isLoggedIn ? 'class="open-login-modal" data-nav-guard="1"' : ''; ?>>About</a></li>
    </ul>

    <div id="navAuthArea">
        <?php if ($isLoggedIn): ?>
            <div class="navuser dropdown">
                <button class="navuser-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navuser-avatar">
                        <?php if ($profileImg): ?>
                            <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="profile">
                        <?php else: ?>
                            <span class="navuser-avatar-initial"><?php echo htmlspecialchars(strtoupper(substr($userName, 0, 1))); ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="navuser-name"><?php echo htmlspecialchars($userName); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end navuser-menu">
                    <?php if ($userRole === 'recruiter'): ?>
                        <li><a class="dropdown-item fw-bold" href="dashboard.php">Employer Dashboard</a></li>
                    <?php elseif ($userRole === 'seeker'): ?>
                        <li><a class="dropdown-item fw-bold" href="my_applications.php">Track Applications</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="jobs.php">Browse Jobs</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <button class="loginbtn open-login-modal" type="button">Login / Register</button>
        <?php endif; ?>
    </div>
</nav>