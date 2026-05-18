jQuery(document).ready(function ($) {

    const row = $('.olx-subcats-row');
    const grid = $('.olx-subcats-grid');
    let activeItem = null;

    function placeRow(item) {

        const items = item
            .closest('.olx-cat-grid')
            .children('.olx-cat-item');

        const itemWidth = item.outerWidth(true);
        const gridWidth = item.parent().width();

        // КЛЮЧЕВОЙ ФИКС — ТОЛЬКО floor
        const itemsPerRow = Math.floor(gridWidth / itemWidth) || 1;

        const index = items.index(item);

        const rowEndIndex =
            index - (index % itemsPerRow) + itemsPerRow - 1;

        const target = items.eq(
            Math.min(rowEndIndex, items.length - 1)
        );

        row.insertAfter(target);
    }

    $('.olx-cat-link').on('click', function (e) {
        e.preventDefault();

        const item = $(this).closest('.olx-cat-item');
        const template = item.find('.olx-subcats-template');

        if (!template.length) {
            row.removeClass('is-open');
            activeItem = null;
            return;
        }

        // повторный клик — закрываем
        if (activeItem && activeItem.is(item)) {
            row.removeClass('is-open');
            activeItem = null;
            return;
        }

        activeItem = item;

        grid.html(template.html());
        placeRow(item);
        row.addClass('is-open');
    });

    // корректный перерасчёт при ресайзе
    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (activeItem) {
                placeRow(activeItem);
            }
        }, 100);
    });

});
