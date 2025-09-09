let currentCategory = 0;

function loadProducts(categoryId = 0, sort = "", filters = {}) {
    categoryId = parseInt(categoryId) || 0;
    currentCategory = categoryId;

    $.ajax({
        url: 'get_products.php',
        type: 'GET',
        data: { 
            id: categoryId, 
            sort: sort, 
            filters: JSON.stringify(filters)
        },
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

            if (filters && (filters.lengths?.length || filters.tests?.length)) {
				if (filters.lengths?.length) {
					params.set('lengths', filters.lengths.join(','));
				} else {
					params.delete('lengths');
				}

				if (filters.tests?.length) {
					params.set('tests', filters.tests.join(','));
				} else {
					params.delete('tests');
				}
			} else {
				params.delete('lengths');
				params.delete('tests');
			}

            let newUrl = window.location.pathname;
            let queryString = params.toString();
            if (queryString) {
                newUrl += '?' + queryString;
            }
            history.replaceState({}, '', newUrl);

            // подсветка категории
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

    localStorage.setItem('cart', JSON.stringify(cart));
}

function toggleFiltersHeader() {
    if (currentCategory > 0) {
        $('#filtersHeader').removeClass('d-none');
    } else {
        $('#filtersHeader').addClass('d-none');
    }
}

$(document).ready(function () {
    // клик по категории
    $(document).on('click', '.category-link', function (e) {
        e.preventDefault();
        currentCategory = parseInt($(this).data('id'));
        loadProducts(currentCategory, $('#sort').val());
    });

    // выбор сортировки
    $('#sort').on('change', function () {
        loadProducts(currentCategory, $(this).val());
    });

    // начальная загрузка
    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = parseInt(urlParams.get('category')) || 0;
    const initialSort = urlParams.get('sort') || "";
    const initialFilters = urlParams.get('filters') ? JSON.parse(urlParams.get('filters')) : {};

    $('#sort').val(initialSort);

    loadProducts(initialCategory, initialSort, initialFilters);
});
