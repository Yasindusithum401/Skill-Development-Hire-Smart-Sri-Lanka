<?php
require 'auth_check.php';
require_login();

if ($_SESSION['role'] !== 'recruiter') {
    header('Location: profile.php');
    exit;
}

require 'config.php';
require 'trust_calculator.php';

$recruiterId = (int)$_SESSION['user_id'];

// Recalculate and fetch ETSR metrics
$trustScore = recalculateEmployerTrustScore($pdo, $recruiterId);

// Fetch employer postings
$stmtJobs = $pdo->prepare("SELECT id, title, status FROM jobs WHERE recruiter_id = ? ORDER BY posted_at DESC");
$stmtJobs->execute([$recruiterId]);
$myJobs = $stmtJobs->fetchAll();

$selectedJobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

$query = "SELECT a.id AS application_id, a.current_status, a.applied_at, a.updated_at,
                 j.title AS job_title, s.username, s.contact_email, s.cv_path, s.mobile_number
          FROM applications a
          JOIN jobs j ON j.id = a.job_id
          JOIN job_seekers s ON s.id = a.seeker_id
          WHERE j.recruiter_id = ?";

$params = [$recruiterId];
if ($selectedJobId > 0) {
    $query .= " AND j.id = ?";
    $params[] = $selectedJobId;
}
$query .= " ORDER BY a.updated_at DESC";

$stmtApps = $pdo->prepare($query);
$stmtApps->execute($params);
$applications = $stmtApps->fetchAll();

$stages = ['Applied', 'In Review', 'HR Screening', 'Interview', 'Selected', 'Rejected'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <style>
        .dash-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 20px; }
        .badge-trust { background: #0284c7; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <h1 class="fw-bold mb-3">Employer Dashboard</h1>

        <!-- ETSR Trust Metric Overview Card -->
        <div class="dash-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Employer Trust Rating (ETSR)</h2>
                <span class="badge-trust"><?php echo htmlspecialchars($trustScore['badge']); ?></span>
            </div>
            <div class="row text-center g-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Overall Trust Score</small>
                        <span class="fs-4 fw-bold text-primary"><?php echo $trustScore['overall']; ?>%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Response Speed (M1)</small>
                        <span class="fs-5 fw-bold"><?php echo $trustScore['m1']; ?>%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Acknowledgement (M2)</small>
                        <span class="fs-5 fw-bold"><?php echo $trustScore['m2']; ?>%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Transparency (M3)</small>
                        <span class="fs-5 fw-bold"><?php echo $trustScore['m3']; ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidate Applications Section -->
        <div class="dash-card">
            <h2 class="h5 fw-bold mb-3">Applicant Pipeline</h2>
            <form method="GET" class="mb-3">
                <select name="job_id" class="form-select" onchange="this.form.submit()">
                    <option value="0">All Posted Jobs</option>
                    <?php foreach ($myJobs as $j): ?>
                        <option value="<?php echo $j['id']; ?>" <?php echo $selectedJobId === (int)$j['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($j['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($app['username']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($app['contact_email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($app['current_status']); ?></span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">Update</button>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($stages as $stg): ?>
                                            <?php if ($stg === 'Rejected'): ?>
                                                <li>
                                                    <button class="dropdown-item text-danger" type="button" onclick="openRejectionModal(<?php echo $app['application_id']; ?>, '<?php echo htmlspecialchars(addslashes($app['username'])); ?>')">Reject...</button>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <form action="process.php" method="POST">
                                                        <input type="hidden" name="action" value="update_application_status">
                                                        <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                                        <input type="hidden" name="status" value="<?php echo $stg; ?>">
                                                        <button class="dropdown-item" type="submit"><?php echo $stg; ?></button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Structured Rejection Modal -->
    <div class="modal fade" id="rejectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="process.php" method="POST">
                    <input type="hidden" name="action" value="update_application_status">
                    <input type="hidden" name="status" value="Rejected">
                    <input type="hidden" name="application_id" id="modal_app_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Applicant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Primary Reason</label>
                            <select name="rejection_reason" class="form-select" required>
                                <option value="Skill Mismatch">Skill Mismatch</option>
                                <option value="Lack of Experience">Lack of Experience</option>
                                <option value="Position Filled">Position Filled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Feedback Comment (Increases M3 Score)</label>
                            <textarea name="rejection_comment" class="form-control" rows="3" placeholder="Actionable feedback..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const rejModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        function openRejectionModal(id, name) {
            document.getElementById('modal_app_id').value = id;
            rejModal.show();
        }
    </script>
</body>
</html>