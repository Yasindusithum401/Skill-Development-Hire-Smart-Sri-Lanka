<?php
require 'auth_check.php';
require 'config.php';

$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
$profileImage = null;
$role = $_SESSION['role'] ?? null;

if ($loggedIn) {
    if ($role === 'seeker') {
        $stmt = $pdo->prepare("SELECT profile_image FROM job_seekers WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT profile_image FROM recruiters WHERE id = ?");
    }
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    $profileImage = $row['profile_image'] ?? null;
}

$jobs = [];
$appliedJobIds = [];

if ($loggedIn) {
    // ---- Read + sanitize filters ----
    $q          = trim($_GET['q'] ?? '');
    $category   = trim($_GET['category'] ?? '');
    $jobType    = trim($_GET['job_type'] ?? '');
    $location   = trim($_GET['location'] ?? '');
    $experience = trim($_GET['experience_level'] ?? '');
    $minSalary  = $_GET['min_salary'] ?? '';

    $where  = ["j.status = 'open'"];
    $params = [];

    if ($q !== '') {
        $where[] = "(j.title LIKE ? OR j.description LIKE ? OR r.company_name LIKE ?)";
        $like = "%$q%";
        $params[] = $like; $params[] = $like; $params[] = $like;
    }
    if ($category !== '') {
        $where[] = "j.category = ?";
        $params[] = $category;
    }
    if ($jobType !== '') {
        $where[] = "j.job_type = ?";
        $params[] = $jobType;
    }
    if ($location !== '') {
        $where[] = "j.location LIKE ?";
        $params[] = "%$location%";
    }
    if ($experience !== '') {
        $where[] = "j.experience_level = ?";
        $params[] = $experience;
    }
    if ($minSalary !== '' && is_numeric($minSalary)) {
        $where[] = "(j.salary_max IS NULL OR j.salary_max >= ?)";
        $params[] = (int)$minSalary;
    }

    $sql = "SELECT j.*, r.company_name, r.profile_image AS company_logo
            FROM jobs j
            JOIN recruiters r ON r.id = j.recruiter_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY j.posted_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll();

    if ($role === 'seeker') {
        $appliedStmt = $pdo->prepare("SELECT job_id FROM applications WHERE seeker_id = ?");
        $appliedStmt->execute([$_SESSION['user_id']]);
        $appliedJobIds = array_column($appliedStmt->fetchAll(), 'job_id');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php if ($loggedIn): ?>
        <section class="jobs-page">
            <div class="jobs-page-head">
                <h1>Find You Dream Job</h1>
                <p class="protected-lead">Browse open positions from trusted employers across Sri Lanka.</p>
            </div>

            <form method="GET" class="job-search-bar">
                <div class="job-search-row">
                    <div class="job-search-field job-search-field-wide">
                        <label>Keyword</label>
                        <input type="text" name="q" placeholder="Job title, company, or keyword" value="<?php echo htmlspecialchars($q ?? ''); ?>">
                    </div>
                    <div class="job-search-field">
                        <label>Category</label>
                        <select name="category">
                            <option value="">All Categories</option>
                            <?php foreach (JOB_CATEGORIES as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo (($category ?? '') === $cat) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="job-search-field">
                        <label>Job Type</label>
                        <select name="job_type">
                            <option value="">Any Type</option>
                            <?php foreach (JOB_TYPES as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo (($jobType ?? '') === $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="job-search-row">
                    <div class="job-search-field">
                        <label>Location</label>
                        <input type="text" name="location" placeholder="e.g. Colombo" value="<?php echo htmlspecialchars($location ?? ''); ?>">
                    </div>
                    <div class="job-search-field">
                        <label>Experience</label>
                        <select name="experience_level">
                            <option value="">Any Level</option>
                            <?php foreach (EXPERIENCE_LEVELS as $lvl): ?>
                                <option value="<?php echo htmlspecialchars($lvl); ?>" <?php echo (($experience ?? '') === $lvl) ? 'selected' : ''; ?>><?php echo htmlspecialchars($lvl); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="job-search-field">
                        <label>Min. Salary (LKR)</label>
                        <input type="number" name="min_salary" placeholder="e.g. 80000" value="<?php echo htmlspecialchars($minSalary ?? ''); ?>">
                    </div>
                    <div class="job-search-field job-search-actions">
                        <button type="submit" class="loginbtn">Search</button>
                        <a href="jobs.php" class="job-search-clear">Clear</a>
                    </div>
                </div>
            </form>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="job-flash job-flash-success"><?php echo htmlspecialchars($_GET['message'] ?? ''); ?></div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="job-flash job-flash-error"><?php echo htmlspecialchars($_GET['message'] ?? ''); ?></div>
            <?php endif; ?>

            <?php if (empty($jobs)): ?>
                <div class="empty-state" style="margin-top:30px;">
                    <p>No job listings match your search  try clearing filters or check back soon.</p>
                </div>
            <?php else: ?>
                <div class="jobs-grid">
                    <?php foreach ($jobs as $job): ?>
                        <div class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-logo">
                                    <?php if (!empty($job['company_logo'])): ?>
                                        <img src="<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company_name']); ?> logo">
                                    <?php else: ?>
                                        <span><?php echo htmlspecialchars(strtoupper(substr($job['company_name'], 0, 1))); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="job-card-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                                    <p class="job-card-company"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                </div>
                            </div>

                            <div class="job-card-tags">
                                <span class="job-tag job-tag-type"><?php echo htmlspecialchars($job['job_type']); ?></span>
                                <span class="job-tag"><?php echo htmlspecialchars($job['category']); ?></span>
                                <span class="job-tag"><?php echo htmlspecialchars($job['experience_level']); ?></span>
                            </div>

                            <p class="job-card-location">📍 <?php echo htmlspecialchars($job['location']); ?></p>

                            <?php if ($job['salary_min'] || $job['salary_max']): ?>
                                <p class="job-card-salary">
                                    LKR <?php echo number_format((float)$job['salary_min']); ?> – <?php echo number_format((float)$job['salary_max']); ?>
                                </p>
                            <?php endif; ?>

                            <p class="job-card-desc"><?php echo htmlspecialchars(mb_strimwidth($job['description'], 0, 150, '…')); ?></p>

                            <div class="job-card-footer">
                                <span class="job-card-posted"><?php echo date('M j, Y', strtotime($job['posted_at'])); ?></span>

                                <?php if ($role === 'seeker'): ?>
                                    <?php if (in_array($job['id'], $appliedJobIds, true)): ?>
                                        <button class="job-apply-btn job-applied" disabled>Applied ✓</button>
                                    <?php else: ?>
                                        <form action="process.php" method="POST">
                                            <input type="hidden" name="action" value="apply_job">
                                            <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                            <button type="submit" class="job-apply-btn">Apply Now</button>
                                        </form>
                                    <?php endif; ?>
                                <?php elseif ($role === 'recruiter' && (int)$job['recruiter_id'] === (int)$_SESSION['user_id']): ?>
                                    <a href="profile.php" class="job-apply-btn job-manage-btn">Manage on Profile</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <section class="protected-page">
            <h1>Job Listings</h1>
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
