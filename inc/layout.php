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
										<h4 class="card-title">КАТЕГОРИИ</h4>
									</div>
									<?= displayCategoriesList($pdo); ?>
								</div>																
							</div>
						</div>
						<div class="col-lg-3-5 col-xl-4-5">
							<div class="row row-gutter-sm" id="products-container">

								<?= renderAllProducts(); ?>									

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
		$(document).on('click', '.category-link', function(e) {
			e.preventDefault();

			let categoryId = $(this).data('id');

			$.ajax({
				url: 'get_products.php',
				type: 'GET',
				data: { id: categoryId },
				success: function(response) {
					// Подставляем товары в контейнер
					$('#products-container').html(response);
				},
				error: function() {
					alert('Ошибка загрузки товаров');
				}
			});
		});
		</script>
	</body>
</html>
