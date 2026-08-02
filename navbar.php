<?php

$profileImage = $profileImage ?? null;
$initial = ($userName ?? '') !== '' ? strtoupper(substr($userName, 0, 1)) : '?';
?>
<nav class="navbar" id="siteNavbar">
    <div class="logo">
        <a href="index.php"><img src="logo_image.png" alt="logo image"></a>
    </div>
    <ul class="navtype">
        <li><a href="index.php">Home</a></li>
        <li><a href="jobs.php" class="<?php echo $loggedIn ? '' : 'open-login-modal'; ?>" <?php echo $loggedIn ? '' : 'data-nav-guard="1" href="#"'; ?>>Jobs</a></li>
        <li><a href="cv_builder.php" class="<?php echo $loggedIn ? '' : 'open-login-modal'; ?>" <?php echo $loggedIn ? '' : 'data-nav-guard="1" href="#"'; ?>>CV Builder</a></li>
        <li><a href="about.php" class="<?php echo $loggedIn ? '' : 'open-login-modal'; ?>" <?php echo $loggedIn ? '' : 'data-nav-guard="1" href="#"'; ?>>About</a></li>
    </ul>

    <div id="navAuthArea">
        <?php if ($loggedIn): ?>
            <div class="navuser dropdown">
                <button class="navuser-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navuser-avatar">
                        <?php if ($profileImage): ?>
                            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="profile photo">
                        <?php else: ?>
                            <span class="navuser-avatar-initial"><?php echo htmlspecialchars($initial); ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="navuser-name"><?php echo htmlspecialchars($userName); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end navuser-menu">
                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="jobs.php">Browse Jobs</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <button class="loginbtn open-login-modal">Login</button>
        <?php endif; ?>
    </div>
</nav>
