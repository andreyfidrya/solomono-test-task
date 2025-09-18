<?php
require_once __DIR__ . "/inc/functions.php";
require_once "db.php";

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$range = getPriceRange($pdo, $categoryId);

echo json_encode([
    'min' => (int)$range['min_price'],
    'max' => (int)$range['max_price']
]);

