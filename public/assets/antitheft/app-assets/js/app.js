!(function (n, e, s) {
    "use strict";
    var l = s("html"),
        c = s("body");
    s(n).on("load", function () {
        s("html").data("textdirection"),
            setTimeout(function () {
                l.removeClass("loading").addClass("loaded");
            }, 1200),
            s.app.menu.init(!1);
        !1 === s.app.nav.initialized && s.app.nav.init({ speed: 300 }),
            Unison.on("change", function (e) {
                s.app.menu.change();
            });
        var e = s(".main-menu").data("img"),
            n = s(".navigation-background");
        0 < n.length && void 0 !== e && n.css("background-image", 'url("' + e + '")'), s('[data-toggle="tooltip"]').tooltip({ container: "body" });
        var a = s(".header-navbar .navbar-search-close");
        s(a).click(function (e) {
            s(".navbar-search .dropdown-toggle").click();
        }),
            0 < s(".navbar-hide-on-scroll").length &&
                (s(".navbar-hide-on-scroll.fixed-top").headroom({ offset: 205, tolerance: 5, classes: { initial: "headroom", pinned: "headroom--pinned-top", unpinned: "headroom--unpinned-top" } }),
                s(".navbar-hide-on-scroll.fixed-bottom").headroom({ offset: 205, tolerance: 5, classes: { initial: "headroom", pinned: "headroom--pinned-bottom", unpinned: "headroom--unpinned-bottom" } })),
            setTimeout(function () {
                s("body").hasClass("vertical-content-menu") &&
                    (function () {
                        var e = s(".main-menu").height();
                        s(".content-body").height() < e && s(".content-body").css("height", e);
                    })();
            }, 500),
            s('a[data-action="collapse"]').on("click", function (e) {
                e.preventDefault(), s(this).closest(".card").children(".card-content").collapse("toggle"), s(this).closest(".card").find('[data-action="collapse"] i').toggleClass("ft-plus ft-minus");
            }),
            s('a[data-action="expand"]').on("click", function (e) {
                e.preventDefault(), s(this).closest(".card").find('[data-action="expand"] i').toggleClass("ft-maximize ft-minimize"), s(this).closest(".card").toggleClass("card-fullscreen");
            }),
            s(".scrollable-container").each(function () {
                new PerfectScrollbar(s(this)[0], { wheelPropagation: !1 });
            }),
            s('a[data-action="reload"]').on("click", function () {
                s(this)
                    .closest(".card")
                    .block({ message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>', timeout: 2e3, overlayCSS: { backgroundColor: "#FFF", cursor: "wait" }, css: { border: 0, padding: 0, backgroundColor: "none" } });
            }),
            s('a[data-action="close"]').on("click", function () {
                s(this).closest(".card").removeClass().slideUp("fast");
            }),
            setTimeout(function () {
                s(".row.match-height").each(function () {
                    s(this).find(".card").not(".card .card").matchHeight();
                });
            }, 500),
            s('.card .heading-elements a[data-action="collapse"]').on("click", function () {
                var e,
                    n = s(this).closest(".card");
                0 < parseInt(n[0].style.height, 10) ? ((e = n.css("height")), n.css("height", "").attr("data-height", e)) : n.data("height") && ((e = n.data("height")), n.css("height", e).attr("data-height", ""));
            });
        var t = c.data("menu");
        "vertical-compact-menu" != t &&
            "horizontal-menu" != t &&
            ("vertical-menu-modern" == c.data("menu")
                ? "true" === localStorage.getItem("menuLocked") && s(".main-menu-content").find("li.active").parents("li").addClass("open")
                : s(".main-menu-content").find("li.active").parents("li").addClass("open")),
            ("vertical-compact-menu" != t && "horizontal-menu" != t) || (s(".main-menu-content").find("li.active").parents("li:not(.nav-item)").addClass("open"), s(".main-menu-content").find("li.active").parents("li").addClass("active")),
            s(".heading-elements-toggle").on("click", function () {
                s(this).parent().children(".heading-elements").toggleClass("visible");
            });
        var i = s(".chartjs"),
            o = i.children("canvas").attr("height");
        i.css("height", o),
            s(".nav-link-search").on("click", function () {
                s(this);
                var e = s(this).siblings(".search-input");
                e.hasClass("open") ? e.removeClass("open") : e.addClass("open");
            });
    }),
        s(e).on("click", ".menu-toggle, .modern-nav-toggle", function (e) {
            return (
                e.preventDefault(),
                s.app.menu.toggle(),
                setTimeout(function () {
                    s(n).trigger("resize");
                }, 200),
                0 < s("#collapsed-sidebar").length &&
                    setTimeout(function () {
                        c.hasClass("menu-expanded") || c.hasClass("menu-open") ? s("#collapsed-sidebar").prop("checked", !1) : s("#collapsed-sidebar").prop("checked", !0);
                    }, 1e3),
                !1
            );
        }),
        s(e).on("click", ".close-navbar", function (e) {
            e.preventDefault(), s.app.menu.toggle();
        }),
        s(e).on("click", ".open-navbar-container", function (e) {
            var n = Unison.fetch.now();
            s.app.menu.drillDownMenu(n.name);
        }),
        s(e).on("click", ".main-menu-footer .footer-toggle", function (e) {
            return e.preventDefault(), s(this).find("i").toggleClass("pe-is-i-angle-down pe-is-i-angle-up"), s(".main-menu-footer").toggleClass("footer-close footer-open"), !1;
        }),
        s(".navigation").find("li").has("ul").addClass("has-sub"),
        s(".carousel").carousel({ interval: 2e3 }),
        s(".nav-link-expand").on("click", function (e) {
            "undefined" != typeof screenfull && screenfull.enabled && screenfull.toggle();
        }),
        "undefined" != typeof screenfull &&
            screenfull.enabled &&
            s(e).on(screenfull.raw.fullscreenchange, function () {
                screenfull.isFullscreen ? s(".nav-link-expand").find("i").toggleClass("ft-minimize ft-maximize") : s(".nav-link-expand").find("i").toggleClass("ft-maximize ft-minimize");
            }),
        s(e).on("click", ".mega-dropdown-menu", function (e) {
            e.stopPropagation();
        }),
        s(e).ready(function () {
            s(".step-icon").each(function () {
                var e = s(this);
                0 < e.siblings("span.step").length && (e.siblings("span.step").empty(), s(this).appendTo(s(this).siblings("span.step")));
            });
        }),
        s(n).resize(function () {
            s.app.menu.manualScroller.updateHeight();
        });
})(window, document, jQuery);
