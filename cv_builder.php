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
                        <h2>IT CV Template</h2>
                        <p>You can download this pdf.</p>
                    </div>
                </a>

                <a href="upload.html" class="card">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=900" alt="">
                    <div class="overlay">
                        <h2>..........</h2>
                        <p>....................</p>
                    </div>
                </a>

                <a href="community.html" class="card">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900" alt="">
                    <div class="overlay">
                        <h2>.................</h2>
                        <p>.......................</p>
                    </div>
                </a>

                <a href="about.html" class="card">
                    <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?w=900" alt="">
                    <div class="overlay">
                        <h2>............</h2>
                        <p>...............</p>
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
