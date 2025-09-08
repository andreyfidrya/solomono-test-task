let currentCategory = 0;

$(document).ready(function () {		
			// загрузка товаров (категория + сортировка)
			function loadProducts(categoryId = 0, sort = "") {
				categoryId = parseInt(categoryId) || 0; // ← всегда число
    			currentCategory = categoryId; // обновляем текущее

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

						// Только если есть параметры, добавляем '?' и строку запроса
						let newUrl = window.location.pathname;
						let queryString = params.toString();
						if (queryString) {
							newUrl += '?' + queryString;
						}
						history.replaceState({}, '', newUrl);

						// ОБНОВЛЯЕМ ПОДСВЕТКУ КАТЕГОРИИ
						$('.category-link').removeClass('active text-primary');
						if (categoryId > 0) {
							$('.category-link[data-id="' + categoryId + '"]').addClass('active text-primary');
						}

						toggleFiltersHeader();
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
				currentCategory = parseInt($(this).data('id')); // делаем числом
				loadProducts(currentCategory, $('#sort').val());						
			});			

			// выбор сортировки
			$('#sort').on('change', function () {
				loadProducts(currentCategory, $(this).val());
			});

			// при загрузке страницы проверяем параметры
			const urlParams = new URLSearchParams(window.location.search);
			const initialCategory = parseInt(urlParams.get('category')) || 0;
			const initialSort = urlParams.get('sort') || "";

			$('#sort').val(initialSort);			

			// ВСЕГДА грузим товары через AJAX
			loadProducts(initialCategory, initialSort);
			
			// функция для показа/скрытия кнопки фильтров
			function toggleFiltersHeader() {
				if (currentCategory > 0) {
					$('#filtersHeader').removeClass('d-none');
				} else {
					$('#filtersHeader').addClass('d-none');
				}
			}
			});
