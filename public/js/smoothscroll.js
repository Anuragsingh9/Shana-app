! function(e) {
    e.extend({
        smoothScroll: function() {
            function e() {
                var e = !1;
                if (document.URL.indexOf("google.com/reader/view") > -1 && (e = !0), b.excluded) {
                    var t = b.excluded.split(/[,\n] ?/);
                    t.push("mail.google.com");
                    for (var o = t.length; o--;)
                        if (document.URL.indexOf(t[o]) > -1) {
                            g && g.disconnect(), u("mousewheel", n), e = !0, y = !0;
                            break
                        }
                }
                e && u("keydown", a), b.keyboardSupport && !e && c("keydown", a)
            }

            function t() {
                if (document.body) {
                    var t = document.body,
                        o = document.documentElement,
                        n = window.innerHeight,
                        a = t.scrollHeight;
                    if (D = document.compatMode.indexOf("CSS") >= 0 ? o : t, w = t, e(), S = !0, top != self) k = !0;
                    else if (a > n && (t.offsetHeight <= n || o.offsetHeight <= n)) {
                        var r = !1,
                            i = function() {
                                r || o.scrollHeight == document.height || (r = !0, setTimeout(function() {
                                    o.style.height = document.height + "px", r = !1
                                }, 500))
                            };
                        o.style.height = "auto", setTimeout(i, 10);
                        var l = {
                            attributes: !0,
                            childList: !0,
                            characterData: !1
                        };
                        if (g = new R(i), g.observe(t, l), D.offsetHeight <= n) {
                            var c = document.createElement("div");
                            c.style.clear = "both", t.appendChild(c)
                        }
                    }
                    if (document.URL.indexOf("mail.google.com") > -1) {
                        var u = document.createElement("style");
                        u.innerHTML = ".iu { visibility: hidden }", (document.getElementsByTagName("head")[0] || o).appendChild(u)
                    } else if (document.URL.indexOf("www.facebook.com") > -1) {
                        var s = document.getElementById("home_stream");
                        s && (s.style.webkitTransform = "translateZ(0)")
                    }
                    b.fixedBackground || y || (t.style.backgroundAttachment = "scroll", o.style.backgroundAttachment = "scroll")
                }
            }

            function o(e, t, o, n) {
                if (n || (n = 1e3), d(t, o), 1 != b.accelerationMax) {
                    var a = +new Date,
                        r = a - C;
                    if (r < b.accelerationDelta) {
                        var i = (1 + 30 / r) / 2;
                        i > 1 && (i = Math.min(i, b.accelerationMax), t *= i, o *= i)
                    }
                    C = +new Date
                }
                if (T.push({
                        x: t,
                        y: o,
                        lastX: 0 > t ? .99 : -.99,
                        lastY: 0 > o ? .99 : -.99,
                        start: +new Date
                    }), !L) {
                    var l = e === document.body,
                        c = function() {
                            for (var a = +new Date, r = 0, i = 0, u = 0; u < T.length; u++) {
                                var s = T[u],
                                    d = a - s.start,
                                    f = d >= b.animationTime,
                                    m = f ? 1 : d / b.animationTime;
                                b.pulseAlgorithm && (m = p(m));
                                var h = s.x * m - s.lastX >> 0,
                                    w = s.y * m - s.lastY >> 0;
                                r += h, i += w, s.lastX += h, s.lastY += w, f && (T.splice(u, 1), u--)
                            }
                            l ? window.scrollBy(r, i) : (r && (e.scrollLeft += r), i && (e.scrollTop += i)), t || o || (T = []), T.length ? O(c, e, n / b.frameRate + 1) : L = !1
                        };
                    O(c, e, 0), L = !0
                }
            }

            function n(e) {
                S || t();
                var n = e.target,
                    a = l(n);
                if (!a || e.defaultPrevented || s(w, "embed") || s(n, "embed") && /\.pdf/i.test(n.src)) return !0;
                var r = e.wheelDeltaX || 0,
                    i = e.wheelDeltaY || 0;
                return r || i || (i = e.wheelDelta || 0), !b.touchpadSupport && f(i) ? !0 : (Math.abs(r) > 1.2 && (r *= b.stepSize / 120), Math.abs(i) > 1.2 && (i *= b.stepSize / 120), o(a, -r, -i), void e.preventDefault())
            }

            function a(e) {
                var t = e.target,
                    n = e.ctrlKey || e.altKey || e.metaKey || e.shiftKey && e.keyCode !== M.spacebar;
                if (/input|textarea|select|embed/i.test(t.nodeName) || t.isContentEditable || e.defaultPrevented || n) return !0;
                if (s(t, "button") && e.keyCode === M.spacebar) return !0;
                var a, r = 0,
                    i = 0,
                    c = l(w),
                    u = c.clientHeight;
                switch (c == document.body && (u = window.innerHeight), e.keyCode) {
                    case M.up:
                        i = -b.arrowScroll;
                        break;
                    case M.down:
                        i = b.arrowScroll;
                        break;
                    case M.spacebar:
                        a = e.shiftKey ? 1 : -1, i = -a * u * .9;
                        break;
                    case M.pageup:
                        i = .9 * -u;
                        break;
                    case M.pagedown:
                        i = .9 * u;
                        break;
                    case M.home:
                        i = -c.scrollTop;
                        break;
                    case M.end:
                        var d = c.scrollHeight - c.scrollTop - u;
                        i = d > 0 ? d + 10 : 0;
                        break;
                    case M.left:
                        r = -b.arrowScroll;
                        break;
                    case M.right:
                        r = b.arrowScroll;
                        break;
                    default:
                        return !0
                }
                o(c, r, i), e.preventDefault()
            }

            function r(e) {
                w = e.target
            }

            function i(e, t) {
                for (var o = e.length; o--;) E[z(e[o])] = t;
                return t
            }

            function l(e) {
                var t = [],
                    o = D.scrollHeight;
                do {
                    var n = E[z(e)];
                    if (n) return i(t, n);
                    if (t.push(e), o === e.scrollHeight) {
                        if (!k || D.clientHeight + 10 < o) return i(t, document.body)
                    } else if (e.clientHeight + 10 < e.scrollHeight && (overflow = getComputedStyle(e, "").getPropertyValue("overflow-y"), "scroll" === overflow || "auto" === overflow)) return i(t, e)
                } while (e = e.parentNode)
            }

            function c(e, t, o) {
                window.addEventListener(e, t, o || !1)
            }

            function u(e, t, o) {
                window.removeEventListener(e, t, o || !1)
            }

            function s(e, t) {
                return (e.nodeName || "").toLowerCase() === t.toLowerCase()
            }

            function d(e, t) {
                e = e > 0 ? 1 : -1, t = t > 0 ? 1 : -1, (x.x !== e || x.y !== t) && (x.x = e, x.y = t, T = [], C = 0)
            }

            function f(e) {
                if (e) {
                    e = Math.abs(e), H.push(e), H.shift(), clearTimeout(N);
                    var t = H[0] == H[1] && H[1] == H[2],
                        o = m(H[0], 120) && m(H[1], 120) && m(H[2], 120);
                    return !(t || o)
                }
            }

            function m(e, t) {
                return Math.floor(e / t) == e / t
            }

            function h(e) {
                var t, o, n;
                return e *= b.pulseScale, 1 > e ? t = e - (1 - Math.exp(-e)) : (o = Math.exp(-1), e -= 1, n = 1 - Math.exp(-e), t = o + n * (1 - o)), t * b.pulseNormalize
            }

            function p(e) {
                return e >= 1 ? 1 : 0 >= e ? 0 : (1 == b.pulseNormalize && (b.pulseNormalize /= h(1)), h(e))
            }
            var w, g, v = {
                    frameRate: 40,
                    animationTime: 5e3,
                    stepSize: 80,
                    pulseAlgorithm: !0,
                    pulseScale: 20,
                    pulseNormalize: 1,
                    accelerationDelta: 40,
                    accelerationMax: 1,
                    keyboardSupport: !0,
                    arrowScroll: 50,
                    touchpadSupport: !0,
                    fixedBackground: !0,
                    excluded: ""
                },
                b = v,
                y = !1,
                k = !1,
                x = {
                    x: 0,
                    y: 0
                },
                S = !1,
                D = document.documentElement,
                H = [120, 120, 120],
                M = {
                    left: 37,
                    up: 38,
                    right: 39,
                    down: 40,
                    spacebar: 32,
                    pageup: 33,
                    pagedown: 34,
                    end: 35,
                    home: 36
                },
                T = [],
                L = !1,
                C = +new Date,
                E = {};
            setInterval(function() {
                E = {}
            }, 1e4);
            var N, z = function() {
                    var e = 0;
                    return function(t) {
                        return t.uniqueID || (t.uniqueID = e++)
                    }
                }(),
                O = function() {
                    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || function(e, t, o) {
                        window.setTimeout(e, o || 1e3 / 60)
                    }
                }(),
                R = window.MutationObserver || window.WebKitMutationObserver;
            c("mousedown", r), c("mousewheel", n), c("load", t)
        }
    })
}(jQuery);