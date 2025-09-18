let currentCategory = 0;

function loadProducts(categoryId = 0, sort = "", filters = {}) {
    categoryId = parseInt(categoryId) || 0;
    currentCategory = categoryId;

    // всегда берём актуальные значения из слайдера
    let minPrice = $("#price-range").slider("values", 0);
    let maxPrice = $("#price-range").slider("values", 1);

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

            // сохраняем цену в URL (из слайдера)
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
    // инициализация слайдера на базе PHP диапазона
    $("#price-range").slider({
        range: true,
        min: window.priceRange.min,
        max: window.priceRange.max,
        values: [window.priceRange.min, window.priceRange.max],
        slide: function (event, ui) {
            $("#price-min").text(ui.values[0]);
            $("#price-max").text(ui.values[1]);
        },
        change: function () {
            loadProducts(currentCategory, $('#sort').val());
        }
    });

    // клик по категории
    $(document).on('click', '.category-link', function (e) {
        e.preventDefault();
        currentCategory = parseInt($(this).data('id'));
        updatePriceRange(currentCategory, function () {
            loadProducts(currentCategory, $('#sort').val());
        });
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
    const initialMin = parseInt(urlParams.get('min_price'));
    const initialMax = parseInt(urlParams.get('max_price'));

    // сначала обновляем диапазон по категории, а потом применяем min/max из URL
    updatePriceRange(initialCategory, function () {
        if (!isNaN(initialMin) && !isNaN(initialMax)) {
            $("#price-range").slider("values", [initialMin, initialMax]);
            $("#price-min").text(initialMin);
            $("#price-max").text(initialMax);
        }

        $('#sort').val(initialSort);

        loadProducts(initialCategory, initialSort, initialFilters);
    });
});

function updatePriceRange(categoryId, callback) {
    $.get('get_price_range.php', { category: categoryId }, function(response) {
        let data = JSON.parse(response);

        // разрушаем старый слайдер и создаём новый с min/max
        $("#price-range").slider("destroy").slider({
            range: true,
            min: data.min,
            max: data.max,
            values: [data.min, data.max],
            slide: function(event, ui) {
                $("#price-min").text(ui.values[0]);
                $("#price-max").text(ui.values[1]);
            },
            change: function() {
                // только когда пользователь закончил движение ползунка
                loadProducts(currentCategory, $('#sort').val());
            }
        });

        // обновляем подписи
        $("#price-min").text(data.min);
        $("#price-max").text(data.max);

        if (typeof callback === "function") callback();
    });
}