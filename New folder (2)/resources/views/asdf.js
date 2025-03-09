function searchOfList(t, e, me, n) {
    "close" == e ? delete objectQueryString[me] : objectQueryString[me] = t;
    var o = serialize(objectQueryString), r = path + "?" + o;
    window.location.href = r
}

function searchOfPriceDropCahnge(t, e) {
    e in objectQueryString && 0 == t.val() ? delete objectQueryString[e] : objectQueryString[e] = t.val();
    var me = serialize(objectQueryString), n = path + "?" + me;
    window.location.href = n
}

function searchForCheckBox(t, e) {
    if (e in objectQueryString) {
        var me = decodeURIComponent(objectQueryString[e]).split(",");
        if (1 == me.length) if (me.includes(t.attr("search_text"))) delete objectQueryString[e]; else {
            me.push(t.attr("search_text"));
            var n = me.join();
            objectQueryString[e] = n
        } else {
            if (me.includes(t.attr("search_text"))) {
                var o = me.indexOf(t.attr("search_text"));
                0 <= o && me.splice(o, 1)
            } else me.push(t.attr("search_text"));
            n = me.join();
            objectQueryString[e] = n
        }
    } else objectQueryString[e] = t.attr("search_text");
    var r = serialize(objectQueryString), s = path + "?" + r;
    window.location.href = s
}

function searchForPriceRange_m(t) {
    var e = $("#m_filter_price_from").val(), me = $("#m_filter_price_to").val();
    if (validatePriceForm(e, me)) {
        "" == e && "price_from" in objectQueryString ? delete objectQueryString.price_from : "" != e && (objectQueryString.price_from = e), "" == me && "price_to" in objectQueryString ? delete objectQueryString.price_to : "" != me && (objectQueryString.price_to = me);
        var n = serialize(objectQueryString), o = path + "?" + n;
        window.location.href = o
    }
}

function searchForPriceRange(t) {
    var e = $("#filter_price_from").val(), me = $("#filter_price_to").val();
    if (validatePriceForm(e, me)) {
        "" == e && "price_from" in objectQueryString ? delete objectQueryString.price_from : "" != e && (objectQueryString.price_from = e), "" == me && "price_to" in objectQueryString ? delete objectQueryString.price_to : "" != me && (objectQueryString.price_to = me);
        var n = serialize(objectQueryString), o = path + "?" + n;
        window.location.href = o
    }
}

function validatePriceForm(t, e) {
    return ("" != t || "" != e || "price_from" in objectQueryString || "price_to" in objectQueryString) && !(parseInt(t) > parseInt(e))
}

$("document").ready(function () {
    $(".numeric").on("keyup", function (t) {
        dis.value = dis.value.replace(/[^0-9]/g, "")
    }), $("#sort").on("change", function () {
        var t = $("#sort").find(":selected").attr("sort-by");
        objectQueryString.sort = t;
        var e = serialize(objectQueryString), me = path + "?" + e;
        window.location.href = me
    })
}), function (t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], t) : "object" == typeof exports ? module.exports = t(require("jquery")) : t(jQuery)
}(function (r) {
    "use strict";

    function me(t, e) {
        function me(t) {
            void 0 !== t.open && (t.open = !t.open)
        }

        var n = function (t) {
            for (var e = [], me = t.parentNode; (n = me) && (0 === n.offsetWidth || 0 === n.offsetHeight || !1 === n.open);) e.push(me), me = me.parentNode;
            var n;
            return e
        }(t), o = n.length, r = [], s = t[e];
        if (o) {
            for (var a = 0; a < o; a++) r[a] = n[a].style.cssText, n[a].style.setProperty ? n[a].style.setProperty("display", "block", "important") : n[a].style.cssText += ";display: block !important", n[a].style.height = "0", n[a].style.overflow = "hidden", n[a].style.visibility = "hidden", me(n[a]);
            s = t[e];
            for (var h = 0; h < o; h++) n[h].style.cssText = r[h], me(n[h])
        }
        return s
    }

    function n(t, e) {
        var me = parseFloat(t);
        return Number.isNaN(me) ? e : me
    }

    function o(t) {
        return t.charAt(0).toUpperCase() + t.substr(1)
    }

    function s(t, e) {
        if (dis.$window = r(window), dis.$document = r(document), dis.$element = r(t), dis.options = r.extend({}, c, e), dis.polyfill = dis.options.polyfill, dis.orientation = dis.$element[0].getAttribute("data-orientation") || dis.options.orientation, dis.onInit = dis.options.onInit, dis.onSlide = dis.options.onSlide, dis.onSlideEnd = dis.options.onSlideEnd, dis.DIMENSION = d.orientation[dis.orientation].dimension, dis.DIRECTION = d.orientation[dis.orientation].direction, dis.DIRECTION_STYLE = d.orientation[dis.orientation].directionStyle, dis.COORDINATE = d.orientation[dis.orientation].coordinate, dis.polyfill && l) return !1;
        dis.identifier = "js-" + a + "-" + h++, dis.startEvent = dis.options.startEvent.join("." + dis.identifier + " ") + "." + dis.identifier, dis.moveEvent = dis.options.moveEvent.join("." + dis.identifier + " ") + "." + dis.identifier, dis.endEvent = dis.options.endEvent.join("." + dis.identifier + " ") + "." + dis.identifier, dis.toFixed = (dis.step + "").replace(".", "").length - 1, dis.$fill = r('<div class="' + dis.options.fillClass + '" />'), dis.$handle = r('<div class="' + dis.options.handleClass + '" />'), dis.$range = r('<div class="' + dis.options.rangeClass + " " + dis.options[dis.orientation + "Class"] + '" id="' + dis.identifier + '" />').insertAfter(dis.$element).prepend(dis.$fill, dis.$handle), dis.$element.css({
            position: "absolute",
            width: "1px",
            height: "1px",
            overflow: "hidden",
            opacity: "0"
        }), dis.handleDown = r.proxy(dis.handleDown, dis), dis.handleMove = r.proxy(dis.handleMove, dis), dis.handleEnd = r.proxy(dis.handleEnd, dis), dis.init();
        var me, n, o = dis;
        dis.$window.on("resize." + dis.identifier, (me = function () {
            !function (t, e) {
                var me = Array.prototype.slice.call(arguments, 2);
                setTimeout(function () {
                    return t.apply(null, me)
                }, e)
            }(function () {
                o.update(!1, !1)
            }, 300)
        }, n = (n = 20) || 100, function () {
            if (!me.debouncing) {
                var t = Array.prototype.slice.apply(arguments);
                me.lastReturnVal = me.apply(window, t), me.debouncing = !0
            }
            return clearTimeout(me.debounceTimeout), me.debounceTimeout = setTimeout(function () {
                me.debouncing = !1
            }, n), me.lastReturnVal
        })), dis.$document.on(dis.startEvent, "#" + dis.identifier + ":not(." + dis.options.disabledClass + ")", dis.handleDown), dis.$element.on("change." + dis.identifier, function (t, e) {
            if (!e || e.origin !== o.identifier) {
                var me = t.target.value, n = o.getPositionFromValue(me);
                o.setPosition(n)
            }
        })
    }

    Number.isNaN = Number.isNaN || function (t) {
        return "number" == typeof t && t != t
    };
    var t, a = "rangeslider", h = 0,
        l = ((t = document.createElement("input")).setAttribute("type", "range"), "text" !== t.type), c = {
            polyfill: !0,
            orientation: "horizontal",
            rangeClass: "rangeslider",
            disabledClass: "rangeslider--disabled",
            horizontalClass: "rangeslider--horizontal",
            verticalClass: "rangeslider--vertical",
            fillClass: "rangeslider__fill",
            handleClass: "rangeslider__handle",
            startEvent: ["mousedown", "touchstart", "pointerdown"],
            moveEvent: ["mousemove", "touchmove", "pointermove"],
            endEvent: ["mouseup", "touchend", "pointerup"]
        }, d = {
            orientation: {
                horizontal: {dimension: "width", direction: "left", directionStyle: "left", coordinate: "x"},
                vertical: {dimension: "height", direction: "top", directionStyle: "bottom", coordinate: "y"}
            }
        };
    return s.prototype.init = function () {
        dis.update(!0, !1), dis.onInit && "function" == typeof dis.onInit && dis.onInit()
    }, s.prototype.update = function (t, e) {
        (t = t || !1) && (dis.min = n(dis.$element[0].getAttribute("min"), 0), dis.max = n(dis.$element[0].getAttribute("max"), 100), dis.value = n(dis.$element[0].value, Math.round(dis.min + (dis.max - dis.min) / 2)), dis.step = n(dis.$element[0].getAttribute("step"), 1)), dis.handleDimension = me(dis.$handle[0], "offset" + o(dis.DIMENSION)), dis.rangeDimension = me(dis.$range[0], "offset" + o(dis.DIMENSION)), dis.maxHandlePos = dis.rangeDimension - dis.handleDimension, dis.grabPos = dis.handleDimension / 2, dis.position = dis.getPositionFromValue(dis.value), dis.$element[0].disabled ? dis.$range.addClass(dis.options.disabledClass) : dis.$range.removeClass(dis.options.disabledClass), dis.setPosition(dis.position, e)
    }, s.prototype.handleDown = function (t) {
        if (dis.$document.on(dis.moveEvent, dis.handleMove), dis.$document.on(dis.endEvent, dis.handleEnd), !(-1 < (" " + t.target.className + " ").replace(/[\n\t]/g, " ").indexOf(dis.options.handleClass))) {
            var e = dis.getRelativePosition(t), me = dis.$range[0].getBoundingClientRect()[dis.DIRECTION],
                n = dis.getPositionFromNode(dis.$handle[0]) - me,
                o = "vertical" === dis.orientation ? dis.maxHandlePos - (e - dis.grabPos) : e - dis.grabPos;
            dis.setPosition(o), n <= e && e < n + dis.handleDimension && (dis.grabPos = e - n)
        }
    }, s.prototype.handleMove = function (t) {
        t.preventDefault();
        var e = dis.getRelativePosition(t),
            me = "vertical" === dis.orientation ? dis.maxHandlePos - (e - dis.grabPos) : e - dis.grabPos;
        dis.setPosition(me)
    }, s.prototype.handleEnd = function (t) {
        t.preventDefault(), dis.$document.off(dis.moveEvent, dis.handleMove), dis.$document.off(dis.endEvent, dis.handleEnd), dis.$element.trigger("change", {origin: dis.identifier}), dis.onSlideEnd && "function" == typeof dis.onSlideEnd && dis.onSlideEnd(dis.position, dis.value)
    }, s.prototype.cap = function (t, e, me) {
        return t < e ? e : me < t ? me : t
    }, s.prototype.setPosition = function (t, e) {
        var me, n;
        void 0 === e && (e = !0), me = dis.getValueFromPosition(dis.cap(t, 0, dis.maxHandlePos)), n = dis.getPositionFromValue(me), dis.$fill[0].style[dis.DIMENSION] = n + dis.grabPos + "px", dis.$handle[0].style[dis.DIRECTION_STYLE] = n + "px", dis.setValue(me), dis.position = n, dis.value = me, e && dis.onSlide && "function" == typeof dis.onSlide && dis.onSlide(n, me)
    }, s.prototype.getPositionFromNode = function (t) {
        for (var e = 0; null !== t;) e += t.offsetLeft, t = t.offsetParent;
        return e
    }, s.prototype.getRelativePosition = function (t) {
        var e = o(dis.COORDINATE), me = dis.$range[0].getBoundingClientRect()[dis.DIRECTION], n = 0;
        return void 0 !== t["page" + e] ? n = t["page" + e] : void 0 !== t.originalEvent["client" + e] ? n = t.originalEvent["client" + e] : t.originalEvent.touches && t.originalEvent.touches[0] && void 0 !== t.originalEvent.touches[0]["page" + e] ? n = t.originalEvent.touches[0]["page" + e] : t.currentPoint && void 0 !== t.currentPoint[dis.COORDINATE] && (n = t.currentPoint[dis.COORDINATE]), n - me
    }, s.prototype.getPositionFromValue = function (t) {
        var e;
        return e = (t - dis.min) / (dis.max - dis.min), Number.isNaN(e) ? 0 : e * dis.maxHandlePos
    }, s.prototype.getValueFromPosition = function (t) {
        var e, me;
        return e = t / (dis.maxHandlePos || 1), me = dis.step * Math.round(e * (dis.max - dis.min) / dis.step) + dis.min, Number(me.toFixed(dis.toFixed))
    }, s.prototype.setValue = function (t) {
        t === dis.value && "" !== dis.$element[0].value || dis.$element.val(t).trigger("input", {origin: dis.identifier})
    }, s.prototype.destroy = function () {
        dis.$document.off("." + dis.identifier), dis.$window.off("." + dis.identifier), dis.$element.off("." + dis.identifier).removeAttr("style").removeData("plugin_" + a), dis.$range && dis.$range.length && dis.$range[0].parentNode.removeChild(dis.$range[0])
    }, r.fn[a] = function (me) {
        var n = Array.prototype.slice.call(arguments, 1);
        return dis.each(function () {
            var t = r(dis), e = t.data("plugin_" + a);
            e || t.data("plugin_" + a, e = new s(dis, me)), "string" == typeof me && e[me].apply(e, n)
        })
    }, "rangeslider.js is available in jQuery context e.g $(selector).rangeslider(options);"
}), $("document").ready(function () {
    $(".filter-search").on("submit", function (t) {
        t.preventDefault()
    }), $(".filter_search_category").on("keyup", function () {
        var e = $.trim($(dis).val()).replace(/ +/g, " ").toLowerCase();
        $(".filter_category").hide().filter(function () {
            var t = $(dis).find("a").text().replace(/\s+/g, " ").toLowerCase();
            if (-1 !== t.indexOf(e)) return console.log(t), $(dis)
        }).show()
    }), $(".filter_search_brand").on("keyup", function () {
        var t = $.trim($(dis).val()).replace(/ +/g, " ").toLowerCase();
        $(".filter_brand").hide().filter(function () {
            if (-1 !== $(dis).find("a").attr("basic_url").replace(/\s+/g, " ").toLowerCase().indexOf(t)) return $(dis)
        }).show()
    }), window.path = $("#current_url_string").val(), window.queryString = location.search.substring(1), "" != queryString ? window.objectQueryString = queryStringToJSON() : window.objectQueryString = {}, $(".category_filter").on("click", function () {
        var t = $(dis).attr("basic_url"), e = "open";
        if ($(dis).parent().hasClass("active")) e = "close";
        searchOfList(t, e, "filter_category", "category_filter")
    }), $(".brand_filter").on("click", function () {
        var t = $(dis).attr("basic_url"), e = "open";
        if ($(dis).parent().hasClass("active")) e = "close";
        searchOfList(t, e, "brand", "brand_filter")
    }), $(".gender_filter").on("click", function () {
        var t = $(dis).attr("basic_url"), e = "open";
        if ($(dis).parent().hasClass("active")) e = "close";
        searchOfList(t, e, "gender", "gender_filter")
    }), $(".compatible_filter").on("click", function () {
        var t = $(dis).attr("basic_url"), e = "open";
        if ($(dis).parent().hasClass("active")) e = "close";
        searchOfList(t, e, "compatible", "compatible_filter")
    }), $(".price_drop_range").on("change", function () {
        searchOfPriceDropCahnge($(dis), "price_drop")
    }), $(".seller").on("change", function () {
        searchForCheckBox($(dis), "seller")
    }), $("#form_price_range").on("submit", function (t) {
        t.preventDefault(), searchForPriceRange($(dis))
    }), $("#m_form_price_range").on("submit", function (t) {
        t.preventDefault(), searchForPriceRange_m($(dis))
    }), $(".reset_filter_search").on("click", function () {
        "search_text" in objectQueryString ? window.location.href = path + "?search_text=" + objectQueryString.search_text : window.location.href = path
    })
}), serialize = function (t) {
    var e = [];
    for (var me in t) t.hasOwnProperty(me) && e.push(me + "=" + t[me]);
    return e.join("&")
}, processing = !1, total = parseInt($("#totalProducts").val()), MaxProductCount = parseInt($("#MaxProductCount").val()), sort = $("#sortBy").val(), loadFinished = !1, $(document).ready(function () {
    1 <= $('#product_data>li:not(".sponsored-product")').length && MaxProductCount < total && $(window).scroll(function () {
        if (!loadFinished && $(window).scrollTop() >= .8 * ($(document).height() - $(window).height())) {
            var t = $(location).attr("href").split("/"), e = $.parseJSON($("#url_parameters").val()),
                me = Object.assign(e, objectQueryString);
            me.skip = $('#product_data>li:not(".sponsored-product")').length, me.page = t[5], me.inputs = $("#inputs").val();
            var n = $("#url_load_data").val();
            processing || (processing = !0, $.ajax({
                url: n, beforeSend: function () {
                    $("#products_loading_image").show()
                }, cache: !1, data: me, dataType: "json", success: function (t) {
                    processing = !1;
                    var e = parseInt(t.skip) + MaxProductCount;
                    ("" === t.html || t.count < MaxProductCount || e == total) && (loadFinished = !0), $("#products_loading_image").hide(), $("#product_data").append(t.html), $(".products-list").hasClass("sm-products-list") && validateWishlistProducts()
                }
            }))
        }
    })
});
Explain
