<?php



define('RESULTS_FILE', 'results.json');
define('QUESTIONS_FILE', 'questions.json');


define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', password_hash('admin123', PASSWORD_DEFAULT));


session_start();

/**
 * Save results to JSON file
 * @param array $result Result data to save
 * @return bool Success status
 */
function saveResult($result) {
    $resultsFile = RESULTS_FILE;
    
   
    $results = [];
    if (file_exists($resultsFile)) {
        $jsonContent = file_get_contents($resultsFile);
        $data = json_decode($jsonContent, true);
        if ($data && isset($data['results'])) {
            $results = $data['results'];
        }
    }
    
    
    $results[] = $result;
    
    
    $data = ['results' => $results];
    return file_put_contents($resultsFile, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Get all results from JSON file
 * @return array Array of results
 */
function getResults() {
    $resultsFile = RESULTS_FILE;
    if (!file_exists($resultsFile)) {
        return [];
    }
    
    $jsonContent = file_get_contents($resultsFile);
    $data = json_decode($jsonContent, true);
    return $data['results'] ?? [];
}

/**
 * Check if user is authenticated
 * @return bool True if user is authenticated, false otherwise
 */
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
} 