<?php
require_once 'auth_check.php';
require_once 'config.php';

$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
$role = $_SESSION['role'] ?? null;

$jobs = [];
$appliedJobIds = [];

function trust_chip(?string $score, ?string $badge): string {
    if ($score === null || $badge === null || $badge === 'New Employer') {
        return '<span class="trust-chip trust-none">🛡 Trust: New employer</span>';
    }
    $v = (float)$score;
    if ($badge === 'Insufficient Data') {
        return '<span class="trust-chip trust-none">🛡 Trust: ' . round($v) . '% (limited data)</span>';
    }
    $cls = $v >= 75 ? 'trust-high' : ($v >= 50 ? 'trust-mid' : 'trust-low');
    return '<span class="trust-chip ' . $cls . '">🛡 Trust: ' . round($v) . '%</span>';
}

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

    $sql = "SELECT j.*, r.company_name, r.profile_image AS company_logo,
                   ts.overall_trust_score, ts.display_badge
            FROM jobs j
            JOIN recruiters r ON r.id = j.recruiter_id
            LEFT JOIN employer_trust_scores ts ON ts.recruiter_id = r.id
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
    <style>
        .trust-chip{display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;padding:4px 10px;border-radius:999px;margin:6px 0 2px}
        .trust-high{background:#e6f6ec;color:#146c2e}
        .trust-mid{background:#fff4d6;color:#8a5a00}
        .trust-low{background:#fde8e8;color:#a12020}
        .trust-none{background:#eef0f3;color:#5b6470}
        .job-view-link{text-decoration:none}
        .job-card-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap;justify-content:flex-end}
        .jv-poster{width:100%;max-height:420px;object-fit:contain;background:#f6f1ea;border-radius:12px;margin-bottom:16px}
        .jv-text{white-space:pre-line}
        .jv-poster{cursor:zoom-in}
        .jv-lightbox{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:2000;display:none;overflow:auto}
        .jv-lightbox.open{display:flex}
        .jv-lightbox img{margin:auto;max-width:96vw;max-height:94vh;object-fit:contain;cursor:zoom-in}
        .jv-lightbox img.actual{max-width:none;max-height:none;cursor:zoom-out}
        .jv-lb-btn{position:fixed;top:14px;z-index:2001;border:none;border-radius:10px;padding:8px 16px;font-size:14px;font-weight:700;background:#fff;color:#222;text-decoration:none;cursor:pointer}
        .jv-lb-close{right:16px}
        .jv-lb-open{left:16px}
        .jv-lb-hint{position:fixed;bottom:12px;left:0;right:0;text-align:center;color:#ddd;font-size:13px;pointer-events:none;z-index:2001}
    </style>
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
                                    <h3 class="job-card-title"><a class="job-view-link text-reset" href="job_details.php?id=<?php echo (int)$job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></a></h3>
                                    <p class="job-card-company"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                </div>
                            </div>

                            <div class="job-card-tags">
                                <span class="job-tag job-tag-type"><?php echo htmlspecialchars($job['job_type']); ?></span>
                                <span class="job-tag"><?php echo htmlspecialchars($job['category']); ?></span>
                                <span class="job-tag"><?php echo htmlspecialchars($job['experience_level']); ?></span>
                            </div>

                            <p class="job-card-location">📍 <?php echo htmlspecialchars($job['location']); ?></p>
                            <?php echo trust_chip($job['overall_trust_score'], $job['display_badge']); ?>

                            <?php if ($job['salary_min'] || $job['salary_max']): ?>
                                <p class="job-card-salary">
                                    LKR <?php echo number_format((float)$job['salary_min']); ?> – <?php echo number_format((float)$job['salary_max']); ?>
                                </p>
                            <?php endif; ?>

                            <p class="job-card-desc"><?php echo htmlspecialchars(mb_strimwidth($job['description'], 0, 150, '…')); ?></p>

                            <div class="job-card-footer">
                                <span class="job-card-posted"><?php echo date('M j, Y', strtotime($job['posted_at'])); ?></span>

                                <div class="job-card-actions">
                                <button type="button" class="job-apply-btn job-manage-btn" onclick="openJobView(<?php echo (int)$job['id']; ?>)">View</button>
                                <?php if ($role === 'seeker'): ?>
                                    <?php if (in_array($job['id'], $appliedJobIds, true)): ?>
                                        <button class="job-apply-btn job-applied" disabled>Applied ✓</button>
                                    <?php else: ?>
                                        <a href="job_details.php?id=<?php echo (int)$job['id']; ?>" class="job-apply-btn">View &amp; Apply</a>
                                    <?php endif; ?>
                                <?php elseif ($role === 'recruiter' && (int)$job['recruiter_id'] === (int)$_SESSION['user_id']): ?>
                                    <a href="profile.php" class="job-apply-btn job-manage-btn">Manage on Profile</a>
                                <?php endif; ?>
                                </div>
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

    <?php if ($loggedIn && !empty($jobs)): ?>
        <?php
        $jobData = [];
        foreach ($jobs as $j) {
            $jobData[(int)$j['id']] = [
                'id'          => (int)$j['id'],
                'title'       => $j['title'],
                'company'     => $j['company_name'],
                'location'    => $j['location'],
                'category'    => $j['category'],
                'job_type'    => $j['job_type'],
                'experience'  => $j['experience_level'],
                'salary'      => ($j['salary_min'] || $j['salary_max'])
                                    ? 'LKR ' . number_format((float)$j['salary_min']) . ' – ' . number_format((float)$j['salary_max'])
                                    : '',
                'description' => $j['description'],
                'requirements'=> $j['requirements'] ?? '',
                'poster'      => $j['poster_image'] ?? '',
                'contact'     => $j['contact_details'] ?? '',
                'posted'      => date('M j, Y', strtotime($j['posted_at'])),
                'trust'       => trust_chip($j['overall_trust_score'], $j['display_badge']),
            ];
        }
        ?>
        <!-- Job view popup -->
        <div class="modal fade" id="jobViewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title fw-bold" id="jv-title"></h5>
                            <div class="text-muted small" id="jv-company"></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="jv-poster" class="jv-poster" alt="Job poster" style="display:none;" onclick="openPosterFull()" title="Click to view full size">
                        <div id="jv-poster-actions" class="mb-3" style="display:none;margin-top:-6px;">
                            <button type="button" class="job-apply-btn job-manage-btn" onclick="openPosterFull()">🔍 View Full Size</button>
                        </div>
                        <div class="job-card-tags mb-2" id="jv-tags"></div>
                        <p class="mb-1" id="jv-location"></p>
                        <p class="mb-1 fw-bold" id="jv-salary"></p>
                        <div id="jv-trust"></div>
                        <h6 class="fw-bold mt-3">Job Description</h6>
                        <p class="jv-text" id="jv-description"></p>
                        <div id="jv-req-wrap">
                            <h6 class="fw-bold mt-3">Requirements</h6>
                            <p class="jv-text" id="jv-requirements"></p>
                        </div>
                        <div id="jv-contact-wrap">
                            <h6 class="fw-bold mt-3">Company Contact Details</h6>
                            <p class="jv-text" id="jv-contact"></p>
                        </div>
                        <p class="text-muted small mb-0" id="jv-posted"></p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="jv-details-link" class="job-apply-btn">Full Details &amp; Apply</a>
                        <button type="button" class="job-apply-btn job-manage-btn" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Full size poster viewer -->
        <div class="jv-lightbox" id="jv-lightbox">
            <a href="#" target="_blank" rel="noopener" class="jv-lb-btn jv-lb-open" id="jv-lb-open">Open original ↗</a>
            <button type="button" class="jv-lb-btn jv-lb-close" id="jv-lb-close">✕ Close</button>
            <img id="jv-lb-img" alt="Job poster full size">
            <div class="jv-lb-hint">Click the image to zoom in / out</div>
        </div>
        <script>
            const JOB_DATA = <?php echo json_encode($jobData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;
            var jvLightbox = document.getElementById('jv-lightbox');
            var jvLbImg = document.getElementById('jv-lb-img');
            function openPosterFull() {
                var src = document.getElementById('jv-poster').getAttribute('src');
                if (!src) return;
                jvLbImg.src = src;
                jvLbImg.classList.remove('actual');
                document.getElementById('jv-lb-open').href = src;
                jvLightbox.classList.add('open');
            }
            function closePosterFull() { jvLightbox.classList.remove('open'); }
            jvLbImg.addEventListener('click', function () { jvLbImg.classList.toggle('actual'); });
            document.getElementById('jv-lb-close').addEventListener('click', closePosterFull);
            jvLightbox.addEventListener('click', function (e) { if (e.target === jvLightbox) closePosterFull(); });
            // Esc closes only the full size viewer first (not the whole popup)
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && jvLightbox.classList.contains('open')) {
                    e.stopImmediatePropagation();
                    closePosterFull();
                }
            }, true);

            function openJobView(id) {
                var j = JOB_DATA[id];
                if (!j) return;
                var set = function (el, text) { document.getElementById(el).textContent = text || ''; };
                set('jv-title', j.title);
                set('jv-company', j.company);
                set('jv-location', '📍 ' + j.location);
                set('jv-salary', j.salary);
                set('jv-description', j.description);
                set('jv-requirements', j.requirements);
                set('jv-contact', j.contact);
                document.getElementById('jv-contact-wrap').style.display = j.contact ? '' : 'none';
                set('jv-posted', 'Posted ' + j.posted);
                document.getElementById('jv-req-wrap').style.display = j.requirements ? '' : 'none';
                document.getElementById('jv-trust').innerHTML = j.trust; // built on the server by trust_chip()

                var tags = document.getElementById('jv-tags');
                tags.innerHTML = '';
                [j.job_type, j.category, j.experience].forEach(function (t, i) {
                    var s = document.createElement('span');
                    s.className = 'job-tag' + (i === 0 ? ' job-tag-type' : '');
                    s.textContent = t;
                    tags.appendChild(s);
                });

                var img = document.getElementById('jv-poster');
                var actions = document.getElementById('jv-poster-actions');
                if (j.poster) { img.src = j.poster; img.style.display = ''; actions.style.display = ''; }
                else { img.removeAttribute('src'); img.style.display = 'none'; actions.style.display = 'none'; }

                document.getElementById('jv-details-link').href = 'job_details.php?id=' + j.id;
                new bootstrap.Modal(document.getElementById('jobViewModal')).show();
            }
        </script>
    <?php endif; ?>

    <?php include 'auth_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
    <?php if (!$loggedIn): ?>
    <script>document.addEventListener('DOMContentLoaded', function(){ new bootstrap.Modal(document.getElementById('authModal')).show(); });</script>
    <?php endif; ?>
</body>
</html>