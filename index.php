<?php
require 'auth_check.php';
require 'config.php';

$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
$profileImage = null;

if ($loggedIn) {
    if ($_SESSION['role'] === 'seeker') {
        $stmt = $pdo->prepare("SELECT profile_image FROM job_seekers WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT profile_image FROM recruiters WHERE id = ?");
    }
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    $profileImage = $row['profile_image'] ?? null;
}
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
                <a href="jobs.php" class="loginbtn2">Go to Jobs</a>
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
