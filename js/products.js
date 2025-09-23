let currentCategory = 0;

function loadProducts(categoryId = 0, sort = "", filters = {}) {
    categoryId = parseInt(categoryId) || 0;
    currentCategory = categoryId;

    // всегда берём актуальные значения из слайдера
    let minPrice = $("#price-range").slider("values", 0);
    let maxPrice = $("#price-range").slider("values", 1);
    let cart = [];

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
                if (params.has('category')) {
                    params.delete('category');
                }
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
    if (currentCategory > 0) $('#filtersHeader').removeClass('d-none');
    else $('#filtersHeader').addClass('d-none');
}

// ————— helper: надёжно установить значения слайдера —————
function setSliderValues(min, max, cb) {
    const $slider = $("#price-range");

    // если слайдера ещё нет / он пересоздаётся — повторим попытку
    if (!$slider.length || !$slider.hasClass('ui-slider')) {
        // подождать немного и повторить (т.к. updatePriceRange делает destroy/slider)
        setTimeout(function () {
            setSliderValues(min, max, cb);
        }, 50);
        return;
    }

    const sliderMin = $slider.slider("option", "min");
    const sliderMax = $slider.slider("option", "max");

    // валидация
    if (!Number.isFinite(min)) min = sliderMin;
    if (!Number.isFinite(max)) max = sliderMax;
    if (min < sliderMin) min = sliderMin;
    if (max > sliderMax) max = sliderMax;
    if (min > max) max = min;

    // обновляем инпуты (на случай исправления)
    $('#price-min').val(min);
    $('#price-max').val(max);

    // ставим значения (несколько способов — для надёжности)
    try {
        $slider.slider("values", [min, max]); // иногда работает
    } catch (e) { /* ignore */ }
    // ставим по-индексно
    $slider.slider("values", 0, min);
    $slider.slider("values", 1, max);

    // на всякий случай триггерим события слайдера (если где-то слушают)
    $slider.trigger("slide", { values: [min, max] });
    $slider.trigger("change", { values: [min, max] });

    if (typeof cb === "function") cb();
}

// ————— синхронизатор, вызываемый по blur / Enter —————
function syncSliderWithInputs() {
    let min = Number($('#price-min').val());
    let max = Number($('#price-max').val());

    // Если пользователь ввёл не число — пусть дальнейшая логика поправит
    setSliderValues(min, max, function () {
        // загрузить товары уже после того как слайдер установился
        loadProducts(currentCategory, $('#sort').val());
    });
}

$(document).ready(function () {
    // инициализация слайдера на базе глобального диапазона
    $("#price-range").slider({
        range: true,
        min: window.priceRange.min,
        max: window.priceRange.max,
        values: [window.priceRange.min, window.priceRange.max],
        slide: function(event, ui) {
            $("#price-min").val(ui.values[0]);
            $("#price-max").val(ui.values[1]);
        },
        change: function () {
            loadProducts(currentCategory, $('#sort').val());
        }
    });

    // привязки — один раз, делегировано
    $(document).off('blur', '#price-min, #price-max', syncSliderWithInputs);
    $(document).on('blur', '#price-min, #price-max', syncSliderWithInputs);

    // Enter в инпуте — применяем тоже
    $(document).off('keydown', '#price-min, #price-max');
    $(document).on('keydown', '#price-min, #price-max', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur(); // вызовет syncSliderWithInputs
        }
    });

    // клик по категории
    $(document).on('click', '.category-link', function (e) {
        e.preventDefault();
        currentCategory = parseInt($(this).data('id'));
        updatePriceRange(currentCategory, function () {
            loadProducts(currentCategory, $('#sort').val());
        }, true); // сброс на min/max категории
    });

    // выбор сортировки
    $('#sort').on('change', function () {
        loadProducts(currentCategory, $(this).val());
    });

    // начальная загрузка
    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = urlParams.has('category') 
        ? parseInt(urlParams.get('category')) || 0 
        : currentCategory;

    currentCategory = initialCategory; 

    const initialSort = urlParams.get('sort') || "";
    const initialFilters = urlParams.get('filters') ? JSON.parse(urlParams.get('filters')) : {};
    const initialMin = parseInt(urlParams.get('min_price'));
    const initialMax = parseInt(urlParams.get('max_price'));

    // обновляем диапазон по категории
    updatePriceRange(initialCategory, function () {
        if (!isNaN(initialMin) && !isNaN(initialMax)) {
            $("#price-range").slider("values", [initialMin, initialMax]);
            $("#price-min").val(initialMin);
            $("#price-max").val(initialMax);
        }
        $('#sort').val(initialSort);
        loadProducts(initialCategory, initialSort, initialFilters);
    }, false); // при загрузке — не сбрасываем, а применяем min/max из URL
});

function updatePriceRange(categoryId, callback, resetToCategoryRange = true) {
    $.get('get_price_range.php', { category: categoryId }, function(response) {
        let data = JSON.parse(response);

        // пересоздаём слайдер
        $("#price-range").slider("destroy").slider({
            range: true,
            min: data.min,
            max: data.max,
            values: [data.min, data.max],
            slide: function(event, ui) {
                $("#price-min").val(ui.values[0]);
                $("#price-max").val(ui.values[1]);
            },
            change: function() {
                loadProducts(currentCategory, $('#sort').val());
            }
        });

        if (resetToCategoryRange) {
            // сброс на min/max новой категории
            setSliderValues(data.min, data.max);
        } else {
            // оставляем текущее из инпутов (или URL)
            let min = parseInt($("#price-min").val()) || data.min;
            let max = parseInt($("#price-max").val()) || data.max;
            setSliderValues(min, max);
        }

        if (typeof callback === "function") callback();
    });
}






