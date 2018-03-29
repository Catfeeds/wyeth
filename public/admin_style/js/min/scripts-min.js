$(document).ready(function() {
    $(".wp1").waypoint(function() {
        $(".wp1").addClass("animated fadeInLeft")
    }, {
        offset: "75%"
    });
    $(".wp2").waypoint(function() {
        $(".wp2").addClass("animated fadeInDown")
    }, {
        offset: "75%"
    });
    $(".wp3").waypoint(function() {
        $(".wp3").addClass("animated fadeInRight")
    }, {
        offset: "75%"
    });
    $(".wp4").waypoint(function() {
        $(".wp4").addClass("animated fadeInDown")
    }, {
        offset: "75%"
    });
    $(".wp5").waypoint(function() {
        $(".wp5").addClass("animated fadeInUp")
    }, {
        offset: "75%"
    });
    $("#clientsSlider").flickity({
        cellAlign: "left",
        contain: true,
        prevNextButtons: true,
        autoPlay: true
    });

});
$(document).ready(function() {
    $("a.single_image").fancybox({
        padding: 4
    })
});
$(".nav-toggle").click(function() {
    $(this).toggleClass("active");
    $(".overlay-vectorlab").toggleClass("open")
});
$(".overlay ul li a").click(function() {
    $(".nav-toggle").toggleClass("active");
    $(".overlay-vectorlab").toggleClass("open")
});
$(".overlay").click(function() {
    $(".nav-toggle").toggleClass("active");
    $(".overlay-vectorlab").toggleClass("open")
});
$("a[href*=#]:not([href=#])").click(function() {
    if (location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") && location.hostname === this.hostname) {
        var e = $(this.hash);
        e = e.length ? e : $("[name=" + this.hash.slice(1) + "]");
        if (e.length) {
            $("html,body").animate({
                scrollTop: e.offset().top
            }, 2e3);
            return false
        }
    }
})