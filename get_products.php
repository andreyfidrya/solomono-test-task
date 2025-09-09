<?php
require_once "db.php";

// Получаем id категории из GET
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Получаем сортировку из GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$filters    = isset($_GET['filters']) ? json_decode($_GET['filters'], true) : [];

$sql = "SELECT * FROM products";
$params = [];

// Фильтр по категории
if ($categoryId > 0) {
    $sql .= " WHERE category_id = ?";
    $params[] = $categoryId;
}

// фильтрация по длине
if (!empty($filters['lengths'])) {
    $placeholders = implode(',', array_fill(0, count($filters['lengths']), '?'));
    $sql .= " AND product_length IN ($placeholders)";
    $params = array_merge($params, $filters['lengths']);
}

// фильтрация по тесту
if (!empty($filters['tests'])) {
    $placeholders = implode(',', array_fill(0, count($filters['tests']), '?'));
    $sql .= " AND product_test IN ($placeholders)";
    $params = array_merge($params, $filters['tests']);
}

// Сортировка
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY product_price ASC";
        break;    
    case 'alphabet':
        $sql .= " ORDER BY product_name ASC";
        break;
    case 'newest':
        $sql .= " ORDER BY product_date DESC";
        break;
    default:
        $sql .= " ORDER BY id ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Возвращаем HTML (фрагмент карточек)
if (!$products) {
    echo "<p>Нет товаров</p>";
    exit;
}

$countOnly = isset($_GET['count_only']) && $_GET['count_only'] == 1;

if ($countOnly) {
    echo count($products);
    exit;
}

foreach ($products as $product) {
    ?>    

    <div class="col-sm-6 col-xl-3 mb-4 d-flex"> <!-- добавили d-flex -->
        <div class="card card-modern card-modern-alt-padding flex-fill d-flex flex-column"> <!-- flex-fill и d-flex flex-column -->
            <div class="card-body bg-light d-flex flex-column">
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
                <div class="product-test">
                    <div class="sale-test">Тест: <?= $product['product_test'] ?> гр</div>
                </div>
                <div class="product-length mb-3">
                    <div class="sale-length">Длина: <?= $product['product_length'] ?> мм</div>
                </div>
                <div class="mt-auto">
                    <div class="product-price product-price d-flex justify-content-center mb-3">
                        <div class="sale-price"><?= number_format($product['product_price'], 2) ?> грн</div>
                    </div>
                    <div class="text-center">
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
    </div>
    
    <?php
}
