let currentCategory = 0;
let minPrice = 0;
let maxPrice = 10000; 

function loadProducts(categoryId = 0, sort = "", filters = {}) {
    categoryId = parseInt(categoryId) || 0;
    currentCategory = categoryId;

    $.ajax({
        url: 'get_products.php',
        type: 'GET',
        data: { 
            id: categoryId, 
            sort: sort, 
            filters: JSON.stringify(filters),
            min_price: minPrice,
            max_price: maxPrice
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

            if (filters && (filters.brands?.length || filters.lengths?.length || filters.tests?.length)) {
				if (filters.brands?.length) {
					params.set('brands', filters.brands.join(','));
				} else {
					params.delete('brands');
				}
                
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
				params.delete('brands');
                params.delete('lengths');
				params.delete('tests');
			}

            // сохраняем цену в URL
            params.set('min_price', minPrice);
            params.set('max_price', maxPrice);

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
    // инициализация ползунка цены
    $("#price-range").slider({
        range: true,
        min: 0,
        max: 10000,
        values: [0, 10000],
        slide: function (event, ui) {
            $("#price-min").text(ui.values[0]);
            $("#price-max").text(ui.values[1]);
            minPrice = ui.values[0];
            maxPrice = ui.values[1];
        },
        change: function (event, ui) {
            minPrice = ui.values[0];
            maxPrice = ui.values[1];
            loadProducts(currentCategory, $('#sort').val());
        }
    });

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

    // восстанавливаем цену из URL, если была
    minPrice = parseInt(urlParams.get('min_price')) || 0;
    maxPrice = parseInt(urlParams.get('max_price')) || 10000;
    $("#price-range").slider("values", [minPrice, maxPrice]);
    $("#price-min").text(minPrice);
    $("#price-max").text(maxPrice);

    $('#sort').val(initialSort);

    loadProducts(initialCategory, initialSort, initialFilters);
});