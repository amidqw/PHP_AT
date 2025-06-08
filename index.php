<?php
require_once 'config.php';

/**
 * Load questions from JSON file
 * @return array Array of questions
 */
function loadQuestions() {
    $questionsJson = file_get_contents('questions.json');
    return json_decode($questionsJson, true)['questions'];
}

$questions = loadQuestions();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестирование по программированию</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .quiz-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .question {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container quiz-container">
        <h1 class="text-center mb-4">Тест по программированию</h1>
        <form action="process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Введите ваше имя:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <?php foreach ($questions as $index => $question): ?>
                <div class="question">
                    <h5><?= htmlspecialchars($question['question']) ?></h5>
                    <?php if ($question['type'] === 'single'): ?>
                        <?php foreach ($question['options'] as $optionIndex => $option): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="answers[<?= $question['id'] ?>]" 
                                       value="<?= $optionIndex ?>" 
                                       id="q<?= $question['id'] ?>_<?= $optionIndex ?>" required>
                                <label class="form-check-label" for="q<?= $question['id'] ?>_<?= $optionIndex ?>">
                                    <?= htmlspecialchars($option) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($question['options'] as $optionIndex => $option): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="answers[<?= $question['id'] ?>][]" 
                                       value="<?= $optionIndex ?>" 
                                       id="q<?= $question['id'] ?>_<?= $optionIndex ?>">
                                <label class="form-check-label" for="q<?= $question['id'] ?>_<?= $optionIndex ?>">
                                    <?= htmlspecialchars($option) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Завершить тест</button>
            </div>
        </form>

        <?php if (isAuthenticated()): ?>
            <div class="text-center mt-3">
                <a href="dashboard.php" class="btn btn-secondary">Панель администратора</a>
            </div>
        <?php else: ?>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-outline-secondary">Вход для администратора</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 