<?php

require_once "db.php";

function tt($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';    
}

function getAllCategoriesWithCounts(PDO $pdo) {
    $sql = "
        SELECT c.id, c.category_name, COUNT(p.id) AS product_count
        FROM categories c
        LEFT JOIN products p ON c.id = p.category_id
        GROUP BY c.id, c.category_name
        ORDER BY c.id ASC 
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function displayCategoriesList(PDO $pdo, $currentCategory = null) {
    $categories = getAllCategoriesWithCounts($pdo);

    $html = '<div class="card-body">';
    $html .= '<ul class="list list-unstyled mb-0">';

    foreach ($categories as $category) {
        $isActive = ($currentCategory == $category['id']) ? 'active text-primary' : '';
        $html .= '<li>';
        $html .= '<a href="#" 
             class="category-link ' . $isActive . '" 
             data-id="' . (int)$category['id'] . '" 
             data-name="' . htmlspecialchars($category['category_name'], ENT_QUOTES) . '">';
        $html .= htmlspecialchars($category['category_name']);
        $html .= ' (' . (int)$category['product_count'] . ')'; // оставляем как есть для отображения в списке
        $html .= '</a>';
        $html .= '</li>';
    }

    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}

function getFiltersByCategory(PDO $pdo, int $categoryId) {
    $filters = [
        'brands' => [],
        'lengths' => [],
        'tests'   => []
    ];

    $sql = "SELECT DISTINCT product_brand FROM products WHERE category_id = :categoryId ORDER BY product_brand ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['categoryId' => $categoryId]);
    $filters['brands'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $sql = "SELECT DISTINCT product_length FROM products WHERE category_id = :categoryId ORDER BY product_length ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['categoryId' => $categoryId]);
    $filters['lengths'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $sql = "SELECT DISTINCT product_test FROM products WHERE category_id = :categoryId ORDER BY product_test ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['categoryId' => $categoryId]);
    $filters['tests'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $filters;
}

function getPriceRange(PDO $pdo, int $categoryId = 0) {
    if ($categoryId > 0) {
        $sql = "SELECT MIN(product_price) as min_price, MAX(product_price) as max_price 
                FROM products 
                WHERE category_id = :categoryId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['categoryId' => $categoryId]);
    } else {
        $sql = "SELECT MIN(product_price) as min_price, MAX(product_price) as max_price FROM products";
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}