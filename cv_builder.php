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
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php if ($loggedIn): ?>
        <section class="protected-page">
            <h1>CV Builder</h1>
            <p class="protected-lead">A guided CV builder is coming soon. In the meantime you can upload an existing
                PDF CV to your profile and recruiters will be able to see it when you apply for jobs.</p>
            <div class="empty-state">
                <?php if ($_SESSION['role'] === 'seeker'): ?>
                    <p>Ready to add your CV?</p>
                    <a href="profile.php" class="loginbtn2" style="display:inline-flex;">Go to My Profile</a>
                <?php else: ?>
                    <p>The CV Builder is available to job seeker accounts.</p>
                <?php endif; ?>
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
