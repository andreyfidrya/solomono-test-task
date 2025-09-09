function initializeModalFiltersCategory(){
    const $document = jQuery(document);
    const $backdrop = jQuery("#filters_backdrop");
    const $modal = jQuery("#filters_modal");

    function openModal() {
    $backdrop.fadeIn();
    $modal.addClass("open");
    }		

    $document.on("click", "#open_modal_filters_category", openModal);

    function closeModal() {
    $backdrop.fadeOut();
    $modal.removeClass("open");			
    }

    $document.on("click", "#close_modal_filters_category", closeModal);
    $document.on("click", "#filter_modal_apply", closeModal);    
    }
initializeModalFiltersCategory();

// загружаем фильтры только при открытии модалки
$('#open_modal_filters_category').on('click', function() {
    if (currentCategory > 0) {   // ← используем ту же переменную
        loadFilters(currentCategory);
        setTimeout(updateApplyButtonCount, 100); // даем время на отрисовку кнопок
    }
});

function loadFilters(categoryId) {
    console.log("loadFilters, categoryId:", categoryId); // для отладки
    $.ajax({
        url: 'get_filters.php',
        type: 'GET',
        data: { id: categoryId },
        success: function(response) {
            console.log("get_filters response:", response); // проверяем JSON 
            let data = JSON.parse(response);

            // контейнеры фильтров по id
            let lengthContainer = $('#filter_lengths');
            let testContainer   = $('#filter_tests');

            lengthContainer.empty();
            testContainer.empty();

            if (data.lengths.length > 0) {
                data.lengths.forEach(function(len) {
                    lengthContainer.append(
                        `<button class="filter-btn" data-type="length" data-value="${len}">${len} мм</button>`
                    );
                });
            }

            if (data.tests.length > 0) {
                data.tests.forEach(function(test) {
                    testContainer.append(
                        `<button class="filter-btn" data-type="test" data-value="${test}">${test} гр</button>`
                    );
                });
            }
        }
    });
}

function updateApplyButtonCount() {
    let selectedLengths = [];
    let selectedTests = [];

    $('#filter_lengths .filter-btn.active').each(function () {
        selectedLengths.push($(this).data('value'));
    });

    $('#filter_tests .filter-btn.active').each(function () {
        selectedTests.push($(this).data('value'));
    });

    let filters = {
        lengths: selectedLengths,
        tests: selectedTests
    };

    // AJAX-запрос для подсчета товаров
    $.ajax({
        url: 'get_products.php',
        type: 'GET',
        data: { 
            id: currentCategory, 
            filters: JSON.stringify(filters),
            count_only: 1
        },
        success: function(response) {
            let count = parseInt(response);
            $('#filter_modal_apply').text(`Застосувати (${count})`);
        }
    });
}

// делегирование клика по кнопкам фильтров
$(document).on("click", ".filter-btn", function () {
    $(this).toggleClass("active");
    updateApplyButtonCount();
});

jQuery("#filter_modal_reset").on("click", function () {
    jQuery(".filter-btn").removeClass("active");    
});
		      
$('#filter_modal_apply').on('click', function () {
    let selectedLengths = [];
    let selectedTests = [];

    $('#filter_lengths .filter-btn.active').each(function () {
        selectedLengths.push($(this).data('value'));
    });

    $('#filter_tests .filter-btn.active').each(function () {
        selectedTests.push($(this).data('value'));
    });

    let filters = {
        lengths: selectedLengths,
        tests: selectedTests
    };

    // загружаем товары с фильтрами
    loadProducts(currentCategory, $('#sort').val(), filters);

    // закрываем модалку
    $('#filters_backdrop').fadeOut();
    $('#filters_modal').removeClass('open');
});
