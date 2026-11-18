<?php
require_once 'auth_check.php';
require_login();
require_once 'config.php';

$role = $_SESSION['role'];
$userId = (int)$_SESSION['user_id'];
$loggedIn = true;

if ($role === 'seeker') {
    $stmt = $pdo->prepare("SELECT * FROM job_seekers WHERE id = ?");
    $stmt->execute([$userId]);
    $profile = $stmt->fetch();

    $appsStmt = $pdo->prepare(
        "SELECT a.applied_at, j.title, j.location, j.job_type, j.status AS job_status, r.company_name
         FROM applications a
         JOIN jobs j ON j.id = a.job_id
         JOIN recruiters r ON r.id = j.recruiter_id
         WHERE a.seeker_id = ?
         ORDER BY a.applied_at DESC"
    );
    $appsStmt->execute([$userId]);
    $myApplications = $appsStmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM recruiters WHERE id = ?");
    $stmt->execute([$userId]);
    $profile = $stmt->fetch();

    $jobsStmt = $pdo->prepare(
        "SELECT j.*, (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) AS applicant_count
         FROM jobs j WHERE j.recruiter_id = ? ORDER BY j.posted_at DESC"
    );
    $jobsStmt->execute([$userId]);
    $myJobs = $jobsStmt->fetchAll();
}

if (!$profile) {
    // Session pointed at an account that no longer exists.
    header('Location: logout.php');
    exit;
}

$userName = $_SESSION['name'];
$profileImage = $profile['profile_image'] ?? null;
$statusMsg = $_GET['message'] ?? '';
$statusType = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Hire Smart Sri Lanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel ="stylesheet" href="profile.css">
</head>
<body>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>
    <div class="shape shape4"></div>
    <div class="shape shape5"></div>
    <div class="shape shape6"></div>

    <?php include 'navbar.php'; ?>

    <section class="profile-page">

        <?php if ($statusMsg): ?>
            <div class="job-flash <?php echo $statusType === 'success' ? 'job-flash-success' : 'job-flash-error'; ?>">
                <?php echo htmlspecialchars($statusMsg); ?>
            </div>
        <?php endif; ?>

        <!-- PROFILE HEADER CARD  -->
        <div class="profile-card">
            <form action="process.php" method="POST" enctype="multipart/form-data" class="profile-avatar-form">
                <input type="hidden" name="action" value="upload_avatar">
                <div class="profile-avatar-wrap">
                    <?php if ($profileImage): ?>
                        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="profile photo" class="profile-avatar-img">
                    <?php else: ?>
                        <span class="profile-avatar-initial"><?php echo htmlspecialchars(strtoupper(substr($userName, 0, 1))); ?></span>
                    <?php endif; ?>
                    <label for="avatarUpload" class="profile-avatar-edit" title="Change photo">✎</label>
                    <input type="file" id="avatarUpload" name="avatar" accept=".jpg,.jpeg,.png,.webp" onchange="this.form.submit()" hidden>
                </div>
            </form>
            <div class="profile-card-info">
                <h1><?php echo htmlspecialchars($userName); ?></h1>
                <span class="profile-role-badge profile-role-<?php echo $role; ?>"><?php echo $role === 'seeker' ? 'Job Seeker' : 'Recruiter'; ?></span>
                <?php if ($role === 'seeker'): ?>
                    <p class="profile-card-sub"><?php echo htmlspecialchars($profile['email']); ?></p>
                <?php else: ?>
                    <p class="profile-card-sub"><?php echo htmlspecialchars($profile['company_email']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($role === 'seeker'): ?>
            <!--  SEEKER: EDIT DETAILS -->
            <div class="profile-section">
                <h2>My Details</h2>
                <form action="process.php" method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_profile_seeker">
                    <div class="row">
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($profile['username']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($profile['contact_email']); ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Mobile Number</label>
                            <input type="tel" name="mobile_number" class="form-control" value="<?php echo htmlspecialchars($profile['mobile_number']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Login Email (fixed)</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($profile['email']); ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">About Me</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="A short summary about your experience and goals..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn fw-bold auth-btn-primary" style="padding:10px 28px;background:#ff7a00;color:white;">Save Changes</button>
                </form>
            </div>

            <!-- SEEKER: CV UPLOAD -->
            <div class="profile-section">
                <h2>My CV</h2>
                <?php if (!empty($profile['cv_path'])): ?>
                    <p class="profile-cv-current">
                         Current CV: <a href="<?php echo htmlspecialchars($profile['cv_path']); ?>" target="_blank">View / Download</a>
                    </p>
                <?php else: ?>
                    <p class="profile-cv-current">You haven't uploaded a CV yet.</p>
                <?php endif; ?>
                <form action="process.php" method="POST" enctype="multipart/form-data" class="profile-form-inline">
                    <input type="hidden" name="action" value="upload_cv">
                    <input type="file" name="cv" accept="application/pdf" class="form-control" required style="max-width:340px;">
                    <button type="submit" class="btn fw-bold auth-btn-primary" style="background:#ff7a00;color:white;">Upload PDF CV</button>
                </form>
                <small class="text-muted">PDF only, max 5MB. Uploading a new file replaces your current CV.</small>
            </div>

            <!--  SEEKER: MY APPLICATIONS  -->
            <div class="profile-section">
                <h2>My Applications</h2>
                <?php if (empty($myApplications)): ?>
                    <p class="text-muted">You haven't applied to any jobs yet. <a href="jobs.php">Browse jobs</a></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table profile-table">
                            <thead><tr><th>Job Title</th><th>Company</th><th>Location</th><th>Type</th><th>Applied</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($myApplications as $app): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($app['title']); ?></td>
                                        <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($app['location']); ?></td>
                                        <td><?php echo htmlspecialchars($app['job_type']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($app['applied_at'])); ?></td>
                                        <td><span class="job-tag <?php echo $app['job_status'] === 'open' ? 'job-tag-type' : ''; ?>"><?php echo ucfirst($app['job_status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!--  RECRUITER: EDIT COMPANY DETAILS  -->
            <div class="profile-section">
                <h2>Company Details</h2>
                <form action="process.php" method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_profile_recruiter">
                    <div class="row">
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($profile['company_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Company Type</label>
                            <select name="company_type" class="form-select" required>
                                <?php foreach (['IT/Software','Finance','Manufacturing','Education','Other'] as $type): ?>
                                    <option value="<?php echo $type; ?>" <?php echo $profile['company_type'] === $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Company Email (fixed)</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($profile['company_email']); ?>" readonly>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Company Description</label>
                        <textarea name="company_description" class="form-control" rows="3" required><?php echo htmlspecialchars($profile['company_description']); ?></textarea>
                    </div>
                    <button type="submit" class="btn fw-bold auth-btn-primary" style="padding:10px 28px;background:#ff7a00;color:white;">Save Changes</button>
                </form>
            </div>

            <!--  RECRUITER: POST A JOB  -->
            <div class="profile-section">
                <h2>Post a New Job</h2>
                <form action="process.php" method="POST" enctype="multipart/form-data" class="profile-form">
                    <input type="hidden" name="action" value="post_job">
                    <div class="row">
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select category</option>
                                <?php foreach (JOB_CATEGORIES as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 auth-field">
                            <label class="form-label">Job Type</label>
                            <select name="job_type" class="form-select" required>
                                <?php foreach (JOB_TYPES as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 auth-field">
                            <label class="form-label">Experience Level</label>
                            <select name="experience_level" class="form-select" required>
                                <?php foreach (EXPERIENCE_LEVELS as $lvl): ?>
                                    <option value="<?php echo htmlspecialchars($lvl); ?>"><?php echo htmlspecialchars($lvl); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 auth-field">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Colombo" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Min Salary (LKR, optional)</label>
                            <input type="number" name="salary_min" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3 auth-field">
                            <label class="form-label">Max Salary (LKR, optional)</label>
                            <input type="number" name="salary_max" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Job Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Requirements (optional)</label>
                        <textarea name="requirements" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Company Contact Details (optional)</label>
                        <textarea name="contact_details" class="form-control" rows="2" maxlength="500" placeholder="e.g. Phone: 011 234 5678 | Email: hr@company.lk | Address"></textarea>
                        <small class="text-muted">Shown to job seekers on the job. Maximum 500 characters.</small>
                    </div>
                    <div class="mb-3 auth-field">
                        <label class="form-label">Job Poster Image (optional)</label>
                        <input type="file" name="job_poster" id="job_poster" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="text-muted">JPG, PNG or WEBP. Maximum size 10MB.</small>
                    </div>
                    <button type="submit" class="btn fw-bold auth-btn-primary" style="padding:10px 28px;background:#ff7a00;color:white;">Publish Job</button>
                </form>
            </div>

            <!--  RECRUITER: MY JOB POSTINGS  -->
            <div class="profile-section">
                <h2>My Job Postings</h2>
                <?php if (empty($myJobs)): ?>
                    <p class="text-muted">You haven't posted any jobs yet.</p>
                <?php else: ?>
                    <?php foreach ($myJobs as $job): ?>
                        <div class="my-job-row">
                            <div class="my-job-row-main">
                                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                                <span class="job-tag <?php echo $job['status'] === 'open' ? 'job-tag-type' : ''; ?>"><?php echo ucfirst($job['status']); ?></span>
                                <span class="text-muted"> . <?php echo (int)$job['applicant_count']; ?> applicant<?php echo $job['applicant_count'] == 1 ? '' : 's'; ?></span>
                                <span class="text-muted"> . Posted <?php echo date('M j, Y', strtotime($job['posted_at'])); ?></span>
                            </div>
                            <div class="my-job-row-actions">
                                <form action="process.php" method="POST">
                                    <input type="hidden" name="action" value="toggle_job_status">
                                    <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                    <button type="submit" class="job-apply-btn job-manage-btn">
                                        <?php echo $job['status'] === 'open' ? 'Close Job' : 'Reopen Job'; ?>
                                    </button>
                                </form>
                                <?php if ((int)$job['applicant_count'] > 0): ?>
                                    <button class="job-apply-btn job-manage-btn" type="button" data-bs-toggle="collapse" data-bs-target="#applicants-<?php echo $job['id']; ?>">
                                        View Applicants
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if ((int)$job['applicant_count'] > 0): ?>
                                <div class="collapse my-job-applicants" id="applicants-<?php echo $job['id']; ?>">
                                    <?php
                                    $appStmt = $pdo->prepare(
                                        "SELECT s.username, s.contact_email, s.cv_path, a.applied_at
                                         FROM applications a JOIN job_seekers s ON s.id = a.seeker_id
                                         WHERE a.job_id = ? ORDER BY a.applied_at DESC"
                                    );
                                    $appStmt->execute([$job['id']]);
                                    $applicants = $appStmt->fetchAll();
                                    ?>
                                    <table class="table profile-table">
                                        <thead><tr><th>Name</th><th>Contact Email</th><th>Applied</th><th>CV</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($applicants as $a): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($a['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($a['contact_email']); ?></td>
                                                    <td><?php echo date('M j, Y', strtotime($a['applied_at'])); ?></td>
                                                    <td>
                                                        <?php if ($a['cv_path']): ?>
                                                            <a href="<?php echo htmlspecialchars($a['cv_path']); ?>" target="_blank">Download</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Not uploaded</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Job poster: block files larger than 10MB before uploading
        (function () {
            var input = document.getElementById('job_poster');
            if (!input) return;
            input.addEventListener('change', function () {
                var f = input.files[0];
                if (f && f.size > 10 * 1024 * 1024) {
                    alert('The job poster is too large. Maximum size is 10MB.');
                    input.value = '';
                }
            });
        })();
    </script>
</body>
</html>