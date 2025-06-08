<?php
require_once 'config.php';

/**
 * Calculate quiz score
 * @param array $userAnswers User's answers
 * @param array $questions Quiz questions
 * @return array Score information
 */
function calculateScore($userAnswers, $questions) {
    $totalQuestions = count($questions);
    $correctAnswers = 0;

    foreach ($questions as $question) {
        $questionId = $question['id'];
        if (!isset($userAnswers[$questionId])) {
            continue;
        }

        if ($question['type'] === 'single') {
            if ((int)$userAnswers[$questionId] === $question['correct']) {
                $correctAnswers++;
            }
        } else {
            $userAnswer = is_array($userAnswers[$questionId]) ? $userAnswers[$questionId] : [$userAnswers[$questionId]];
            $userAnswer = array_map('intval', $userAnswer);
            sort($userAnswer);
            sort($question['correct']);
            if ($userAnswer === $question['correct']) {
                $correctAnswers++;
            }
        }
    }

    $percentageScore = ($correctAnswers / $totalQuestions) * 100;
    return [
        'correct' => $correctAnswers,
        'total' => $totalQuestions,
        'percentage' => round($percentageScore, 2)
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $answers = $_POST['answers'] ?? [];
    
    
    $questionsJson = file_get_contents(QUESTIONS_FILE);
    $questions = json_decode($questionsJson, true)['questions'];
    
    
    $score = calculateScore($answers, $questions);
    
   
    $result = [
        'username' => $username,
        'correct_answers' => $score['correct'],
        'total_questions' => $score['total'],
        'score_percentage' => $score['percentage'],
        'completion_time' => date('Y-m-d H:i:s'),
        'timestamp' => time()
    ];
    
    saveResult($result);
    
    
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Результаты теста</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; }
            .results-container {
                max-width: 600px;
                margin: 2rem auto;
                padding: 2rem;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="container results-container">
            <h1 class="text-center mb-4">Результаты теста</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Спасибо за прохождение теста, <?= htmlspecialchars($username) ?>!</h5>
                    <p class="card-text">Правильных ответов: <?= $score['correct'] ?> из <?= $score['total'] ?></p>
                    <p class="card-text">Процент правильных ответов: <?= $score['percentage'] ?>%</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-primary">Пройти тест снова</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

header('Location: index.php');
exit; 