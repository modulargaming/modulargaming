/**
 * Modular Gaming Inventory view handler.
 */

$(document).ready(function () {
    $('#item-actions > li[class!="dropdown"] > a').click(function (e) {
        e.preventDefault();
        $(this).parent('li').find('form').submit();
    });

    $('#item-actions > li.dropdown > a').click(function (e) {
        e.preventDefault();
        $(this).dropdown('toggle');
    });
});