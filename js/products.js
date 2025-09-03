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
