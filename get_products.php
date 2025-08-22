<?php
require_once "db.php";

// Получаем id категории из GET
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM products";
if ($categoryId > 0) {
    $sql .= " WHERE category_id = :category_id";
}
$sql .= " ORDER BY id ASC";

$stmt = $pdo->prepare($sql);

if ($categoryId > 0) {
    $stmt->execute(['category_id' => $categoryId]);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Возвращаем HTML (фрагмент карточек)
if (!$products) {
    echo "<p>Нет товаров</p>";
    exit;
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
                    <div class="sale-price">$<?= number_format($product['product_price'], 2) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
