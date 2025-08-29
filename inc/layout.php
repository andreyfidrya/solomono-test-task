<?php

require_once "functions.php";

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

		<style>
		/* делаем колонку флексом */
		.col-sm-6.col-xl-3 {
			display: flex;
		}

		/* карточка должна растягиваться */
		.col-sm-6.col-xl-3 .card {
			display: flex;
			flex-direction: column;
			flex: 1;
		}

		/* card-body растягивается */
		.col-sm-6.col-xl-3 .card-body {
			display: flex;
			flex-direction: column;
			flex: 1;
		}

		/* блок с кнопкой уходит вниз */
		.col-sm-6.col-xl-3 .card-body .text-center {
			margin-top: auto;
		}
		</style>

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
						<select id="sort" class="form-select" onchange="sortProducts(this.value)">
							<option value="">Зробіть вибір...</option>
							<option value="price_asc">Спочатку дешеві</option>
							<option value="alphabet">По алфавіту</option>
							<option value="newest">Спочатку нові</option>
						</select>
					</div>																
				</div>
			</div>						
			<div class="col-lg-3-5 col-xl-4-5">
				<div class="row row-gutter-sm" id="products-container">
						
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

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script>
		
		$(document).ready(function () {
			let currentCategory = 0;

			// загрузка товаров (категория + сортировка)
			function loadProducts(categoryId = 0, sort = "") {
				$.ajax({
					url: 'get_products.php',
					type: 'GET',
					data: { id: categoryId, sort: sort },
					success: function (response) {
						$('#products-container').html(response);
						// формируем новый URL
						let params = new URLSearchParams(window.location.search);
						if (categoryId > 0) {
							params.set('category', categoryId);
						} else {
							params.delete('category');
						}

						if (sort) {
							params.set('sort', sort);
						} else {
							params.delete('sort');
						}

						let newUrl = window.location.pathname + '?' + params.toString();
						history.replaceState({}, '', newUrl);
					},
					error: function () {
						alert('Ошибка загрузки товаров');
					}
				});

				// Сохраняем корзину в localStorage
				localStorage.setItem('cart', JSON.stringify(cart));
			}

			// клик по категории
			$(document).on('click', '.category-link', function (e) {
				e.preventDefault();
				currentCategory = $(this).data('id');
				loadProducts(currentCategory, $('#sort').val());

				// снимаем active у всех и вешаем на выбранную
				$('.category-link').removeClass('active text-primary');
				$(this).addClass('active text-primary');
			});

			// выбор сортировки
			$('#sort').on('change', function () {
				loadProducts(currentCategory, $(this).val());
			});

			// при загрузке страницы проверяем параметры
		const urlParams = new URLSearchParams(window.location.search);
		const initialCategory = urlParams.get('category') || 0;
		const initialSort = urlParams.get('sort') || "";

		currentCategory = initialCategory;
		$('#sort').val(initialSort);

		// ВСЕГДА грузим товары через AJAX
		loadProducts(initialCategory, initialSort);
			
		});
		</script>
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			let cart = {};
			const cartModalEl = document.getElementById('cartModal');
			const cartModal = new bootstrap.Modal(cartModalEl);

			// ==== Сохранение корзины ====
			function saveCart() {
				localStorage.setItem('cart', JSON.stringify(cart));
			}

			// ==== Загрузка корзины ====
			function loadCart() {
				const savedCart = localStorage.getItem('cart');
				if (savedCart) {
					cart = JSON.parse(savedCart);
				}
				updateCartTable();
				updateButtons();
			}

			// ==== Обновление таблицы корзины ====
			function updateCartTable() {
				const tbody = document.querySelector('#cartTable tbody');
				tbody.innerHTML = '';
				let total = 0;

				for (const id in cart) {
					const item = cart[id];
					const itemTotal = item.price * item.quantity;
					total += itemTotal;

					const tr = document.createElement('tr');
					tr.innerHTML = `
						<td><img src="${item.image}" alt="${item.name}" width="50"></td>
						<td>${item.name}</td>
						<td>${item.price} грн</td>
						<td><input type="number" min="1" value="${item.quantity}" class="form-control form-control-sm cart-qty" data-id="${id}"></td>
						<td class="item-total">${itemTotal.toFixed(2)} грн</td>
						<td><button class="btn btn-danger btn-sm btn-remove" data-id="${id}">Удалить</button></td>
					`;
					tbody.appendChild(tr);
				}

				document.getElementById('cartTotal').textContent = total.toFixed(2) + ' грн';
			}

			// ==== Обновление кнопок "Купити / В корзині" ====
			function updateButtons() {
				document.querySelectorAll('.btn-buy').forEach(button => {
					const id = button.dataset.id;
					if (cart[id]) {
						button.textContent = 'В корзині';
						button.classList.remove('btn-primary');
						button.classList.add('btn-success');
					} else {
						button.textContent = 'Купити';
						button.classList.remove('btn-success');
						button.classList.add('btn-primary');
					}
				});
			}

			// ==== Добавление товара ====
			document.addEventListener('click', function(e) {
				if (e.target.classList.contains('btn-buy')) {
					const button = e.target;
					const id = button.dataset.id;
					const name = button.dataset.name;
					const price = parseFloat(button.dataset.price);
					const image = button.dataset.image;

					if (cart[id]) {
						cart[id].quantity += 1;
					} else {
						cart[id] = {id, name, price, image, quantity: 1};
					}

					updateCartTable();
					updateButtons();
					saveCart();
					cartModal.show();
				}
			});

			// ==== Изменение количества ====
			document.getElementById('cartTable').addEventListener('input', function (e) {
				if (e.target.classList.contains('cart-qty')) {
					const id = e.target.dataset.id;
					let qty = parseInt(e.target.value);
					if (qty < 1) qty = 1;
					cart[id].quantity = qty;

					updateCartTable();
					saveCart();
				}
			});

			// ==== Удаление товара ====
			document.getElementById('cartTable').addEventListener('click', function (e) {
				if (e.target.classList.contains('btn-remove')) {
					const id = e.target.dataset.id;
					delete cart[id];

					updateCartTable();
					updateButtons();
					saveCart();
				}
			});

			// ==== Подхватываем кнопки после AJAX подгрузки товаров ====
			// (когда get_products.php возвращает товары)
			$(document).ajaxSuccess(function () {
				updateButtons();
			});

			// ==== Первичная загрузка ====
			loadCart();
		});
		</script>
		
	</body>
</html>