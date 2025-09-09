<?php
require_once "db.php";
require_once __DIR__ . "/inc/functions.php";

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($categoryId > 0) {
    // получаем фильтры для категории
    $filters = getFiltersByCategory($pdo, $categoryId);
    echo json_encode($filters, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['lengths' => [], 'tests' => []], JSON_UNESCAPED_UNICODE);
}