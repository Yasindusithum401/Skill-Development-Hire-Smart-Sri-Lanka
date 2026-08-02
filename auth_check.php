<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Redirects a guest back to the homepage with a popup message. */
function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        $query = http_build_query([
            'status'  => 'error',
            'message' => 'Please log in to access this page.',
        ]);
        header("Location: index.php?$query");
        exit;
    }
}

/** Like require_login(), but also enforces the account type (seeker/recruiter). */
function require_role(string $role): void
{
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) {
        header('Location: index.php');
        exit;
    }
}
