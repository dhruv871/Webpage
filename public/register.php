<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$fullName = '';
$email = '';
$role = 'freelancer';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = ($_POST['role'] ?? 'freelancer') === 'client' ? 'client' : 'freelancer';

    if (strlen($fullName) < 3) {
        $errors[] = 'Full name must be at least 3 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = getDbConnection()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $errors[] = 'Email already exists. Please login.';
        } else {
            $insert = getDbConnection()->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (:full_name, :email, :password_hash, :role)');
            $insert->execute([
                'full_name' => $fullName,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
            ]);

            loginUser((int)getDbConnection()->lastInsertId());
            flash('success', 'Welcome! Your account has been created.');
            header('Location: dashboard.php');
            exit;
        }
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/../includes/header.php';
?>
<h2>Create Account</h2>
<form method="post" class="form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert error"><?= h($error) ?></div>
    <?php endforeach; ?>

    <label>Full Name
        <input type="text" name="full_name" value="<?= h($fullName) ?>" required>
    </label>

    <label>Email
        <input type="email" name="email" value="<?= h($email) ?>" required>
    </label>

    <label>Role
        <select name="role">
            <option value="freelancer" <?= $role === 'freelancer' ? 'selected' : '' ?>>Freelancer</option>
            <option value="client" <?= $role === 'client' ? 'selected' : '' ?>>Client</option>
        </select>
    </label>

    <label>Password
        <input type="password" name="password" required>
    </label>

    <label>Confirm Password
        <input type="password" name="confirm_password" required>
    </label>

    <button type="submit" class="btn">Register</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
