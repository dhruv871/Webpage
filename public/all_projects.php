<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT p.id, p.title, p.description, p.skills_required, p.budget, p.deadline, p.status, u.full_name
    FROM projects p
    JOIN users u ON u.id = p.posted_by
    ORDER BY p.created_at DESC');
$projects = $stmt->fetchAll();

$pageTitle = 'All Projects';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>All Projects</h2>
<div class="grid">
    <?php if (!$projects): ?>
        <article class="card"><p>No projects available.</p></article>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <article class="card">
                <h3><?= h($project['title']) ?></h3>
                <p><?= nl2br(h($project['description'])) ?></p>
                <p><strong>Skills:</strong> <?= h($project['skills_required']) ?></p>
                <p><strong>Budget:</strong> $<?= number_format((float)$project['budget'], 2) ?></p>
                <p><strong>Deadline:</strong> <?= h($project['deadline']) ?></p>
                <p><strong>Status:</strong> <?= h(strtoupper($project['status'])) ?></p>
                <p><strong>Posted by:</strong> <?= h($project['full_name']) ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
