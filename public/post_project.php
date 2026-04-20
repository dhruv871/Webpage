<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$user = currentUser();
$errors = [];
$title = '';
$description = '';
$skills = '';
$budget = '';
$deadline = '';
$status = 'open';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $skills = trim($_POST['skills_required'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $status = ($_POST['status'] ?? 'open') === 'closed' ? 'closed' : 'open';

    if (strlen($title) < 5) {
        $errors[] = 'Project title must be at least 5 characters.';
    }

    if (strlen($description) < 30) {
        $errors[] = 'Description must be at least 30 characters.';
    }

    if (!is_numeric($budget) || (float)$budget <= 0) {
        $errors[] = 'Budget must be a positive number.';
    }

    if (!$deadline || strtotime($deadline) === false) {
        $errors[] = 'Please provide a valid deadline date.';
    }

    if (!$errors) {
        $stmt = getDbConnection()->prepare('INSERT INTO projects (title, description, budget, deadline, skills_required, status, posted_by)
            VALUES (:title, :description, :budget, :deadline, :skills_required, :status, :posted_by)');
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'budget' => $budget,
            'deadline' => $deadline,
            'skills_required' => $skills,
            'status' => $status,
            'posted_by' => $user['id'],
        ]);

        flash('success', 'Project posted successfully.');
        header('Location: my_projects.php');
        exit;
    }
}

$pageTitle = 'Post Project';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>Post a New Project</h2>
<form method="post" class="form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert error"><?= h($error) ?></div>
    <?php endforeach; ?>

    <label>Project Title
        <input type="text" name="title" value="<?= h($title) ?>" required>
    </label>

    <label>Description
        <textarea name="description" rows="6" required><?= h($description) ?></textarea>
    </label>

    <label>Skills Required (comma-separated)
        <input type="text" name="skills_required" value="<?= h($skills) ?>">
    </label>

    <label>Budget (USD)
        <input type="number" step="0.01" min="1" name="budget" value="<?= h($budget) ?>" required>
    </label>

    <label>Deadline
        <input type="date" name="deadline" value="<?= h($deadline) ?>" required>
    </label>

    <label>Status
        <select name="status">
            <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>Open</option>
            <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>Closed</option>
        </select>
    </label>

    <button type="submit" class="btn">Publish Project</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
