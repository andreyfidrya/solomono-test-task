<?php

require_once "functions.php";

$currentCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$priceRange = getPriceRange($pdo, $currentCategory);
$minPrice = (int)$priceRange['min_price'];
$maxPrice = (int)$priceRange['max_price'];

?>

<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Тестове завдання 1: Категорії і товари</title>

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="vendor/animate/animate.compat.css">
		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="vendor/boxicons/css/boxicons.min.css" />
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
		<link rel="stylesheet" href="vendor/dropzone/basic.css" />
		<link rel="stylesheet" href="vendor/dropzone/dropzone.css" />
		<link rel="stylesheet" href="vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="vendor/pnotify/pnotify.custom.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Theme Layout -->
		<link rel="stylesheet" href="css/layouts/modern.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Theme WordPress CSS -->
		<link rel="stylesheet" href="css/style.css">		

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>

	</head>
	<body>
		<div class="row row-gutter-sm mb-5">
			<div class="col-lg-2-5 col-xl-1-5 mb-4 mb-lg-0">
				<div class="filters-sidebar-wrapper bg-light rounded">
					<div class="card card-modern">
						<div class="card-header">
							<div class="card-actions">
								<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							</div>
							<h4 class="card-title">КАТЕГОРІЇ</h4>
						</div>
						<?php
						$currentCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
						echo displayCategoriesList($pdo, $currentCategory);
						?>
					</div>
					<hr class="solid opacity-7">
					<div class="p-3">
						<label for="sort" class="form-label fw-bold">Сортування:</label>
						<select id="sort" class="form-select">
							<option value="">Зробіть вибір...</option>
							<option value="price_asc">Спочатку дешеві</option>
							<option value="alphabet">По алфавіту</option>
							<option value="newest">Спочатку нові</option>
						</select>
					</div>																
				</div>
				<hr class="solid opacity-7">
				
			</div>						
			<div class="col-lg-3-5 col-xl-4-5">
				<!-- Хедер с кнопкой фильтров -->
				<div class="category-filters-hidden mb-3 d-none" id="filtersHeader">
					<div class="category-wrapper-filters d-flex align-items-center gap-3">
						
						<!-- Кнопка фильтров -->
						<div class="category-wrapper-btn-filters">			
						<button id="open_modal_filters_category" 
								class="category-btn-filters" 
								type="button" 
								data-bs-toggle="modal" 
								data-bs-target="#filtersModal">
							ФІЛЬТРИ
						</button>
						</div>

						<!-- Слайдер цены -->
						<div class="price-filter d-flex align-items-center gap-2">
						<label for="price-range" class="form-label fw-bold mb-0">Ціна:</label>
						<div id="price-range" style="width:200px;"></div>
						<div class="ms-2">
							<span id="price-min"><?php echo $minPrice; ?></span> грн –
							<span id="price-max"><?php echo $maxPrice; ?></span> грн
						</div>
						</div>

					</div>
				</div>

				<!-- Список товаров -->
				<div class="row row-gutter-sm" id="products-container">
						
				</div>							
			</div>
		</div>

		<script>
			// Передаём значения в JS
			window.priceRange = {
				min: <?php echo $minPrice; ?>,
				max: <?php echo $maxPrice; ?>
			};
		</script>

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
							<tbody></tbody>
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

		<div id="filters_backdrop" class="backdrop">
			<div id="filters_modal" class="ns-modal modal-scroll">
				<div class="modal-container">
					<button id="close_modal_filters_category" class="modal-btn-close"></button>
					<h2 class="modal-title">фільтри</h2>
				</div>
				<div class="modal-wrapper-filters modal-wrapper-filters-rods">
					<h3 class="modal-filters-title">Категорія:</h3>
					<div id="filter_category_title" class="modal-category-title"></div>
					
					<h3 class="modal-filters-title">Бренди:</h3>				
    				<div id="filter_brands" class="modal-filters-container-btn"></div>

					<h3 class="modal-filters-title">Довжина:</h3>				
    				<div id="filter_lengths" class="modal-filters-container-btn"></div>
    
					<h3 class="modal-filters-title">Тест:</h3>
					<div id="filter_tests" class="modal-filters-container-btn"></div>
					<div class="modal-box-btns">						
						<button id="filter_modal_reset" class="modal-btn-remove-filters" type="button">очистити фільтри</button>  
						<button id="filter_modal_apply" class="modal-btn" type="button">Застосувати</button>
					</div>		
				</div>
			</div>	
		</div>

		<!-- Vendor -->
		<script src="vendor/jquery/jquery.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->
		<script src="vendor/jquery-ui/jquery-ui.js"></script>
		<script src="vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="vendor/jquery-validation/jquery.validate.js"></script>
		<script src="vendor/select2/js/select2.js"></script>
		<script src="vendor/dropzone/dropzone.js"></script>
		<script src="vendor/pnotify/pnotify.custom.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>

		<!-- Theme Custom -->
		<script src="js/custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

		<!-- Examples -->
		<script src="js/examples/examples.header.menu.js"></script>
		<script src="js/examples/examples.ecommerce.form.js"></script>
		<script src="js/examples/examples.ecommerce.sidebar.overlay.js"></script>		
		
		<script src="js/products.js"></script>
		<script src="js/cart.js"></script>
		<script src="js/filters.js"></script>
		
	</body>
</html>