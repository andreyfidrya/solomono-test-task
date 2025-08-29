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
        $html .= '<a href="#" class="category-link ' . $isActive . '" data-id="' . (int)$category['id'] . '">';
        $html .= htmlspecialchars($category['category_name']);
        $html .= ' (' . (int)$category['product_count'] . ')'; // <-- количество товаров
        $html .= '</a>';
        $html .= '</li>';
    }

    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}



