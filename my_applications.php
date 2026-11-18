<?php
require_once 'auth_check.php';
require_login();

if ($_SESSION['role'] !== 'seeker') {
    header('Location: profile.php');
    exit;
}

require_once 'config.php';

$seekerId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT a.id AS application_id, a.current_status, a.applied_at, a.updated_at,
            j.title AS job_title, j.id AS job_id,
            r.id AS recruiter_id, r.company_name,
            rev.rating AS my_rating, rev.comment AS my_review,
            rej.rejection_reason, rej.rejection_comment
     FROM applications a
     JOIN jobs j ON j.id = a.job_id
     JOIN recruiters r ON r.id = j.recruiter_id
     LEFT JOIN employer_reviews rev ON rev.recruiter_id = r.id AND rev.seeker_id = ?
     LEFT JOIN (
         SELECT h1.application_id, h1.rejection_reason, h1.rejection_comment
         FROM application_status_history h1
         WHERE h1.status = 'Rejected'
           AND h1.changed_at = (
               SELECT MAX(h2.changed_at)
               FROM application_status_history h2
               WHERE h2.application_id = h1.application_id AND h2.status = 'Rejected'
           )
     ) rej ON rej.application_id = a.id
     WHERE a.seeker_id = ?
     ORDER BY a.updated_at DESC"
);
$stmt->execute([$seekerId, $seekerId]);
$myApps = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>
    <div class="shape shape4"></div>
    <div class="shape shape5"></div>
    <div class="shape shape6"></div>

    <div class="container py-4">
        <h1 class="fw-bold mb-4">Application Progress & Reviews</h1>

        <div class="card p-3 shadow-sm">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Rejection Feedback</th>
                        <th>Employer Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myApps as $app): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($app['job_title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($app['current_status']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                            <td>
                                <?php if ($app['current_status'] === 'Rejected' && ($app['rejection_reason'] || $app['rejection_comment'])): ?>
                                    <?php if ($app['rejection_reason']): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1 d-inline-block"><?php echo htmlspecialchars($app['rejection_reason']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($app['rejection_comment']): ?>
                                        <div class="small text-muted"><?php echo nl2br(htmlspecialchars($app['rejection_comment'])); ?></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($app['my_rating']): ?>
                                    <span class="text-warning fw-bold">★ <?php echo $app['my_rating']; ?>/5</span>
                                <?php elseif (!in_array($app['current_status'], ['Selected', 'Rejected'], true)): ?>
                                    <span class="small text-muted">Available after the employer decides</span>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-warning" onclick="openReviewModal(<?php echo $app['recruiter_id']; ?>, '<?php echo htmlspecialchars(addslashes($app['company_name'])); ?>')">
                                        Rate Employer
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Candidate Review Submission Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="process.php" method="POST">
                    <input type="hidden" name="action" value="submit_employer_review">
                    <input type="hidden" name="recruiter_id" id="rev_recruiter_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Rate <span id="company_title"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating (1 to 5 Stars)</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">5 - Excellent Experience</option>
                                <option value="4">4 - Good Responsiveness</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Slow Response</option>
                                <option value="1">1 - Unresponsive / Poor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Comments (Optional)</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience during the recruitment process..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning fw-bold">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const revModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        function openReviewModal(recruiterId, companyName) {
            document.getElementById('rev_recruiter_id').value = recruiterId;
            document.getElementById('company_title').textContent = companyName;
            revModal.show();
        }
    </script>
</body>
</html>