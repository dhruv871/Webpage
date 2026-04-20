<?php

declare(strict_types=1);

$pageTitle = 'Welcome - FreelanceHub';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="hero">
    <h1>Build, Hire, and Grow with FreelanceHub</h1>
    <p>A complete PHP freelancing platform where clients post projects and freelancers manage opportunities.</p>
    <div class="hero-actions">
        <?php if (isLoggedIn()): ?>
            <a class="btn" href="dashboard.php">Go to Dashboard</a>
        <?php else: ?>
            <a class="btn" href="register.php">Create Account</a>
            <a class="btn secondary" href="login.php">Sign In</a>
        <?php endif; ?>
    </div>
</section>

<section class="grid">
    <article class="card">
        <h3>Secure Authentication</h3>
        <p>User registration and login with hashed passwords and server-side validation.</p>
    </article>
    <article class="card">
        <h3>Project Posting</h3>
        <p>Clients can post detailed projects including budget, timeline, and required skills.</p>
    </article>
    <article class="card">
        <h3>Reports Export</h3>
        <p>Download your project data as TXT or CSV directly from the dashboard.</p>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
