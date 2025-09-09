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
    }
initializeModalFiltersCategory();

// загружаем фильтры только при открытии модалки
$('#open_modal_filters_category').on('click', function() {
    if (currentCategory > 0) {   // ← используем ту же переменную
        loadFilters(currentCategory);
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
                        `<button class="filter-btn" data-type="length" data-value="${len}">${len}</button>`
                    );
                });
            }

            if (data.tests.length > 0) {
                data.tests.forEach(function(test) {
                    testContainer.append(
                        `<button class="filter-btn" data-type="test" data-value="${test}">${test}</button>`
                    );
                });
            }
        }
    });
}

// делегирование кликов на кнопки фильтров
jQuery(document).on("click", ".filter-btn", function () {
    jQuery(this).toggleClass("active");
});

jQuery("#filter_modal_reset button").on("click", function () {
    jQuery(".filter-btn").removeClass("active");    
});
		      
