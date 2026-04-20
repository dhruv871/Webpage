<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$user = currentUser();
$pdo = getDbConnection();

$myProjectsCountStmt = $pdo->prepare('SELECT COUNT(*) FROM projects WHERE posted_by = :posted_by');
$myProjectsCountStmt->execute(['posted_by' => $user['id']]);
$myProjectsCount = (int)$myProjectsCountStmt->fetchColumn();

$openProjectsCount = (int)$pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'open'")->fetchColumn();
$closedProjectsCount = (int)$pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'closed'")->fetchColumn();

$recentStmt = $pdo->prepare('SELECT p.id, p.title, p.budget, p.deadline, p.status, u.full_name AS owner_name
    FROM projects p
    JOIN users u ON u.id = p.posted_by
    ORDER BY p.created_at DESC
    LIMIT 8');
$recentStmt->execute();
$recentProjects = $recentStmt->fetchAll();

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>Dashboard</h2>
<p>Hello, <strong><?= h($user['full_name']) ?></strong> (<?= h(ucfirst($user['role'])) ?>).</p>

<section class="stats-grid">
    <article class="stat-card">
        <h3><?= $myProjectsCount ?></h3>
        <p>My Posted Projects</p>
    </article>
    <article class="stat-card">
        <h3><?= $openProjectsCount ?></h3>
        <p>Open Projects</p>
    </article>
    <article class="stat-card">
        <h3><?= $closedProjectsCount ?></h3>
        <p>Closed Projects</p>
    </article>
</section>

<div class="toolbar">
    <a class="btn" href="post_project.php">Post New Project</a>
    <a class="btn secondary" href="download_report.php?format=csv">Download CSV Report</a>
    <a class="btn secondary" href="download_report.php?format=txt">Download TXT Report</a>
</div>

<h3>Recent Projects</h3>
<div class="table-wrap">
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Budget (USD)</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Posted By</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$recentProjects): ?>
            <tr><td colspan="5">No projects found.</td></tr>
        <?php else: ?>
            <?php foreach ($recentProjects as $project): ?>
                <tr>
                    <td><?= h($project['title']) ?></td>
                    <td><?= number_format((float)$project['budget'], 2) ?></td>
                    <td><?= h($project['deadline']) ?></td>
                    <td><?= h(strtoupper($project['status'])) ?></td>
                    <td><?= h($project['owner_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
