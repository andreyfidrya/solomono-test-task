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

function displayCategoriesList(PDO $pdo) {
    $categories = getAllCategoriesWithCounts($pdo);

    $html = '<div class="card-body">';
    $html .= '<ul class="list list-unstyled mb-0">';

    foreach ($categories as $category) {
        $html .= '<li>';
        $html .= '<a href="#" class="category-link" data-id="' . (int)$category['id'] . '">';
        $html .= htmlspecialchars($category['category_name']);
        $html .= ' (' . (int)$category['product_count'] . ')'; // <-- количество товаров
        $html .= '</a>';
        $html .= '</li>';
    }

    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}

function getAllProducts() {
    // подключаем глобальный $pdo
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);    
}

function renderAllProducts() {
    global $pdo;

    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$products) {
        echo "<p>Нет товаров</p>";
        return;
    }

    foreach ($products as $product) {
        ?>
        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card card-modern card-modern-alt-padding">
                <div class="card-body bg-light">
                    <div class="image-frame mb-2">
                        <div class="image-frame-wrapper">
                            <a href="ecommerce-products-form.php?id=<?= $product['id'] ?>">
                                <img src="images/<?= htmlspecialchars($product['product_image']) ?>" 
                                     class="img-fluid" 
                                     alt="<?= htmlspecialchars($product['product_name']) ?>" />
                            </a>
                        </div>
                    </div>
                    <h4 class="text-4 line-height-2 mt-0 mb-2">
                        <a href="ecommerce-products-form.php?id=<?= $product['id'] ?>" 
                           class="ecommerce-sidebar-link text-color-dark text-color-hover-primary text-decoration-none">
                            <?= htmlspecialchars($product['product_name']) ?>
                        </a>
                    </h4>
                    <div class="product-price">
                        <div class="sale-price"><?= number_format($product['product_price'], 2) ?> грн</div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" 
                                class="btn btn-primary btn-buy"
                                data-bs-toggle="modal" 
                                data-bs-target="#cartModal"
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= htmlspecialchars($product['product_name'], ENT_QUOTES) ?>"
                                data-price="<?= $product['product_price'] ?>"
                                data-image="images/<?= htmlspecialchars($product['product_image'], ENT_QUOTES) ?>">
                            Купити
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Корзина</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered align-middle" id="cartTable">
                <thead>
                    <tr>
                    <th>Фото</th>
                    <th>Назва</th>
                    <th>Ціна</th>
                    <th>Кількість</th>
                    <th>Сума</th>
                    <th>Видалити</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Товары будут добавляться сюда через JS -->
                </tbody>
                <tfoot>
                    <tr>
                    <th colspan="4" class="text-end">Разом:</th>
                    <th id="cartTotal">0 грн</th>
                    <th></th>
                    </tr>
                </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                <button type="button" class="btn btn-success">Оформити заказ</button>
            </div>
            </div>
        </div>
        </div>

        <?php
    }
}
