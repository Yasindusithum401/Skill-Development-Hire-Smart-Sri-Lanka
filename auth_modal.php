<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <style>
        .material-symbols-outlined {
        font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
    }
</style>
    </style>
</head>
<body>
    <!-- Login / Sign Up modal  shared across all pages -->
    <div class="modal fade auth-modal-wrap" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-start auth-modal-content">
                <div class="auth-modal-header">
                    <div class="auth-brand">
                        <span class="auth-brand-mark">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="3.5" y="7" width="17" height="12" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
                                <path d="M3.5 12.5h17" stroke="currentColor" stroke-width="1.6"/>
                                <rect x="10.3" y="11" width="3.4" height="3" rx="0.6" fill="currentColor"/>
                            </svg>
                        </span>
                        <h5 class="modal-title fw-bold" id="authModalLabel">Hire Smart Sri Lanka</h5>
                    </div>
                    <button type="button" class="btn-close auth-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 auth-modal-body">
                    <ul class="nav nav-pills nav-justified mb-4 auth-tabs" id="authTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-view" type="button">Login</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="role-tab" data-bs-toggle="pill" data-bs-target="#role-view" type="button">Sign Up</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="authTabsContent">

                        <!-- 1. LOGIN FORM -->
                        <div class="tab-pane fade show active" id="login-view">
                            <form id="loginForm" action="process.php" method="POST" class="auth-form">
                                <input type="hidden" name="action" value="login">
                                <input type="hidden" name="ajax" value="1">

                                <div class="auth-form-alert alert alert-danger d-none" id="loginFormAlert"></div>

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Email Address</label>
                                    <div class="auth-input-wrap">
                                        <svg class="auth-input-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m4.5 6.5 7.5 6 7.5-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                                    </div>
                                </div>

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Password</label>
                                    <div class="auth-input-wrap">
                                        <svg class="auth-input-icon" viewBox="0 0 24 24" width="17" height="17" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="10.5" width="14" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                        <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-center mb-3">
                                    <a href="#" onclick="alert('Password reset link sent to your email.'); return false;" class="text-decoration-none auth-link-muted">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn w-100 py-2 fw-bold auth-btn-primary">
                                    <span class="btn-label">Log In</span>
                                </button>

                                <div class="text-center mt-3">
                                    <p class="text-muted mb-0">Don't have an account? <a href="#" id="goto-signup" class="auth-link">Sign Up</a></p>
                                </div>
                            </form>
                        </div>

                        <!-- 2. ROLE SELECTION VIEW -->
                        <div class="tab-pane fade" id="role-view">
                            <h5 class="text-center mb-4 auth-role-title">Choose Account Type</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="role-card role-card-seeker" style="cursor: pointer;" id="btn-select-seeker">
                                        <span class="role-icon-badge role-icon-badge-seeker">
                                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="6" stroke="currentColor" stroke-width="1.7"/><path d="m19 19-4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="10.5" cy="9" r="2" stroke="currentColor" stroke-width="1.5"/><path d="M7.3 13.2a3.4 3.4 0 0 1 6.4 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        </span>
                                        <h5>Job Seeker</h5>
                                        <p class="small">Looking for jobs and internships</p>
                                        <button type="button" class="btn btn-sm role-card-btn role-card-btn-seeker">Register as Seeker</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="role-card role-card-recruiter" style="cursor: pointer;" id="btn-select-recruiter">
                                        <span class="role-icon-badge role-icon-badge-recruiter">
                                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 20V6.5L12 3l8 3.5V20" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M4 20h16M9 20v-4h6v4" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M9 10h1.5M13.5 10H15M9 13.5h1.5M13.5 13.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        </span>
                                        <h5>Recruiter</h5>
                                        <p class="small">Posting vacancies and hiring talent</p>
                                        <button type="button" class="btn btn-sm role-card-btn role-card-btn-recruiter">Register as Recruiter</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. JOB SEEKER REGISTRATION FORM -->
                        <div class="tab-pane fade" id="seeker-view">
                            <h4 class="text-center mb-3 auth-role-title">Welcome Job Seeker!</h4>
                            <form action="process.php" method="POST" id="seekerForm" class="auth-form">
                                <input type="hidden" name="action" value="register_seeker">

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Account Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" name="contact_email" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="seeker_pass" name="password" class="form-control" required minlength="6">
                                    </div>
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="seeker_cpass" class="form-control" required minlength="6">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Calculated Age</label>
                                        <input type="text" id="calculated_age" class="form-control" readonly placeholder="Auto calculated">
                                    </div>
                                </div>

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" name="mobile_number" class="form-control" placeholder="+94 7X XXX XXXX" required>
                                </div>

                                <button type="submit" class="btn w-100 fw-bold auth-btn-primary">Submit & Register</button>
                                <button type="button" class="btn btn-link w-100 mt-2 auth-link-muted btn-back-role">Back to Selection</button>
                            </form>
                        </div>

                        <!-- 4. RECRUITER REGISTRATION FORM -->
                        <div class="tab-pane fade" id="recruiter-view">
                            <h4 class="text-center mb-3 auth-role-title">Welcome Recruiter!</h4>
                            <form action="process.php" method="POST" id="recruiterForm" class="auth-form">
                                <input type="hidden" name="action" value="register_recruiter">

                                <div class="row">
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Company Type</label>
                                        <select name="company_type" class="form-select" required>
                                            <option value="">Select Type <span class="material-symbols-outlined"></span></option>
                                            <option value="IT/Software">IT / Software</option>
                                            <option value="Finance">Finance / Banking</option>
                                            <option value="Manufacturing">Manufacturing</option>
                                            <option value="Education">Education</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Company Email</label>
                                    <input type="email" name="company_email" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="recruiter_pass" name="password" class="form-control" required minlength="6">
                                    </div>
                                    <div class="col-md-6 mb-3 auth-field">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="recruiter_cpass" class="form-control" required minlength="6">
                                    </div>
                                </div>

                                <div class="mb-3 auth-field">
                                    <label class="form-label">Company Description</label>
                                    <textarea name="company_description" class="form-control" rows="3" required placeholder="Describe your company..."></textarea>
                                </div>

                                <button type="submit" class="btn w-100 fw-bold auth-btn-primary">Submit & Register</button>
                                <button type="button" class="btn btn-link w-100 mt-2 auth-link-muted btn-back-role">Back to Selection</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>