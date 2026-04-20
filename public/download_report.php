<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$user = currentUser();
$format = ($_GET['format'] ?? 'csv') === 'txt' ? 'txt' : 'csv';

$stmt = getDbConnection()->prepare('SELECT id, title, budget, deadline, status, created_at FROM projects WHERE posted_by = :posted_by ORDER BY created_at DESC');
$stmt->execute(['posted_by' => $user['id']]);
$projects = $stmt->fetchAll();

$filename = 'projects_report_' . date('Ymd_His');

if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

    $output = fopen('php://output', 'wb');
    fputcsv($output, ['ID', 'Title', 'Budget', 'Deadline', 'Status', 'Created At']);

    foreach ($projects as $project) {
        fputcsv($output, [
            $project['id'],
            $project['title'],
            $project['budget'],
            $project['deadline'],
            $project['status'],
            $project['created_at'],
        ]);
    }

    fclose($output);
    exit;
}

header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '.txt"');

echo "FreelanceHub Project Report\n";
echo 'User: ' . $user['full_name'] . ' (' . $user['email'] . ")\n";
echo 'Generated at: ' . date('Y-m-d H:i:s') . "\n";
echo str_repeat('=', 90) . "\n";

foreach ($projects as $project) {
    echo 'ID: ' . $project['id'] . "\n";
    echo 'Title: ' . $project['title'] . "\n";
    echo 'Budget: ' . $project['budget'] . "\n";
    echo 'Deadline: ' . $project['deadline'] . "\n";
    echo 'Status: ' . strtoupper($project['status']) . "\n";
    echo 'Created At: ' . $project['created_at'] . "\n";
    echo str_repeat('-', 60) . "\n";
}
exit;
