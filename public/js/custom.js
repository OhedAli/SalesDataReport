
! function (s) {
    "use strict";
    s(document).on("scroll", function () {
        100 < s(this).scrollTop() ? s(".scroll-to-top").fadeIn() : s(".scroll-to-top").fadeOut()
    }), s(document).on("click", "a.scroll-to-top", function (e) {
        s("html, body").stop().animate({
            scrollTop: s('.slim-header').offset().top
        }), e.preventDefault()
    })
}(jQuery);

function markActiveNav(page) {
    //console.log(page);
    $(".nav-item").removeClass('active');
    if(page == 'sales-view')
        $("a[name='saleslogs']").parent().addClass('active');
    else
        $("a[name='"+page+"']").parent().addClass('active');
}
