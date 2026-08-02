document.addEventListener('DOMContentLoaded', function () {
    const authModalElement = document.getElementById('authModal');
    if (!authModalElement) return; 
    const authModal = new bootstrap.Modal(authModalElement);

    // Open modal for the Login button AND any guarded nav link (Jobs/CV Builder/About while logged out)
    document.body.addEventListener('click', function (e) {
        const trigger = e.target.closest('.open-login-modal');
        if (trigger) {
            e.preventDefault();
            authModal.show();
        }
    });

    function showTab(paneId) {
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
        const pane = document.getElementById(paneId);
        if (pane) pane.classList.add('show', 'active');
    }

    const gotoSignup = document.getElementById('goto-signup');
    if (gotoSignup) {
        gotoSignup.addEventListener('click', function (e) {
            e.preventDefault();
            const roleTab = new bootstrap.Tab(document.getElementById('role-tab'));
            roleTab.show();
        });
    }

    const btnSeeker = document.getElementById('btn-select-seeker');
    if (btnSeeker) btnSeeker.addEventListener('click', () => showTab('seeker-view'));

    const btnRecruiter = document.getElementById('btn-select-recruiter');
    if (btnRecruiter) btnRecruiter.addEventListener('click', () => showTab('recruiter-view'));

    document.querySelectorAll('.btn-back-role').forEach(btn => {
        btn.addEventListener('click', () => showTab('role-view'));
    });

    // Auto-calculate age from date of birth
    const dobInput = document.getElementById('dob');
    if (dobInput) {
        dobInput.addEventListener('change', function () {
            const dobVal = this.value;
            if (!dobVal) return;
            const dob = new Date(dobVal);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) age--;
            document.getElementById('calculated_age').value = age > 0 ? age + " Years" : "0 Years";
        });
    }

    // Client-side password confirmation
    const seekerForm = document.getElementById('seekerForm');
    if (seekerForm) {
        seekerForm.addEventListener('submit', function (e) {
            const pass = document.getElementById('seeker_pass').value;
            const cpass = document.getElementById('seeker_cpass').value;
            if (pass !== cpass) {
                e.preventDefault();
                alert("Job Seeker Passwords do not match!");
            }
        });
    }

    const recruiterForm = document.getElementById('recruiterForm');
    if (recruiterForm) {
        recruiterForm.addEventListener('submit', function (e) {
            const pass = document.getElementById('recruiter_pass').value;
            const cpass = document.getElementById('recruiter_cpass').value;
            if (pass !== cpass) {
                e.preventDefault();
                alert("Recruiter Passwords do not match!");
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('loginFormAlert');
            const submitBtn = loginForm.querySelector('button[type="submit"] .btn-label');
            alertBox.classList.add('d-none');
            if (submitBtn) submitBtn.textContent = 'Logging in...';

            fetch('process.php', {
                method: 'POST',
                body: new FormData(loginForm),
            })
                .then(res => res.json())
                .then(data => {
                    if (submitBtn) submitBtn.textContent = 'Log In';
                    if (data.status === 'success') {
                        updateNavbarLoggedIn(data.name, data.profile_image);
                        authModal.hide();
                        loginForm.reset();
                    } else {
                        alertBox.textContent = data.message || 'Login failed.';
                        alertBox.classList.remove('d-none');
                    }
                })
                .catch(() => {
                    if (submitBtn) submitBtn.textContent = 'Log In';
                    alertBox.textContent = 'Something went wrong. Please try again.';
                    alertBox.classList.remove('d-none');
                });
        });
    }

    function updateNavbarLoggedIn(name, profileImage) {
        const navAuthArea = document.getElementById('navAuthArea');
        if (!navAuthArea) return;

        const initial = name ? name.charAt(0).toUpperCase() : '?';
        const avatarInner = profileImage
            ? `<img src="${profileImage}" alt="profile photo">`
            : `<span class="navuser-avatar-initial">${initial}</span>`;

        navAuthArea.innerHTML = `
            <div class="navuser dropdown">
                <button class="navuser-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navuser-avatar">${avatarInner}</span>
                    <span class="navuser-name">${escapeHtml(name)}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end navuser-menu">
                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="jobs.php">Browse Jobs</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>`;

        document.querySelectorAll('[data-nav-guard="1"]').forEach(link => {
            link.removeAttribute('data-nav-guard');
            link.classList.remove('open-login-modal');
            const label = link.textContent.trim();
            if (label === 'Jobs') link.setAttribute('href', 'jobs.php');
            else if (label === 'CV Builder') link.setAttribute('href', 'cv_builder.php');
            else if (label === 'About') link.setAttribute('href', 'about.php');
        });

        document.querySelectorAll('.open-login-modal.loginbtn2').forEach(btn => {
            const a = document.createElement('a');
            a.href = 'jobs.php';
            a.className = 'loginbtn2';
            a.textContent = 'Go to Jobs';
            btn.replaceWith(a);
        });
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');
    const message = params.get('message');
    const hasOwnFlashBanner = document.querySelector('.job-flash') !== null;

    if (status && message) {
        if (!hasOwnFlashBanner) {
            alert(message);
            if (status === 'error' && message.toLowerCase().includes('log in')) {
                authModal.show();
            }
        }
        params.delete('status');
        params.delete('message');
        const cleanQuery = params.toString();
        const newUrl = window.location.pathname + (cleanQuery ? '?' + cleanQuery : '');
        window.history.replaceState({}, document.title, newUrl);
    }
});
