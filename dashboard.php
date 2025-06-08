<?php
require_once 'config.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Get results
$results = getResults();
// Sort by timestamp descending
usort($results, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Результаты тестирования</h1>
            <div>
                <a href="index.php" class="btn btn-secondary me-2">На главную</a>
                <a href="?logout" class="btn btn-danger">Выйти</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="resultsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Правильных ответов</th>
                        <th>Всего вопросов</th>
                        <th>Процент</th>
                        <th>Дата прохождения</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['correct_answers'] ?></td>
                            <td><?= $row['total_questions'] ?></td>
                            <td><?= number_format($row['score_percentage'], 2) ?>%</td>
                            <td><?= date('d.m.Y H:i:s', strtotime($row['completion_time'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resultsTable').DataTable({
                "order": [[4, "desc"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
                }
            });
        });
    </script>
</body>
</html> 