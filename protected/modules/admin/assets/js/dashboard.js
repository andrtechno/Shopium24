function footerCssPadding(selector) {
    $('.footer').css({'padding-left': ($(selector).hasClass('active')) ? 250 : 0});
}
$(function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'hover'
    });

    //Slidebar
    var toggle_selector = '#wrapper';
    footerCssPadding(toggle_selector);

    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $(toggle_selector).toggleClass("active");
        $('#menu ul').hide();
        footerCssPadding(toggle_selector);

        $.cookie(toggle_selector.replace(/#/g, ""), $(toggle_selector).hasClass('active'), {
            expires: 300,
            path: window.location.href // fix '/'
        });
    });
});

/*
 function initMenu() {
 $('#menu ul').hide();
 $('#menu ul').children('.current').parent().show();
 $('#menu li a').click(
 function () {
 var checkElement = $(this).next();
 if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
 return false;
 }
 if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
 $('#menu ul:visible').slideUp('normal');
 checkElement.slideDown('normal');
 return false;
 }
 }
 );
 }
 $(document).ready(function () {
 initMenu();
 });
 */

