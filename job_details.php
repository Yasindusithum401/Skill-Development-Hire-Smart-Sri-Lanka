<?php
require_once 'auth_check.php';
require_once 'config.php';
require_once 'trust_calculator.php';

$jobId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT j.*, r.company_name, r.company_description, r.company_email
     FROM jobs j
     JOIN recruiters r ON r.id = j.recruiter_id
     WHERE j.id = ?"
);
$stmt->execute([$jobId]);
$job = $stmt->fetch();

if (!$job) {
    header('Location: jobs.php');
    exit;
}

// Trust score (cached row; calculate once if the employer has none yet)
$tsStmt = $pdo->prepare(
    "SELECT m1_response_time AS m1, m2_acknowledgement AS m2, m3_rejection_transp AS m3,
            m4_timeline_updates AS m4, overall_trust_score AS overall, display_badge AS badge,
            last_calculated
     FROM employer_trust_scores WHERE recruiter_id = ?"
);
$tsStmt->execute([$job['recruiter_id']]);
$trust = $tsStmt->fetch();
// Recalculate if there is no row yet, or the cached score is older than 1 hour
if (!$trust || strtotime($trust['last_calculated'] ?? '1970-01-01') < time() - 3600) {
    $trust = recalculateEmployerTrustScore($pdo, (int)$job['recruiter_id']);
}

// Candidate reviews
$revStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM employer_reviews WHERE recruiter_id = ?");
$revStmt->execute([$job['recruiter_id']]);
$rev = $revStmt->fetch();

// Total applications (to explain how reliable the score is)
$cntStmt = $pdo->prepare("SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id = a.job_id WHERE j.recruiter_id = ?");
$cntStmt->execute([$job['recruiter_id']]);
$totalApps = (int)$cntStmt->fetchColumn();

$hasApplied = false;
$isSeeker = isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'seeker';
if ($isSeeker) {
    $chk = $pdo->prepare("SELECT 1 FROM applications WHERE job_id = ? AND seeker_id = ?");
    $chk->execute([$jobId, $_SESSION['user_id']]);
    $hasApplied = (bool)$chk->fetchColumn();
}

$badge   = $trust['badge'] ?? 'New Employer';
$overall = (float)($trust['overall'] ?? 0);
$hasScore = $badge !== 'New Employer';
$limited  = $badge === 'Insufficient Data';
$color = !$hasScore || $limited ? 'secondary' : ($overall >= 75 ? 'success' : ($overall >= 50 ? 'warning' : 'danger'));

$ratingPct = ((int)$rev['cnt'] > 0) ? round(((float)$rev['avg_rating'] / 5) * 100, 2) : null;
$metrics = [
    ['Response speed',        $trust['m1'] ?? 0, 'How quickly applicants get a first reply'],
    ['Acknowledgement rate',  $trust['m2'] ?? 0, '% of applications moved past "Applied"'],
    ['Rejection transparency',$trust['m3'] ?? 0, 'Rejections that include a reason and feedback'],
    ['Timeline updates',      $trust['m4'] ?? 0, '% of applications with ongoing status updates'],
];
if ($ratingPct !== null) {
    $metrics[] = ['Candidate rating', $ratingPct, 'Average star rating given by job seekers who applied'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <a href="jobs.php" class="text-decoration-none">&larr; Back to jobs</a>
        <div class="row mt-3">
            <div class="col-md-8">
                <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($job['title']); ?></h1>
                <p class="text-muted fs-5"><?php echo htmlspecialchars($job['company_name']); ?> • <?php echo htmlspecialchars($job['location']); ?></p>

                <hr>

                <h3>Job Description</h3>
                <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

                <h3 class="mt-4">Requirements</h3>
                <p><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>

                <?php if (!empty($job['contact_details'])): ?>
                    <h3 class="mt-4">Company Contact Details</h3>
                    <p><?php echo nl2br(htmlspecialchars($job['contact_details'])); ?></p>
                <?php endif; ?>

                <?php if (!empty($job['company_description'])): ?>
                    <h3 class="mt-4">About the Company</h3>
                    <p><?php echo nl2br(htmlspecialchars($job['company_description'])); ?></p>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <!-- ETSR Employer Credibility Card -->
                <div class="card p-4 shadow-sm border-0 bg-light">
                    <h4 class="fw-bold h5">Employer Trust Rate</h4>
                    <hr>

                    <div class="text-center mb-3">
                        <div class="display-5 fw-bold text-<?php echo $color; ?>">
                            <?php echo $hasScore ? round($overall) . '%' : 'N/A'; ?>
                        </div>
                        <span class="badge bg-<?php echo $color; ?>">
                            <?php echo $hasScore ? ($limited ? 'Limited data' : 'Verified activity') : 'New employer'; ?>
                        </span>
                    </div>

                    <?php if ($hasScore): ?>
                        <?php foreach ($metrics as [$label, $val, $hint]): ?>
                            <div class="mb-2" title="<?php echo htmlspecialchars($hint); ?>">
                                <div class="d-flex justify-content-between small">
                                    <span><?php echo $label; ?></span>
                                    <strong><?php echo round((float)$val); ?>%</strong>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-<?php echo $color; ?>" style="width:<?php echo max(0, min(100, (float)$val)); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <p class="small text-muted mt-2 mb-3">
                            Based on <?php echo $totalApps; ?> application<?php echo $totalApps === 1 ? '' : 's'; ?>.
                            <?php if ($limited): ?>Fewer than 5 applications, so this score may change.<?php endif; ?>
                        </p>
                    <?php else: ?>
                        <p class="small text-muted mb-3">This employer has not received applications yet, so there is no trust score.</p>
                    <?php endif; ?>

                    <div class="mb-3">
                        <span class="text-muted">Candidate Rating:</span>
                        <span class="text-warning fw-bold">
                            ★ <?php echo $rev['cnt'] > 0 ? round($rev['avg_rating'], 1) . ' / 5' : 'No ratings yet'; ?>
                        </span>
                        <small class="text-muted">(<?php echo (int)$rev['cnt']; ?> reviews)</small>
                    </div>

                    <?php if ($isSeeker): ?>
                        <?php if ($hasApplied): ?>
                            <button class="btn btn-success w-100 fw-bold py-2" disabled>Applied ✓</button>
                        <?php elseif ($job['status'] !== 'open'): ?>
                            <button class="btn btn-secondary w-100 fw-bold py-2" disabled>Job closed</button>
                        <?php else: ?>
                            <form action="process.php" method="POST">
                                <input type="hidden" name="action" value="apply_job">
                                <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Apply Now</button>
                            </form>
                        <?php endif; ?>
                    <?php elseif (isset($_SESSION['user_id'])): ?>
                        <p class="small text-muted mb-0">Only job seeker accounts can apply.</p>
                    <?php else: ?>
                        <button class="btn btn-primary w-100 fw-bold py-2 open-login-modal">Login to Apply</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'auth_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
</body>
</html>