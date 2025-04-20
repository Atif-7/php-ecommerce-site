<?php
require_once "config/database.php";
header('Content-Type: application/json'); // Set response type
if (isset($_GET['query'])) {
    $keyword = trim($_GET['query']);
    if (!empty($keyword)) {
        $results = $query->searchSuggestions($keyword);
        
        // Debugging: Check if results are fetched
        // echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // exit;
    }
    $results = $query->searchProducts($keyword);
    echo json_encode($results);
}

// Debugging: No keyword provided
// header('Content-Type: application/json');
// echo json_encode(["error" => "No query provided"]);
// exit;
?>