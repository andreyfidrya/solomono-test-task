<?php

require_once "db.php";

function tt($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';    
}

function getAllCategories() {
    // подключаем глобальный $pdo
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);    
}

function displayCategoriesList() {
        $categories = getAllCategories();

        $html = '<div class="card-body">';
        $html .= '<ul class="list list-unstyled mb-0">';

        foreach ($categories as $category) {
            $html .= '<li>';
            $html .= '<a href="category.php?id=' . (int)$category['id'] . '">';
            $html .= htmlspecialchars($category['category_name']);
            $html .= '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
}
