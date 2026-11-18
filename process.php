<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'trust_calculator.php'; // Included for ETSR metric updates

$action = $_POST['action'] ?? '';

function redirect_with_message(string $target, string $status, string $message): void
{
    $query = http_build_query(['status' => $status, 'message' => $message]);
    header("Location: $target?$query");
    exit;
}

function respond_login(bool $ajax, string $status, string $message, array $extra = []): void
{
    if ($ajax) {
        header('Content-Type: application/json');
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
        exit;
    }
    redirect_with_message('index.php', $status, $message);
}

function require_logged_in_json_safe(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect_with_message('index.php', 'error', 'Please log in first.');
    }
}

// Job poster uploads: images only, max 10MB
if (!defined('MAX_POSTER_BYTES')) { define('MAX_POSTER_BYTES', 10 * 1024 * 1024); }
if (!defined('UPLOAD_DIR_POSTER')) { define('UPLOAD_DIR_POSTER', __DIR__ . '/uploads/job_posters/'); }

// If the whole request is bigger than the server's post_max_size, PHP drops $_POST and $_FILES completely.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    redirect_with_message('profile.php', 'error', 'The uploaded file is too large for the server. Please use an image under 10MB.');
}

if ($action === 'register_seeker') {

    $username      = trim($_POST['username'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $dob           = $_POST['dob'] ?? '';
    $mobile        = trim($_POST['mobile_number'] ?? '');

    if ($username === '' || $email === '' || $contact_email === '' || $password === '' || $dob === '' || $mobile === '') {
        redirect_with_message('index.php', 'error', 'Please fill in all required fields.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('index.php', 'error', 'Please enter valid email addresses.');
    }
    if (strlen($password) < 6) {
        redirect_with_message('index.php', 'error', 'Password must be at least 6 characters.');
    }

    $check = $pdo->prepare("SELECT id FROM job_seekers WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        redirect_with_message('index.php', 'error', 'An account with that email already exists. Please log in instead.');
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO job_seekers (username, email, contact_email, password, dob, mobile_number)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$username, $email, $contact_email, $hashed, $dob, $mobile]);

    // Auto-login: sign the new seeker straight in instead of sending them back to the login form.
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$pdo->lastInsertId();
    $_SESSION['role']    = 'seeker';
    $_SESSION['name']    = $username;

    redirect_with_message('index.php', 'success', 'Account created! Welcome, ' . $username . '.');

} elseif ($action === 'register_recruiter') {

    $company_name        = trim($_POST['company_name'] ?? '');
    $company_type        = trim($_POST['company_type'] ?? '');
    $company_email       = trim($_POST['company_email'] ?? '');
    $password            = $_POST['password'] ?? '';
    $company_description = trim($_POST['company_description'] ?? '');

    if ($company_name === '' || $company_type === '' || $company_email === '' || $password === '' || $company_description === '') {
        redirect_with_message('index.php', 'error', 'Please fill in all required fields.');
    }
    if (!filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('index.php', 'error', 'Please enter a valid company email.');
    }
    if (strlen($password) < 6) {
        redirect_with_message('index.php', 'error', 'Password must be at least 6 characters.');
    }

    $check = $pdo->prepare("SELECT id FROM recruiters WHERE company_email = ?");
    $check->execute([$company_email]);
    if ($check->fetch()) {
        redirect_with_message('index.php', 'error', 'An account with that company email already exists. Please log in instead.');
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO recruiters (company_name, company_type, company_email, password, company_description)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$company_name, $company_type, $company_email, $hashed, $company_description]);

    // Auto-login: sign the new recruiter straight in instead of sending them back to the login form.
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$pdo->lastInsertId();
    $_SESSION['role']    = 'recruiter';
    $_SESSION['name']    = $company_name;

    redirect_with_message('index.php', 'success', 'Company account created! Welcome, ' . $company_name . '.');

} elseif ($action === 'login') {

    $ajax     = ($_POST['ajax'] ?? '') === '1';
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        respond_login($ajax, 'error', 'Please enter your email and password.');
    }

    // Try job seekers first
    $stmt = $pdo->prepare("SELECT id, username, password, profile_image FROM job_seekers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = 'seeker';
        $_SESSION['name']    = $user['username'];
        respond_login($ajax, 'success', 'Welcome back, ' . $user['username'] . '!', [
            'role' => 'seeker',
            'name' => $user['username'],
            'profile_image' => $user['profile_image'],
        ]);
    }

    // Then try recruiters
    $stmt = $pdo->prepare("SELECT id, company_name, password, profile_image FROM recruiters WHERE company_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = 'recruiter';
        $_SESSION['name']    = $user['company_name'];
        respond_login($ajax, 'success', 'Welcome back, ' . $user['company_name'] . '!', [
            'role' => 'recruiter',
            'name' => $user['company_name'],
            'profile_image' => $user['profile_image'],
        ]);
    }

    respond_login($ajax, 'error', 'Incorrect email or password.');

} elseif ($action === 'update_profile_seeker') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'seeker') { redirect_with_message('index.php', 'error', 'Not authorized.'); }

    $username      = trim($_POST['username'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $mobile        = trim($_POST['mobile_number'] ?? '');
    $bio           = trim($_POST['bio'] ?? '');

    if ($username === '' || $contact_email === '' || $mobile === '') {
        redirect_with_message('profile.php', 'error', 'Please fill in all required fields.');
    }
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('profile.php', 'error', 'Please enter a valid contact email.');
    }

    $stmt = $pdo->prepare("UPDATE job_seekers SET username=?, contact_email=?, mobile_number=?, bio=? WHERE id=?");
    $stmt->execute([$username, $contact_email, $mobile, $bio, $_SESSION['user_id']]);
    $_SESSION['name'] = $username;

    redirect_with_message('profile.php', 'success', 'Profile updated successfully.');

} elseif ($action === 'update_profile_recruiter') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'recruiter') { redirect_with_message('index.php', 'error', 'Not authorized.'); }

    $company_name        = trim($_POST['company_name'] ?? '');
    $company_type        = trim($_POST['company_type'] ?? '');
    $company_description = trim($_POST['company_description'] ?? '');

    if ($company_name === '' || $company_type === '' || $company_description === '') {
        redirect_with_message('profile.php', 'error', 'Please fill in all required fields.');
    }

    $stmt = $pdo->prepare("UPDATE recruiters SET company_name=?, company_type=?, company_description=? WHERE id=?");
    $stmt->execute([$company_name, $company_type, $company_description, $_SESSION['user_id']]);
    $_SESSION['name'] = $company_name;

    redirect_with_message('profile.php', 'success', 'Company profile updated successfully.');

} elseif ($action === 'upload_avatar') {
    require_logged_in_json_safe();

    try {
        $path = handle_upload('avatar', UPLOAD_DIR_PROFILE, ['jpg', 'jpeg', 'png', 'webp'], MAX_IMAGE_BYTES, $_SESSION['role']);
    } catch (RuntimeException $e) {
        redirect_with_message('profile.php', 'error', $e->getMessage());
    }

    if ($path === null) {
        redirect_with_message('profile.php', 'error', 'No image selected.');
    }

    $table = $_SESSION['role'] === 'seeker' ? 'job_seekers' : 'recruiters';
    $stmt = $pdo->prepare("UPDATE $table SET profile_image=? WHERE id=?");
    $stmt->execute([$path, $_SESSION['user_id']]);

    redirect_with_message('profile.php', 'success', 'Profile photo updated.');

} elseif ($action === 'upload_cv') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'seeker') { redirect_with_message('index.php', 'error', 'Not authorized.'); }

    try {
        $path = handle_upload('cv', UPLOAD_DIR_CV, ['pdf'], MAX_CV_BYTES, 'cv');
    } catch (RuntimeException $e) {
        redirect_with_message('profile.php', 'error', $e->getMessage());
    }

    if ($path === null) {
        redirect_with_message('profile.php', 'error', 'No file selected.');
    }

    $stmt = $pdo->prepare("UPDATE job_seekers SET cv_path=? WHERE id=?");
    $stmt->execute([$path, $_SESSION['user_id']]);

    redirect_with_message('profile.php', 'success', 'CV uploaded successfully.');

} elseif ($action === 'post_job') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'recruiter') { redirect_with_message('index.php', 'error', 'Not authorized.'); }

    $title        = trim($_POST['title'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $jobType      = trim($_POST['job_type'] ?? '');
    $experience   = trim($_POST['experience_level'] ?? '');
    $location     = trim($_POST['location'] ?? '');
    $salaryMin    = $_POST['salary_min'] ?? '';
    $salaryMax    = $_POST['salary_max'] ?? '';
    $description  = trim($_POST['description'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $contactDetails = trim($_POST['contact_details'] ?? '');

    if ($title === '' || $category === '' || $jobType === '' || $experience === '' || $location === '' || $description === '') {
        redirect_with_message('profile.php', 'error', 'Please fill in all required job fields.');
    }
    if (!in_array($category, JOB_CATEGORIES, true) || !in_array($jobType, JOB_TYPES, true) || !in_array($experience, EXPERIENCE_LEVELS, true)) {
        redirect_with_message('profile.php', 'error', 'Invalid category, type, or experience level.');
    }

    if (mb_strlen($contactDetails) > 500) {
        redirect_with_message('profile.php', 'error', 'Company contact details are too long (maximum 500 characters).');
    }

    $salaryMin = ($salaryMin !== '' && is_numeric($salaryMin)) ? (int)$salaryMin : null;
    $salaryMax = ($salaryMax !== '' && is_numeric($salaryMax)) ? (int)$salaryMax : null;

    // Optional job poster image (max 10MB)
    $posterPath = null;
    if (isset($_FILES['job_poster']) && $_FILES['job_poster']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (in_array($_FILES['job_poster']['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            redirect_with_message('profile.php', 'error', 'Job poster is too large. Maximum size is 10MB.');
        }
        try {
            $posterPath = handle_upload('job_poster', UPLOAD_DIR_POSTER, ['jpg', 'jpeg', 'png', 'webp'], MAX_POSTER_BYTES, 'poster');
        } catch (RuntimeException $e) {
            redirect_with_message('profile.php', 'error', 'Job poster: ' . $e->getMessage() . ' (max 10MB)');
        }
    }

    $stmt = $pdo->prepare(
        "INSERT INTO jobs (recruiter_id, title, category, job_type, location, experience_level, salary_min, salary_max, description, requirements, poster_image, contact_details)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$_SESSION['user_id'], $title, $category, $jobType, $location, $experience, $salaryMin, $salaryMax, $description, $requirements ?: null, $posterPath, $contactDetails ?: null]);

    redirect_with_message('profile.php', 'success', 'Job posted successfully.');

} elseif ($action === 'toggle_job_status') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'recruiter') { redirect_with_message('index.php', 'error', 'Not authorized.'); }

    $jobId = (int)($_POST['job_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT status FROM jobs WHERE id=? AND recruiter_id=?");
    $stmt->execute([$jobId, $_SESSION['user_id']]);
    $job = $stmt->fetch();

    if (!$job) {
        redirect_with_message('profile.php', 'error', 'Job not found.');
    }

    $newStatus = $job['status'] === 'open' ? 'closed' : 'open';
    $update = $pdo->prepare("UPDATE jobs SET status=? WHERE id=?");
    $update->execute([$newStatus, $jobId]);

    redirect_with_message('profile.php', 'success', 'Job marked as ' . $newStatus . '.');

} elseif ($action === 'apply_job') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'seeker') { redirect_with_message('jobs.php', 'error', 'Only job seeker accounts can apply.'); }

    $jobId = (int)($_POST['job_id'] ?? 0);

    $seekerStmt = $pdo->prepare("SELECT cv_path FROM job_seekers WHERE id=?");
    $seekerStmt->execute([$_SESSION['user_id']]);
    $seeker = $seekerStmt->fetch();

    if (empty($seeker['cv_path'])) {
        redirect_with_message('profile.php', 'error', 'Please upload your CV before applying to jobs.');
    }

    $jobStmt = $pdo->prepare("SELECT id FROM jobs WHERE id=? AND status='open'");
    $jobStmt->execute([$jobId]);
    if (!$jobStmt->fetch()) {
        redirect_with_message('jobs.php', 'error', 'This job is no longer open.');
    }

    try {
        $insert = $pdo->prepare("INSERT INTO applications (job_id, seeker_id) VALUES (?, ?)");
        $insert->execute([$jobId, $_SESSION['user_id']]);
        $appId = $pdo->lastInsertId();

        // Create initial history trail entry
        $hist = $pdo->prepare("INSERT INTO application_status_history (application_id, status) VALUES (?, 'Applied')");
        $hist->execute([$appId]);

        redirect_with_message('jobs.php', 'success', 'Application submitted!');
    } catch (PDOException $e) {
        redirect_with_message('jobs.php', 'error', 'You have already applied to this job.');
    }

// UPDATE APPLICATION MILESTONE STATUS (Recruiter Action)
} elseif ($action === 'update_application_status') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'recruiter') { redirect_with_message('dashboard.php', 'error', 'Not authorized.'); }

    $applicationId   = (int)($_POST['application_id'] ?? 0);
    $newStatus       = trim($_POST['status'] ?? '');
    $rejectionReason = $_POST['rejection_reason'] ?? null;
    $rejectionComment = $_POST['rejection_comment'] ?? null;

    $allowed = ['Applied', 'In Review', 'HR Screening', 'Interview', 'Selected', 'Rejected'];
    if (!$applicationId || !in_array($newStatus, $allowed, true)) {
        redirect_with_message('dashboard.php', 'error', 'Invalid status update selection.');
    }

    // Verify recruiter owns the job posting
    $stmtCheck = $pdo->prepare(
        "SELECT a.id, j.recruiter_id 
         FROM applications a 
         JOIN jobs j ON j.id = a.job_id 
         WHERE a.id = ? AND j.recruiter_id = ?"
    );
    $stmtCheck->execute([$applicationId, $_SESSION['user_id']]);
    if (!$stmtCheck->fetch()) {
        redirect_with_message('dashboard.php', 'error', 'Unauthorized operation.');
    }

    // Update state
    $stmtUp = $pdo->prepare("UPDATE applications SET current_status = ? WHERE id = ?");
    $stmtUp->execute([$newStatus, $applicationId]);

    // Record transition audit history
    $stmtHist = $pdo->prepare(
        "INSERT INTO application_status_history (application_id, status, rejection_reason, rejection_comment) 
         VALUES (?, ?, ?, ?)"
    );
    $stmtHist->execute([$applicationId, $newStatus, $rejectionReason, $rejectionComment]);

    // Recalculate and update the employer's cached ETSR trust score
    recalculateEmployerTrustScore($pdo, (int)$_SESSION['user_id']);

    redirect_with_message('dashboard.php', 'success', 'Application status updated.');

// SUBMIT EMPLOYER STAR RATING & REVIEW (Candidate Action)
} elseif ($action === 'submit_employer_review') {
    require_logged_in_json_safe();
    if ($_SESSION['role'] !== 'seeker') { redirect_with_message('my_applications.php', 'error', 'Not authorized.'); }

    $recruiterId = (int)($_POST['recruiter_id'] ?? 0);
    $rating      = (int)($_POST['rating'] ?? 0);
    $comment     = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5 || $recruiterId === 0) {
        redirect_with_message('my_applications.php', 'error', 'Please provide a valid rating (1-5 stars).');
    }

    // A job seeker can rate an employer only after the employer has made a final decision
    // (Selected or Rejected) on one of that seeker's applications.
    $stmtApplied = $pdo->prepare(
        "SELECT 1 FROM applications a JOIN jobs j ON j.id = a.job_id
         WHERE a.seeker_id = ? AND j.recruiter_id = ?
           AND a.current_status IN ('Selected', 'Rejected') LIMIT 1"
    );
    $stmtApplied->execute([$_SESSION['user_id'], $recruiterId]);
    if (!$stmtApplied->fetchColumn()) {
        redirect_with_message('my_applications.php', 'error', 'You can rate an employer only after they have selected or rejected your application.');
    }

    $stmtRev = $pdo->prepare(
        "INSERT INTO employer_reviews (recruiter_id, seeker_id, rating, comment) 
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment)"
    );
    $stmtRev->execute([$recruiterId, $_SESSION['user_id'], $rating, $comment]);

    // The rating now feeds into the employer's trust score
    recalculateEmployerTrustScore($pdo, $recruiterId);

    redirect_with_message('my_applications.php', 'success', 'Review submitted successfully.');

} else {
    redirect_with_message('index.php', 'error', 'Invalid request.');
}