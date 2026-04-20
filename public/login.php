<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = getDbConnection()->prepare('SELECT id, password_hash FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $errors[] = 'Invalid email or password.';
    } else {
        loginUser((int)$user['id']);
        flash('success', 'Login successful.');
        header('Location: dashboard.php');
        exit;
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>Login</h2>
<form method="post" class="form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert error"><?= h($error) ?></div>
    <?php endforeach; ?>

    <label>Email
        <input type="email" name="email" value="<?= h($email) ?>" required>
    </label>

    <label>Password
        <input type="password" name="password" required>
    </label>

    <button type="submit" class="btn">Login</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
