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
