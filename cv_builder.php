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
    <title>CV Builder - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="cv_builder.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>
    <div class="shape shape4"></div>
    <div class="shape shape5"></div>
    <div class="shape shape6"></div>

    <?php if ($loggedIn): ?>
        <section class="protected-page">
            <h1>CV Builder</h1>
            <p class="protected-lead">A more CV builder Templates is coming soon. In the meantime you can upload an existing
                PDF CV to your profile and recruiters will be able to see it when you apply for jobs.</p>
            <!--cv build card add karanna -->
           <div class="card-grid">
                <a href="cv/cv1.php" class="card">
                    <img src="cv/cv1.png" alt="cv1png eka">
                    <div class="overlay">
                        <h2>IT  Template</h2>
                        <p>You can download this pdf.</p>
                    </div>
                </a>

                <a href="cv/cv temp3.php" class="card">
                    <img src="cv/cv2.png" alt="">
                    <div class="overlay">
                        <h2>Agriculture  Template</h2>
                        <p>You can download this pdf.</p>
                    </div>
                </a>

                <a href="cv/cvtemp2.php" class="card">
                    <img src="cv/cv3.png" alt="">
                    <div class="overlay">
                        <h2>Medicine Template</h2>
                        <p>You can download this pdf.</p>
                    </div>
                </a>

                <a href="cv/mgcvtemp4.php" class="card">
                    <img src="cv/cv4.png" alt="">
                    <div class="overlay">
                        <h2>Management  Template</h2>
                        <p>You can download this pdf.</p>
                    </div>
                </a>
                <a href="" class="card">
                    <img src="image..." alt="">
                    <div class="overlay">
                        <h2>comming soon more... </h2>
                        <p>we are add more wait..</p>
                    </div>
                </a>

            </div>
        </section>
    <?php else: ?>
        <section class="protected-page">
            <h1>CV Builder</h1>
            <p class="protected-lead">Please log in to view this page.</p>
        </section>
    <?php endif; ?>

    <?php include 'auth_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
    <?php if (!$loggedIn): ?>
    <script>document.addEventListener('DOMContentLoaded', function(){ new bootstrap.Modal(document.getElementById('authModal')).show(); });</script>
    <?php endif; ?>
</body>
</html>
