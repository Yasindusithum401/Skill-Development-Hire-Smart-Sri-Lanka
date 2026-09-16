<?php
require 'auth_check.php';
require 'config.php';

$jobId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT j.*, r.company_name, r.company_description, r.company_email,
            ts.overall_trust_score, ts.display_badge, ts.m1_response_time,
            AVG(rev.rating) AS avg_candidate_rating, COUNT(rev.id) AS review_count
     FROM jobs j
     JOIN recruiters r ON r.id = j.recruiter_id
     LEFT JOIN employer_trust_scores ts ON ts.recruiter_id = r.id
     LEFT JOIN employer_reviews rev ON rev.recruiter_id = r.id
     WHERE j.id = ?
     GROUP BY j.id"
);
$stmt->execute([$jobId]);
$job = $stmt->fetch();

if (!$job) {
    header('Location: jobs.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($job['title']); ?> - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($job['title']); ?></h1>
                <p class="text-muted fs-5"><?php echo htmlspecialchars($job['company_name']); ?> • <?php echo htmlspecialchars($job['location']); ?></p>

                <hr>

                <h3>Job Description</h3>
                <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

                <h3 class="mt-4">Requirements</h3>
                <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
            </div>

            <div class="col-md-4">
                <!-- ETSR Employer Credibility Card -->
                <div class="card p-4 shadow-sm border-0 bg-light">
                    <h4 class="fw-bold h5">Employer Credibility</h4>
                    <hr>
                    <div class="mb-2">
                        <span class="text-muted">ETSR Trust Score:</span>
                        <strong class="text-primary fs-5"><?php echo $job['overall_trust_score'] ? $job['overall_trust_score'] . '%' : 'N/A'; ?></strong>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Trust Status:</span>
                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($job['display_badge'] ?? 'New Employer'); ?></span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Candidate Rating:</span>
                        <span class="text-warning fw-bold">
                            ★ <?php echo $job['avg_candidate_rating'] ? round($job['avg_candidate_rating'], 1) . ' / 5' : 'No ratings yet'; ?>
                        </span>
                        <small class="text-muted">(<?php echo $job['review_count']; ?> reviews)</small>
                    </div>

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'seeker'): ?>
                        <form action="process.php" method="POST">
                            <input type="hidden" name="action" value="apply_job">
                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Apply Now</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-primary w-100 fw-bold py-2 open-login-modal">Login to Apply</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
</body>
</html>