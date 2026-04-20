<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$user = currentUser();
$pageTitle = $pageTitle ?? 'FreelanceHub';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a href="index.php" class="brand">FreelanceHub</a>
        <nav>
            <a href="index.php">Home</a>
            <?php if ($user): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="all_projects.php">Browse Projects</a>
                <a href="post_project.php">Post Project</a>
                <a href="my_projects.php">My Projects</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php if ($message = flash('success')): ?>
    <div class="alert success"><?= h($message) ?></div>
<?php endif; ?>
<?php if ($message = flash('error')): ?>
    <div class="alert error"><?= h($message) ?></div>
<?php endif; ?>
