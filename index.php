<?php
require_once 'auth_check.php';
require_once 'config.php';

$loggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? null;
$userName = $_SESSION['name'] ?? '';
$statusMsg = $_GET['message'] ?? '';
$statusType = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        .job-flash {
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 500;
            text-align: center;
        }
        .job-flash-success { background:#e9f8ee; color:#1e7a3d; border:1px solid #bfe9cc; }
        .job-flash-error { background:#fdeceb; color:#c23a2e; border:1px solid #f6c9c4; }
    </style>
</head>
<body>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>
    <div class="shape shape4"></div>
    <div class="shape shape5"></div>
    <div class="shape shape6"></div>

    <?php include 'navbar.php'; ?>

    <?php if ($statusMsg): ?>
        <div class="job-flash <?php echo $statusType === 'success' ? 'job-flash-success' : 'job-flash-error'; ?>" style="max-width:700px;margin:20px auto 0;">
            <?php echo htmlspecialchars($statusMsg); ?>
        </div>
    <?php endif; ?>

    <section class="text-center py-5">
        <h1>Find Your Dream Job<br><span class="orange">Build Your Future</span></h1>
        <div class="para">
            <p><span class="bold">Hire Smart Sri Lanka</span> is an innovative job portal that connects job seekers
                with trusted employers across Sri Lanka. Our platform simplifies the recruitment
                process with features like Job searching, an online CV Builder, application tracking,
                and employer trust ratings, helping users build successful careers with confidence.</p>
        </div><br><br>
        <h5>Find Your Jobs</h5>
        <span class="material-symbols-outlined">arrow_cool_down</span>
        <div class="buttonbottum">
            <?php if ($loggedIn): ?>
                <?php if ($userRole === 'recruiter'): ?>
                    <a href="dashboard.php" class="loginbtn2" style="text-decoration: none; margin-right: 10px;">Go to Dashboard</a>
                <?php endif; ?>
                <a href="jobs.php" class="loginbtn2" style="text-decoration: none;">Go to Jobs</a>
            <?php else: ?>
                <button class="loginbtn2 open-login-modal">Login</button>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'auth_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
</body>
</html>