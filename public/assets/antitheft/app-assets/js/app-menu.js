!(function (n, e, d) {
    "use strict";
    d.app = d.app || {};
    var l = d("body"),
        m = d(n),
        r = d('div[data-menu="menu-wrapper"]').html(),
        u = d('div[data-menu="menu-wrapper"]').attr("class");
    (d.app.menu = {
        expanded: null,
        collapsed: null,
        hidden: null,
        container: null,
        horizontalMenu: !1,
        manualScroller: {
            obj: null,
            init: function () {
                d(".main-menu").hasClass("menu-dark");
                this.obj = new PerfectScrollbar(".main-menu-content", { wheelPropagation: !1 });
            },
            update: function () {
                if (this.obj) {
                    var e;
                    if (!0 === d(".main-menu").data("scroll-to-active"))
                        (e = 0 < d(".main-menu-content").find("li.active").parents("li").length ? d(".main-menu-content").find("li.active").parents("li").last().position() : d(".main-menu-content").find("li.active").position()),
                            setTimeout(function () {
                                void 0 !== e && d.app.menu.container.stop().animate({ scrollTop: e.top }, 300), d(".main-menu").data("scroll-to-active", "false");
                            }, 300);
                    this.obj.update();
                }
            },
            enable: function () {
                this.init();
            },
            disable: function () {
                this.obj && this.obj.destroy();
            },
            updateHeight: function () {
                ("vertical-menu" != l.data("menu") && "vertical-menu-modern" != l.data("menu") && "vertical-overlay-menu" != l.data("menu")) ||
                    !d(".main-menu").hasClass("menu-fixed") ||
                    (d(".main-menu-content").css("height", d(n).height() - d(".header-navbar").height() - d(".main-menu-header").outerHeight() - d(".main-menu-footer").outerHeight()), this.update());
            },
        },
        init: function (e) {
            if (0 < d(".main-menu-content").length) {
                this.container = d(".main-menu-content");
                var n = "";
                if ((!0 === e && (n = "collapsed"), "vertical-menu-modern" == l.data("menu"))) {
                    var a = "";
                    "undefined" != typeof Storage && (a = localStorage.getItem("menuLocked")), "false" === a ? this.change("collapsed") : this.change();
                } else this.change(n);
            } else this.drillDownMenu();
        },
        drillDownMenu: function (e) {
            d(".drilldown-menu").length && ("sm" == e || "xs" == e ? "true" == d("#navbar-mobile").attr("aria-expanded") && d(".drilldown-menu").slidingMenu({ backLabel: !0 }) : d(".drilldown-menu").slidingMenu({ backLabel: !0 }));
        },
        change: function (e) {
            var n = Unison.fetch.now();
            this.reset();
            var a,
                i,
                s = l.data("menu");
            if (n)
                switch (n.name) {
                    case "xl":
                    case "lg":
                        "vertical-overlay-menu" === s ? this.hide() : "horizontal-menu" === s && "lg" == n.name ? this.collapse() : "collapsed" === e ? this.collapse(e) : this.expand();
                        break;
                    case "md":
                        "vertical-overlay-menu" === s ? this.hide() : this.collapse();
                        break;
                    case "sm":
                    case "xs":
                        this.hide();
                }
            ("vertical-menu" !== s && "vertical-menu-modern" !== s) || this.toOverlayMenu(n.name),
                l.is(".horizontal-layout") && !l.hasClass(".horizontal-menu-demo") && (this.changeMenu(n.name), d(".menu-toggle").removeClass("is-active")),
                "horizontal-menu" != s && this.drillDownMenu(n.name),
                "xl" == n.name &&
                    (d('body[data-open="hover"] .dropdown')
                        .on("mouseenter", function () {
                            d(this).hasClass("show") ? d(this).removeClass("show") : d(this).addClass("show");
                        })
                        .on("mouseleave", function (e) {
                            d(this).removeClass("show");
                        }),
                    d('body[data-open="hover"] .dropdown a').on("click", function (e) {
                        if ("horizontal-menu" == s && d(this).hasClass("dropdown-toggle")) return !1;
                    })),
                d(".header-navbar").hasClass("navbar-brand-center") && d(".header-navbar").attr("data-nav", "brand-center"),
                "sm" == n.name || "xs" == n.name ? d(".header-navbar[data-nav=brand-center]").removeClass("navbar-brand-center") : d(".header-navbar[data-nav=brand-center]").addClass("navbar-brand-center"),
                d("ul.dropdown-menu [data-toggle=dropdown]").on("click", function (e) {
                    0 < d(this).siblings("ul.dropdown-menu").length && e.preventDefault(), e.stopPropagation(), d(this).parent().siblings().removeClass("show"), d(this).parent().toggleClass("show");
                }),
                "horizontal-menu" == s && ("sm" == n.name || "xs" == n.name ? d(".menu-fixed").length && d(".menu-fixed").unstick() : d(".navbar-fixed").length && d(".navbar-fixed").sticky()),
                ("vertical-menu" !== s && "vertical-overlay-menu" !== s) ||
                    ((jQuery.expr[":"].Contains = function (e, n, a) {
                        return 0 <= (e.textContent || e.innerText || "").toUpperCase().indexOf(a[3].toUpperCase());
                    }),
                    (a = d("#main-menu-navigation")),
                    (i = d(".menu-search")),
                    d(i)
                        .change(function () {
                            var e = d(this).val();
                            if (e) {
                                d(".navigation-header").hide(),
                                    d(a)
                                        .find("li a:not(:Contains(" + e + "))")
                                        .hide()
                                        .parent()
                                        .hide();
                                var n = d(a).find("li a:Contains(" + e + ")");
                                n.parent().hasClass("has-sub")
                                    ? (n.show().parents("li").show().addClass("open").closest("li").children("a").show().children("li").show(), 0 < n.siblings("ul").length && n.siblings("ul").children("li").show().children("a").show())
                                    : n.show().parents("li").show().addClass("open").closest("li").children("a").show();
                            } else d(".navigation-header").show(), d(a).find("li a").show().parent().show().removeClass("open");
                            return d.app.menu.manualScroller.update(), !1;
                        })
                        .keyup(function () {
                            d(this).change();
                        }));
        },
        transit: function (e, n) {
            var a = this;
            l.addClass("changing-menu"),
                e.call(a),
                l.hasClass("vertical-layout") &&
                    (l.hasClass("menu-open") || l.hasClass("menu-expanded")
                        ? (d(".menu-toggle").addClass("is-active"), "vertical-menu" === l.data("menu") && d(".main-menu-header") && d(".main-menu-header").show())
                        : (d(".menu-toggle").removeClass("is-active"), "vertical-menu" === l.data("menu") && d(".main-menu-header") && d(".main-menu-header").hide())),
                setTimeout(function () {
                    n.call(a), l.removeClass("changing-menu"), a.update();
                }, 500);
        },
        open: function () {
            this.transit(
                function () {
                    l.removeClass("menu-hide menu-collapsed").addClass("menu-open"), (this.hidden = !1), (this.expanded = !0);
                },
                function () {
                    !d(".main-menu").hasClass("menu-native-scroll") &&
                        d(".main-menu").hasClass("menu-fixed") &&
                        (this.manualScroller.enable(), d(".main-menu-content").css("height", d(n).height() - d(".header-navbar").height() - d(".main-menu-header").outerHeight() - d(".main-menu-footer").outerHeight()));
                }
            );
        },
        hide: function () {
            this.transit(
                function () {
                    l.removeClass("menu-open menu-expanded").addClass("menu-hide"), (this.hidden = !0), (this.expanded = !1);
                },
                function () {
                    !d(".main-menu").hasClass("menu-native-scroll") && d(".main-menu").hasClass("menu-fixed") && this.manualScroller.enable();
                }
            );
        },
        expand: function () {
            !1 === this.expanded &&
                ("vertical-menu-modern" == l.data("menu") && (d(".modern-nav-toggle").find(".toggle-icon").removeClass("ft-circle").addClass("ft-disc"), "undefined" != typeof Storage && localStorage.setItem("menuLocked", "true")),
                this.transit(
                    function () {
                        l.removeClass("menu-collapsed").addClass("menu-expanded"), (this.collapsed = !1), (this.expanded = !0);
                    },
                    function () {
                        d(".main-menu").hasClass("menu-native-scroll") || "horizontal-menu" == l.data("menu") ? this.manualScroller.disable() : d(".main-menu").hasClass("menu-fixed") && this.manualScroller.enable(),
                            ("vertical-menu" != l.data("menu") && "vertical-menu-modern" != l.data("menu")) ||
                                !d(".main-menu").hasClass("menu-fixed") ||
                                d(".main-menu-content").css("height", d(n).height() - d(".header-navbar").height() - d(".main-menu-header").outerHeight() - d(".main-menu-footer").outerHeight());
                    }
                ));
        },
        collapse: function (e) {
            !1 === this.collapsed &&
                ("vertical-menu-modern" == l.data("menu") && (d(".modern-nav-toggle").find(".toggle-icon").removeClass("ft-disc").addClass("ft-circle"), "undefined" != typeof Storage && localStorage.setItem("menuLocked", "false")),
                this.transit(
                    function () {
                        l.removeClass("menu-expanded").addClass("menu-collapsed"), (this.collapsed = !0), (this.expanded = !1);
                    },
                    function () {
                        "horizontal-menu" == l.data("menu") && l.hasClass("vertical-overlay-menu") && d(".main-menu").hasClass("menu-fixed") && this.manualScroller.enable(),
                            ("vertical-menu" != l.data("menu") && "vertical-menu-modern" != l.data("menu")) ||
                                !d(".main-menu").hasClass("menu-fixed") ||
                                (d(".main-menu-content").css("height", d(n).height() - d(".header-navbar").height()), d(".main-menu-content").hasClass("ps") || this.manualScroller.enable());
                    }
                ));
        },
        toOverlayMenu: function (e) {
            var n = l.data("menu");
            "sm" == e || "xs" == e ? l.hasClass(n) && l.removeClass(n).addClass("vertical-overlay-menu") : l.hasClass("vertical-overlay-menu") && l.removeClass("vertical-overlay-menu").addClass(n);
        },
        changeMenu: function (e) {
            d('div[data-menu="menu-wrapper"]').html(""), d('div[data-menu="menu-wrapper"]').html(r);
            var n = d('div[data-menu="menu-wrapper"]'),
                a = (d('div[data-menu="menu-container"]'), d('ul[data-menu="menu-navigation"]')),
                i = d('li[data-menu="megamenu"]'),
                s = d("li[data-mega-col]"),
                t = d('li[data-menu="dropdown"]'),
                o = d('li[data-menu="dropdown-submenu"]');
            "sm" == e || "xs" == e
                ? (l.removeClass(l.data("menu")).addClass("vertical-layout vertical-overlay-menu fixed-navbar"),
                  d("nav.header-navbar").addClass("fixed-top"),
                  n.removeClass().addClass("main-menu menu-light menu-fixed menu-shadow"),
                  a.removeClass().addClass("navigation navigation-main"),
                  i.removeClass("dropdown mega-dropdown").addClass("has-sub"),
                  i.children("ul").removeClass(),
                  s.each(function (e, n) {
                      d(n).find(".mega-menu-sub").find("li").has("ul").addClass("has-sub");
                      var a = d(n).children().first(),
                          i = "";
                      a.is("h6") &&
                          ((i = a.html()),
                          a.remove(),
                          d(n)
                              .prepend('<a href="#">' + i + "</a>")
                              .addClass("has-sub mega-menu-title"));
                  }),
                  i.find("a").removeClass("dropdown-toggle"),
                  i.find("a").removeClass("dropdown-item"),
                  t.removeClass("dropdown").addClass("has-sub"),
                  t.find("a").removeClass("dropdown-toggle nav-link"),
                  t.children("ul").find("a").removeClass("dropdown-item"),
                  t.find("ul").removeClass("dropdown-menu"),
                  o.removeClass().addClass("has-sub"),
                  d.app.nav.init(),
                  d("ul.dropdown-menu [data-toggle=dropdown]").on("click", function (e) {
                      e.preventDefault(), e.stopPropagation(), d(this).parent().siblings().removeClass("open"), d(this).parent().toggleClass("open");
                  }))
                : (l.removeClass("vertical-layout vertical-overlay-menu fixed-navbar").addClass(l.data("menu")),
                  d("nav.header-navbar").removeClass("fixed-top"),
                  n.removeClass().addClass(u),
                  this.drillDownMenu(e),
                  d("a.dropdown-item.nav-has-children").on("click", function () {
                      event.preventDefault(), event.stopPropagation();
                  }),
                  d("a.dropdown-item.nav-has-parent").on("click", function () {
                      event.preventDefault(), event.stopPropagation();
                  }));
        },
        toggle: function () {
            var e = Unison.fetch.now(),
                n = (this.collapsed, this.expanded),
                a = this.hidden,
                i = l.data("menu");
            switch (e.name) {
                case "xl":
                case "lg":
                case "md":
                    !0 === n ? ("vertical-overlay-menu" == i ? this.hide() : this.collapse()) : "vertical-overlay-menu" == i ? this.open() : this.expand();
                    break;
                case "sm":
                case "xs":
                    !0 === a ? this.open() : this.hide();
            }
            this.drillDownMenu(e.name);
        },
        update: function () {
            this.manualScroller.update();
        },
        reset: function () {
            (this.expanded = !1), (this.collapsed = !1), (this.hidden = !1), l.removeClass("menu-hide menu-open menu-collapsed menu-expanded");
        },
    }),
        (d.app.nav = {
            container: d(".navigation-main"),
            initialized: !1,
            navItem: d(".navigation-main").find("li").not(".navigation-category"),
            config: { speed: 300 },
            init: function (e) {
                (this.initialized = !0), d.extend(this.config, e), this.bind_events();
            },
            bind_events: function () {
                var o = this;
                d(".navigation-main")
                    .on("mouseenter.app.menu", "li", function () {
                        var e = d(this);
                        if ((d(".hover", ".navigation-main").removeClass("hover"), l.hasClass("menu-collapsed") && "vertical-menu-modern" != l.data("menu"))) {
                            d(".main-menu-content").children("span.menu-title").remove(), d(".main-menu-content").children("a.menu-title").remove(), d(".main-menu-content").children("ul.menu-content").remove();
                            var n,
                                a,
                                i = e.find("span.menu-title").clone();
                            e.hasClass("has-sub") || ((n = e.find("span.menu-title").text()), (a = e.children("a").attr("href")), "" !== n && ((i = d("<a>")).attr("href", a), i.attr("title", n), i.text(n), i.addClass("menu-title")));
                            var s,
                                t = d(".navbar-header").length ? d(".navbar-header").height() : 0;
                            if (
                                ((s = e.css("border-top") ? t + e.position().top + parseInt(e.css("border-top"), 10) : t + e.position().top),
                                i.appendTo(".main-menu-content").css({ position: "fixed", top: s }),
                                e.hasClass("has-sub") && e.hasClass("nav-item"))
                            ) {
                                e.children("ul:first");
                                o.adjustSubmenu(e);
                            }
                        }
                        e.addClass("hover");
                    })
                    .on("mouseleave.app.menu", "li", function () {})
                    .on("active.app.menu", "li", function (e) {
                        d(this).addClass("active"), e.stopPropagation();
                    })
                    .on("deactive.app.menu", "li.active", function (e) {
                        d(this).removeClass("active"), e.stopPropagation();
                    })
                    .on("open.app.menu", "li", function (e) {
                        var n = d(this);
                        if ((n.addClass("open"), o.expand(n), d(".main-menu").hasClass("menu-collapsible"))) return !1;
                        n.siblings(".open").find("li.open").trigger("close.app.menu"), n.siblings(".open").trigger("close.app.menu"), e.stopPropagation();
                    })
                    .on("close.app.menu", "li.open", function (e) {
                        var n = d(this);
                        n.removeClass("open"), o.collapse(n), e.stopPropagation();
                    })
                    .on("click.app.menu", "li", function (e) {
                        var n = d(this);
                        n.is(".disabled")
                            ? e.preventDefault()
                            : l.hasClass("menu-collapsed") && "vertical-menu-modern" != l.data("menu")
                            ? e.preventDefault()
                            : n.has("ul")
                            ? n.is(".open")
                                ? n.trigger("close.app.menu")
                                : n.trigger("open.app.menu")
                            : n.is(".active") || (n.siblings(".active").trigger("deactive.app.menu"), n.trigger("active.app.menu")),
                            e.stopPropagation();
                    }),
                    d(".navbar-header, .main-menu")
                        .on("mouseenter", function () {
                            if ("vertical-menu-modern" == l.data("menu") && (d(".main-menu, .navbar-header").addClass("expanded"), l.hasClass("menu-collapsed"))) {
                                var e = d(".main-menu li.menu-collapsed-open"),
                                    n = e.children("ul");
                                n.hide().slideDown(200, function () {
                                    d(this).css("display", "");
                                }),
                                    e.addClass("open").removeClass("menu-collapsed-open");
                            }
                        })
                        .on("mouseleave", function () {
                            l.hasClass("menu-collapsed") &&
                                "vertical-menu-modern" == l.data("menu") &&
                                setTimeout(function () {
                                    if (0 === d(".main-menu:hover").length && 0 === d(".navbar-header:hover").length && (d(".main-menu, .navbar-header").removeClass("expanded"), l.hasClass("menu-collapsed"))) {
                                        var e = d(".main-menu li.open"),
                                            n = e.children("ul");
                                        e.addClass("menu-collapsed-open"),
                                            n.show().slideUp(200, function () {
                                                d(this).css("display", "");
                                            }),
                                            e.removeClass("open");
                                    }
                                }, 1);
                        }),
                    d(".main-menu-content").on("mouseleave", function () {
                        l.hasClass("menu-collapsed") && (d(".main-menu-content").children("span.menu-title").remove(), d(".main-menu-content").children("a.menu-title").remove(), d(".main-menu-content").children("ul.menu-content").remove()),
                            d(".hover", ".navigation-main").removeClass("hover");
                    }),
                    d(".navigation-main li.has-sub > a").on("click", function (e) {
                        e.preventDefault();
                    }),
                    d("ul.menu-content").on("click", "li", function (e) {
                        var n = d(this);
                        if (n.is(".disabled")) e.preventDefault();
                        else if (n.has("ul"))
                            if (n.is(".open")) n.removeClass("open"), o.collapse(n);
                            else {
                                if ((n.addClass("open"), o.expand(n), d(".main-menu").hasClass("menu-collapsible"))) return !1;
                                n.siblings(".open").find("li.open").trigger("close.app.menu"), n.siblings(".open").trigger("close.app.menu"), e.stopPropagation();
                            }
                        else n.is(".active") || (n.siblings(".active").trigger("deactive.app.menu"), n.trigger("active.app.menu"));
                        e.stopPropagation();
                    });
            },
            adjustSubmenu: function (e) {
                var n,
                    a,
                    i,
                    s,
                    t,
                    o,
                    l = e.children("ul:first"),
                    r = l.clone(!0);
                (n = d(".navbar-header").height()),
                    (a = e.position().top),
                    (s = m.height() - d(".header-navbar").height()),
                    (o = 0),
                    l.height(),
                    0 < parseInt(e.css("border-top"), 10) && (o = parseInt(e.css("border-top"), 10)),
                    (t = s - a - e.height() - 30),
                    d(".main-menu").hasClass("menu-dark"),
                    (i = n + a + e.height() + o),
                    r.addClass("menu-popout").appendTo(".main-menu-content").css({ top: i, position: "fixed", "max-height": t });
                new PerfectScrollbar(".main-menu-content > ul.menu-content");
            },
            collapse: function (e, n) {
                e.children("ul")
                    .show()
                    .slideUp(d.app.nav.config.speed, function () {
                        d(this).css("display", ""), d(this).find("> li").removeClass("is-shown"), n && n(), d.app.nav.container.trigger("collapsed.app.menu");
                    });
            },
            expand: function (e, n) {
                var a = e.children("ul"),
                    i = a.children("li").addClass("is-hidden");
                a.hide().slideDown(d.app.nav.config.speed, function () {
                    d(this).css("display", ""), n && n(), d.app.nav.container.trigger("expanded.app.menu");
                }),
                    setTimeout(function () {
                        i.addClass("is-shown"), i.removeClass("is-hidden");
                    }, 0);
            },
            refresh: function () {
                d.app.nav.container.find(".open").removeClass("open");
            },
        });
})(window, document, jQuery);
