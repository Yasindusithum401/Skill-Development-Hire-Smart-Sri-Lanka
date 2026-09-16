document.addEventListener('DOMContentLoaded', function () {
    const authModalElement = document.getElementById('authModal');
    if (!authModalElement) return; 
    const authModal = new bootstrap.Modal(authModalElement);

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

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const alertBox = document.getElementById('loginFormAlert');
            const submitBtn = loginForm.querySelector('button[type="submit"] .btn-label');
            if (alertBox) alertBox.classList.add('d-none');
            if (submitBtn) submitBtn.textContent = 'Logging in...';

            fetch('process.php', {
                method: 'POST',
                body: new FormData(loginForm),
            })
                .then(res => res.json())
                .then(data => {
                    if (submitBtn) submitBtn.textContent = 'Log In';
                    if (data.status === 'success') {
                        if (authModal) authModal.hide();
                        loginForm.reset();
                        
                        // REFRESH PAGE ON SUCCESSFUL AUTHENTICATION
                        window.location.reload();
                    } else {
                        if (alertBox) {
                            alertBox.textContent = data.message || 'Login failed.';
                            alertBox.classList.remove('d-none');
                        }
                    }
                })
                .catch(() => {
                    if (submitBtn) submitBtn.textContent = 'Log In';
                    if (alertBox) {
                        alertBox.textContent = 'Something went wrong. Please try again.';
                        alertBox.classList.remove('d-none');
                    }
                });
        });
    }
});