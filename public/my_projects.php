<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$user = currentUser();
$pdo = getDbConnection();

$stmt = $pdo->prepare('SELECT id, title, budget, deadline, status, created_at FROM projects WHERE posted_by = :posted_by ORDER BY created_at DESC');
$stmt->execute(['posted_by' => $user['id']]);
$projects = $stmt->fetchAll();

$pageTitle = 'My Projects';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>My Projects</h2>
<div class="table-wrap">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Budget</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$projects): ?>
            <tr><td colspan="6">You have not posted any projects yet.</td></tr>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= (int)$project['id'] ?></td>
                    <td><?= h($project['title']) ?></td>
                    <td>$<?= number_format((float)$project['budget'], 2) ?></td>
                    <td><?= h($project['deadline']) ?></td>
                    <td><?= h(strtoupper($project['status'])) ?></td>
                    <td><?= h($project['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
