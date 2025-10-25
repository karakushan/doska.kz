

(function(a) {
    a.fn.addBack = a.fn.addBack || a.fn.andSelf;
    a.fn.extend({
        actual: function(b, l) {
            if (!this[b]) {
                throw '$.actual => The jQuery method "' + b + '" you called does not exist';
            }
            var f = {
                absolute: false,
                clone: false,
                includeMargin: false
            };
            var i = a.extend(f, l);
            var e = this.eq(0);
            var h, j;
            if (i.clone === true) {
                h = function() {
                    var m = "position: absolute !important; top: -1000 !important; ";
                    e = e.clone().attr("style", m).appendTo("body")
                };
                j = function() {
                    e.remove()
                }
            } else {
                var g = [];
                var d = "";
                var c;
                h = function() {
                    c = e.parents().addBack().filter(":hidden");
                    d += "visibility: hidden !important; display: block !important; ";
                    if (i.absolute === true) {
                        d += "position: absolute !important; "
                    }
                    c.each(function() {
                        var m = a(this);
                        g.push(m.attr("style"));
                        m.attr("style", d)
                    })
                };
                j = function() {
                    c.each(function(m) {
                        var o = a(this);
                        var n = g[m];
                        if (n === undefined) {
                            o.removeAttr("style")
                        } else {
                            o.attr("style", n)
                        }
                    })
                }
            }
            h();
            var k = /(outer)/.test(b) ? e[b](i.includeMargin) : e[b]();
            j();
            return k
        }
    })
})(jQuery);

/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas, David Knight. Dual MIT/BSD license */

window.matchMedia || (window.matchMedia = function() {
    "use strict";

    // For browsers that support matchMedium api such as IE 9 and webkit
    var styleMedia = (window.styleMedia || window.media);

    // For those that don't support matchMedium
    if (!styleMedia) {
        var style       = document.createElement('style'),
            script      = document.getElementsByTagName('script')[0],
            info        = null;

        style.type  = 'text/css';
        style.id    = 'matchmediajs-test';

        script.parentNode.insertBefore(style, script);

        // 'style.currentStyle' is used by IE <= 8 and 'window.getComputedStyle' for all other browsers
        info = ('getComputedStyle' in window) && window.getComputedStyle(style, null) || style.currentStyle;

        styleMedia = {
            matchMedium: function(media) {
                var text = '@media ' + media + '{ #matchmediajs-test { width: 1px; } }';

                // 'style.styleSheet' is used by IE <= 8 and 'style.textContent' for all other browsers
                if (style.styleSheet) {
                    style.styleSheet.cssText = text;
                } else {
                    style.textContent = text;
                }

                // Test if media query is true or false
                return info.width === '1px';
            }
        };
    }

    return function(media) {
        return {
            matches: styleMedia.matchMedium(media || 'all'),
            media: media || 'all'
        };
    };
}());

/*! matchMedia() polyfill addListener/removeListener extension. Author & copyright (c) 2012: Scott Jehl. Dual MIT/BSD license */
(function(){
    // Bail out for browsers that have addListener support
    if (window.matchMedia && window.matchMedia('all').addListener) {
        return false;
    }

    var localMatchMedia = window.matchMedia,
        hasMediaQueries = localMatchMedia('only all').matches,
        isListening     = false,
        timeoutID       = 0,    // setTimeout for debouncing 'handleChange'
        queries         = [],   // Contains each 'mql' and associated 'listeners' if 'addListener' is used
        handleChange    = function(evt) {
            // Debounce
            clearTimeout(timeoutID);

            timeoutID = setTimeout(function() {
                for (var i = 0, il = queries.length; i < il; i++) {
                    var mql         = queries[i].mql,
                        listeners   = queries[i].listeners || [],
                        matches     = localMatchMedia(mql.media).matches;

                    // Update mql.matches value and call listeners
                    // Fire listeners only if transitioning to or from matched state
                    if (matches !== mql.matches) {
                        mql.matches = matches;

                        for (var j = 0, jl = listeners.length; j < jl; j++) {
                            listeners[j].call(window, mql);
                        }
                    }
                }
            }, 30);
        };

    window.matchMedia = function(media) {
        var mql         = localMatchMedia(media),
            listeners   = [],
            index       = 0;

        mql.addListener = function(listener) {
            // Changes would not occur to css media type so return now (Affects IE <= 8)
            if (!hasMediaQueries) {
                return; 
            }

            // Set up 'resize' listener for browsers that support CSS3 media queries (Not for IE <= 8)
            // There should only ever be 1 resize listener running for performance
            if (!isListening) {
                isListening = true;
                window.addEventListener('resize', handleChange, true);
            }

            // Push object only if it has not been pushed already
            if (index === 0) {
                index = queries.push({
                    mql         : mql,
                    listeners   : listeners
                });
            }

            listeners.push(listener);
        };

        mql.removeListener = function(listener) {
            for (var i = 0, il = listeners.length; i < il; i++){
                if (listeners[i] === listener){
                    listeners.splice(i, 1);
                }
            }
        };

        return mql;
    };
}());
;/*! waitForImages jQuery Plugin - v1.5.0 - 2013-07-20
 * https://github.com/alexanderdickson/waitForImages
 * Copyright (c) 2013 Alex Dickson; Licensed MIT */
(function($) {
    var o = 'waitForImages';
    $.waitForImages = {
        hasImageProperties: ['backgroundImage', 'listStyleImage', 'borderImage', 'borderCornerImage', 'cursor']
    };
    $.expr[':'].uncached = function(a) {
        if (!$(a).is('img[src!=""]')) {
            return false
        }
        var b = new Image();
        b.src = a.src;
        return !b.complete
    };
    $.fn.waitForImages = function(j, k, l) {
        var m = 0;
        var n = 0;
        if ($.isPlainObject(arguments[0])) {
            l = arguments[0].waitForAll;
            k = arguments[0].each;
            j = arguments[0].finished
        }
        j = j || $.noop;
        k = k || $.noop;
        l = !! l;
        if (!$.isFunction(j) || !$.isFunction(k)) {
            throw new TypeError('An invalid callback was supplied.');
        }
        return this.each(function() {
            var e = $(this);
            var f = [];
            var g = $.waitForImages.hasImageProperties || [];
            var h = /url\(\s*(['"]?)(.*?)\1\s*\)/g;
            if (l) {
                e.find('*').addBack().each(function() {
                    var d = $(this);
                    if (d.is('img:uncached')) {
                        f.push({
                            src: d.attr('src'),
                            element: d[0]
                        })
                    }
                    $.each(g, function(i, a) {
                        var b = d.css(a);
                        var c;
                        if (!b) {
                            return true
                        }
                        while (c = h.exec(b)) {
                            f.push({
                                src: c[2],
                                element: d[0]
                            })
                        }
                    })
                })
            } else {
                e.find('img:uncached').each(function() {
                    f.push({
                        src: this.src,
                        element: this
                    })
                })
            }
            m = f.length;
            n = 0;
            if (m === 0) {
                j.call(e[0])
            }
            $.each(f, function(i, b) {
                var c = new Image();
                $(c).on('load.' + o + ' error.' + o, function(a) {
                    n++;
                    k.call(b.element, n, m, a.type == 'load');
                    if (n == m) {
                        j.call(e[0]);
                        return false
                    }
                });
                c.src = b.src
            })
        })
    }
}(jQuery));
;/*

Infinite Scroll

*/

(function(o, i, k) {
    i.infinitescroll = function z(D, F, E) {
        this.element = i(E);
        if (!this._create(D, F)) {
            this.failed = true
        }
    };
    i.infinitescroll.defaults = {
        loading: {
            finished: k,
            finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
            img: "data:image/gif;base64,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",
            msg: null,
            msgText: "<em>Loading the next set of posts...</em>",
            selector: null,
            speed: "fast",
            start: k
        },
        state: {
            isDuringAjax: false,
            isInvalidPage: false,
            isDestroyed: false,
            isDone: false,
            isPaused: false,
            currPage: 1
        },
        debug: false,
        behavior: k,
        binder: i(o),
        nextSelector: "div.navigation a:first",
        navSelector: "div.navigation",
        contentSelector: null,
        extraScrollPx: 150,
        itemSelector: "div.post",
        animate: false,
        pathParse: k,
        dataType: "html",
        appendCallback: true,
        bufferPx: 40,
        errorCallback: function() {},
        infid: 0,
        pixelsFromNavToBottom: k,
        path: k,
        prefill: false
    };
    i.infinitescroll.prototype = {
        _binding: function g(F) {
            var D = this,
                E = D.options;
            E.v = "2.0b2.120520";
            if ( !! E.behavior && this["_binding_" + E.behavior] !== k) {
                this["_binding_" + E.behavior].call(this);
                return
            }
            if (F !== "bind" && F !== "unbind") {
                this._debug("Binding value  " + F + " not valid");
                return false
            }
            if (F === "unbind") {
                (this.options.binder).unbind("smartscroll.infscr." + D.options.infid)
            } else {
                (this.options.binder)[F]("smartscroll.infscr." + D.options.infid, function() {
                    D.scroll()
                })
            }
            this._debug("Binding", F)
        },
        _create: function t(F, J) {
            var G = i.extend(true, {}, i.infinitescroll.defaults, F);
            this.options = G;
            var I = i(o);
            var D = this;
            if (!D._validate(F)) {
                return false
            }
            var H = i(G.nextSelector).attr("href");
            if (!H) {
                this._debug("Navigation selector not found");
                return false
            }
            G.path = G.path || this._determinepath(H);
            G.contentSelector = G.contentSelector || this.element;
            G.loading.selector = G.loading.selector || G.contentSelector;
            G.loading.msg = G.loading.msg || i('<div id="infscr-loading"><img alt="Loading..." src="' + G.loading.img + '" /><div>' + G.loading.msgText + "</div></div>");
            (new Image()).src = G.loading.img;
            if (G.pixelsFromNavToBottom === k) {
                G.pixelsFromNavToBottom = i(document).height() - i(G.navSelector).offset().top
            }
            var E = this;
            G.loading.start = G.loading.start || function() {
                i(G.navSelector).hide();
                G.loading.msg.appendTo(G.loading.selector).show(G.loading.speed, i.proxy(function() {
                    this.beginAjax(G)
                }, E))
            };
            G.loading.finished = G.loading.finished || function() {
                G.loading.msg.fadeOut(G.loading.speed)
            };
            G.callback = function(K, M, L) {
                if ( !! G.behavior && K["_callback_" + G.behavior] !== k) {
                    K["_callback_" + G.behavior].call(i(G.contentSelector)[0], M, L)
                }
                if (J) {
                    J.call(i(G.contentSelector)[0], M, G, L)
                }
                if (G.prefill) {
                    I.bind("resize.infinite-scroll", K._prefill)
                }
            };
            if (F.debug) {
                if (Function.prototype.bind && (typeof console === "object" || typeof console === "function") && typeof console.log === "object") {
                    ["log", "info", "warn", "error", "assert", "dir", "clear", "profile", "profileEnd"].forEach(function(K) {
                        console[K] = this.call(console[K], console)
                    }, Function.prototype.bind)
                }
            }
            this._setup();
            if (G.prefill) {
                this._prefill()
            }
            return true
        },
        _prefill: function n() {
            var D = this;
            var G = i(document);
            var F = i(o);

            function E() {
                return (G.height() <= F.height())
            }
            this._prefill = function() {
                if (E()) {
                    D.scroll()
                }
                F.bind("resize.infinite-scroll", function() {
                    if (E()) {
                        F.unbind("resize.infinite-scroll");
                        D.scroll()
                    }
                })
            };
            this._prefill()
        },
        _debug: function q() {
            if (true !== this.options.debug) {
                return
            }
            if (typeof console !== "undefined" && typeof console.log === "function") {
                if ((Array.prototype.slice.call(arguments)).length === 1 && typeof Array.prototype.slice.call(arguments)[0] === "string") {
                    console.log((Array.prototype.slice.call(arguments)).toString())
                } else {
                    console.log(Array.prototype.slice.call(arguments))
                }
            } else {
                if (!Function.prototype.bind && typeof console !== "undefined" && typeof console.log === "object") {
                    Function.prototype.call.call(console.log, console, Array.prototype.slice.call(arguments))
                }
            }
        },
        _determinepath: function A(E) {
            var D = this.options;
            if ( !! D.behavior && this["_determinepath_" + D.behavior] !== k) {
                return this["_determinepath_" + D.behavior].call(this, E)
            }
            if ( !! D.pathParse) {
                this._debug("pathParse manual");
                return D.pathParse(E, this.options.state.currPage + 1)
            } else {
                if (E.match(/^(.*?)\b2\b(.*?$)/)) {
                    E = E.match(/^(.*?)\b2\b(.*?$)/).slice(1)
                } else {
                    if (E.match(/^(.*?)2(.*?$)/)) {
                        if (E.match(/^(.*?page=)2(\/.*|$)/)) {
                            E = E.match(/^(.*?page=)2(\/.*|$)/).slice(1);
                            return E
                        }
                        E = E.match(/^(.*?)2(.*?$)/).slice(1)
                    } else {
                        if (E.match(/^(.*?page=)1(\/.*|$)/)) {
                            E = E.match(/^(.*?page=)1(\/.*|$)/).slice(1);
                            return E
                        } else {
                            this._debug("Sorry, we couldn't parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.");
                            D.state.isInvalidPage = true
                        }
                    }
                }
            }
            this._debug("determinePath", E);
            return E
        },
        _error: function v(E) {
            var D = this.options;
            if ( !! D.behavior && this["_error_" + D.behavior] !== k) {
                this["_error_" + D.behavior].call(this, E);
                return
            }
            if (E !== "destroy" && E !== "end") {
                E = "unknown"
            }
            this._debug("Error", E);
            if (E === "end") {
                this._showdonemsg()
            }
            D.state.isDone = true;
            D.state.currPage = 1;
            D.state.isPaused = false;
            this._binding("unbind")
        },
        _loadcallback: function c(H, G, E) {
            var D = this.options,
                J = this.options.callback,
                L = (D.state.isDone) ? "done" : (!D.appendCallback) ? "no-append" : "append",
                K;
            if ( !! D.behavior && this["_loadcallback_" + D.behavior] !== k) {
                this["_loadcallback_" + D.behavior].call(this, H, G);
                return
            }
            switch (L) {
                case "done":
                    this._showdonemsg();
                    return false;
                case "no-append":
                    if (D.dataType === "html") {
                        G = "<div>" + G + "</div>";
                        G = i(G).find(D.itemSelector)
                    }
                    break;
                case "append":
                    var F = H.children();
                    if (F.length === 0) {
                        return this._error("end")
                    }
                    K = document.createDocumentFragment();
                    while (H[0].firstChild) {
                        K.appendChild(H[0].firstChild)
                    }
                    this._debug("contentSelector", i(D.contentSelector)[0]);
                    i(D.contentSelector)[0].appendChild(K);
                    G = F.get();
                    break
            }
            D.loading.finished.call(i(D.contentSelector)[0], D);
            if (D.animate) {
                var I = i(o).scrollTop() + i("#infscr-loading").height() + D.extraScrollPx + "px";
                i("html,body").animate({
                    scrollTop: I
                }, 800, function() {
                    D.state.isDuringAjax = false
                })
            }
            if (!D.animate) {
                D.state.isDuringAjax = false
            }
            J(this, G, E);
            if (D.prefill) {
                this._prefill()
            }
        },
        _nearbottom: function u() {
            var E = this.options,
                D = 0 + i(document).height() - (E.binder.scrollTop()) - i(o).height();
            if ( !! E.behavior && this["_nearbottom_" + E.behavior] !== k) {
                return this["_nearbottom_" + E.behavior].call(this)
            }
            this._debug("math:", D, E.pixelsFromNavToBottom);
            return (D - E.bufferPx < E.pixelsFromNavToBottom)
        },
        _pausing: function l(E) {
            var D = this.options;
            if ( !! D.behavior && this["_pausing_" + D.behavior] !== k) {
                this["_pausing_" + D.behavior].call(this, E);
                return
            }
            if (E !== "pause" && E !== "resume" && E !== null) {
                this._debug("Invalid argument. Toggling pause value instead")
            }
            E = (E && (E === "pause" || E === "resume")) ? E : "toggle";
            switch (E) {
                case "pause":
                    D.state.isPaused = true;
                    break;
                case "resume":
                    D.state.isPaused = false;
                    break;
                case "toggle":
                    D.state.isPaused = !D.state.isPaused;
                    break
            }
            this._debug("Paused", D.state.isPaused);
            return false
        },
        _setup: function r() {
            var D = this.options;
            if ( !! D.behavior && this["_setup_" + D.behavior] !== k) {
                this["_setup_" + D.behavior].call(this);
                return
            }
            this._binding("bind");
            return false
        },
        _showdonemsg: function a() {
            var D = this.options;
            if ( !! D.behavior && this["_showdonemsg_" + D.behavior] !== k) {
                this["_showdonemsg_" + D.behavior].call(this);
                return
            }
            D.loading.msg.find("img").hide().parent().find("div").html(D.loading.finishedMsg).animate({
                opacity: 1
            }, 2000, function() {
                i(this).parent().fadeOut(D.loading.speed)
            });
            D.errorCallback.call(i(D.contentSelector)[0], "done")
        },
        _validate: function w(E) {
            for (var D in E) {
                if (D.indexOf && D.indexOf("Selector") > -1 && i(E[D]).length === 0) {
                    this._debug("Your " + D + " found no elements.");
                    return false
                }
            }
            return true
        },
        bind: function p() {
            this._binding("bind")
        },
        destroy: function C() {
            this.options.state.isDestroyed = true;
            return this._error("destroy")
        },
        pause: function e() {
            this._pausing("pause")
        },
        resume: function h() {
            this._pausing("resume")
        },
        beginAjax: function B(G) {
            var E = this,
                I = G.path,
                F, D, K, J;
            G.state.currPage++;
            F = i(G.contentSelector).is("table") ? i("<tbody/>") : i("<div/>");
            D = (typeof I === "function") ? I(G.state.currPage) : I.join(G.state.currPage);
            E._debug("heading into ajax", D);
            K = (G.dataType === "html" || G.dataType === "json") ? G.dataType : "html+callback";
            if (G.appendCallback && G.dataType === "html") {
                K += "+callback"
            }
            switch (K) {
                case "html+callback":
                    E._debug("Using HTML via .load() method");
                    F.load(D + " " + G.itemSelector, k, function H(L) {
                        E._loadcallback(F, L, D)
                    });
                    break;
                case "html":
                    E._debug("Using " + (K.toUpperCase()) + " via $.ajax() method");
                    i.ajax({
                        url: D,
                        dataType: G.dataType,
                        complete: function H(L, M) {
                            J = (typeof(L.isResolved) !== "undefined") ? (L.isResolved()) : (M === "success" || M === "notmodified");
                            if (J) {
                                E._loadcallback(F, L.responseText, D)
                            } else {
                                E._error("end")
                            }
                        }
                    });
                    break;
                case "json":
                    E._debug("Using " + (K.toUpperCase()) + " via $.ajax() method");
                    i.ajax({
                        dataType: "json",
                        type: "GET",
                        url: D,
                        success: function(N, O, M) {
                            J = (typeof(M.isResolved) !== "undefined") ? (M.isResolved()) : (O === "success" || O === "notmodified");
                            if (G.appendCallback) {
                                if (G.template !== k) {
                                    var L = G.template(N);
                                    F.append(L);
                                    if (J) {
                                        E._loadcallback(F, L)
                                    } else {
                                        E._error("end")
                                    }
                                } else {
                                    E._debug("template must be defined.");
                                    E._error("end")
                                }
                            } else {
                                if (J) {
                                    E._loadcallback(F, N, D)
                                } else {
                                    E._error("end")
                                }
                            }
                        },
                        error: function() {
                            E._debug("JSON ajax request failed.");
                            E._error("end")
                        }
                    });
                    break
            }
        },
        retrieve: function b(F) {
            F = F || null;
            var D = this,
                E = D.options;
            if ( !! E.behavior && this["retrieve_" + E.behavior] !== k) {
                this["retrieve_" + E.behavior].call(this, F);
                return
            }
            if (E.state.isDestroyed) {
                this._debug("Instance is destroyed");
                return false
            }
            E.state.isDuringAjax = true;
            E.loading.start.call(i(E.contentSelector)[0], E)
        },
        scroll: function f() {
            var D = this.options,
                E = D.state;
            if ( !! D.behavior && this["scroll_" + D.behavior] !== k) {
                this["scroll_" + D.behavior].call(this);
                return
            }
            if (E.isDuringAjax || E.isInvalidPage || E.isDone || E.isDestroyed || E.isPaused) {
                return
            }
            if (!this._nearbottom()) {
                return
            }
            this.retrieve()
        },
        toggle: function y() {
            this._pausing()
        },
        unbind: function m() {
            this._binding("unbind")
        },
        update: function j(D) {
            if (i.isPlainObject(D)) {
                this.options = i.extend(true, this.options, D)
            }
        }
    };
    i.fn.infinitescroll = function d(F, G) {
        var E = typeof F;
        switch (E) {
            case "string":
                var D = Array.prototype.slice.call(arguments, 1);
                this.each(function() {
                    var H = i.data(this, "infinitescroll");
                    if (!H) {
                        return false
                    }
                    if (!i.isFunction(H[F]) || F.charAt(0) === "_") {
                        return false
                    }
                    H[F].apply(H, D)
                });
                break;
            case "object":
                this.each(function() {
                    var H = i.data(this, "infinitescroll");
                    if (H) {
                        H.update(F)
                    } else {
                        H = new i.infinitescroll(F, G, this);
                        if (!H.failed) {
                            i.data(this, "infinitescroll", H)
                        }
                    }
                });
                break
        }
        return this
    };
    var x = i.event,
        s;
    x.special.smartscroll = {
        setup: function() {
            i(this).bind("scroll", x.special.smartscroll.handler)
        },
        teardown: function() {
            i(this).unbind("scroll", x.special.smartscroll.handler)
        },
        handler: function(G, D) {
            var F = this,
                E = arguments;
            G.type = "smartscroll";
            if (s) {
                clearTimeout(s)
            }
            s = setTimeout(function() {
                i.event.handle.apply(F, E)
            }, D === "execAsap" ? 0 : 100)
        }
    };
    i.fn.smartscroll = function(D) {
        return D ? this.bind("smartscroll", D) : this.trigger("smartscroll", ["execAsap"])
    }
})(window, jQuery);

;

/*
 * DC Mega Menu - jQuery mega menu
 * Copyright (c) 2011 Design Chemical
 *
 */
(function($) {

    //define the defaults for the plugin and how to call it 
    $.fn.dcMegaMenu = function(options) {
        //set default options  
        var defaults = {
            classParent: 'dc-mega',
            classContainer: 'sub-container',
            classSubParent: 'mega-hdr',
            classSubLink: 'mega-hdr',
            classWidget: 'dc-extra',
            rowItems: 6,
            speed: 'fast',
            effect: 'fade',
            event: 'hover',
            fullWidth: false,
            onLoad: function() {},
            beforeOpen: function() {},
            beforeClose: function() {}
        };


        //call in the default otions
        var mega_div_width = pacz_js.pacz_grid_width - 30;
        var options = $.extend(defaults, options);
        var $dcMegaMenuObj = this;

        //act upon the element that is passed into the design    
        return $dcMegaMenuObj.each(function(options) {

            var clSubParent = defaults.classSubParent;
            var clSubLink = defaults.classSubLink;
            var clParent = defaults.classParent;
            var clContainer = defaults.classContainer;
            var clWidget = defaults.classWidget;
            //console.log(jQuery(this).parents('.pacz-header-nav-container').width());

            megaSetup();

            function megaOver() {
                var subNav = $('.sub', this);
                $(this).addClass('mega-hover');
                if (defaults.effect === 'fade') {
                    $(subNav).fadeIn(defaults.speed);
                }
                if (defaults.effect === 'slide') {
                    $(subNav).show(defaults.speed);
                }
                // beforeOpen callback;
                defaults.beforeOpen.call(this);
            }

            function megaAction(obj) {
                var subNav = $('.sub', obj);
                $(obj).addClass('mega-hover');
                if (defaults.effect === 'fade') {
                    $(subNav).fadeIn(defaults.speed);
                }
                if (defaults.effect === 'slide') {
                    $(subNav).show(defaults.speed);
                }
                // beforeOpen callback;
                defaults.beforeOpen.call(this);
            }

            function megaOut() {
                var subNav = $('.sub', this);
                $(this).removeClass('mega-hover');
                $(subNav).hide();
                // beforeClose callback;
                defaults.beforeClose.call(this);
            }

            function megaActionClose(obj) {
                var subNav = $('.sub', obj);
                $(obj).removeClass('mega-hover');
                $(subNav).hide();
                // beforeClose callback;
                defaults.beforeClose.call(this);
            }

            function megaReset() {
                $('li', $dcMegaMenuObj).removeClass('mega-hover');
                $('.sub', $dcMegaMenuObj).hide();
            }

            function megaSetup() {
                //$arrow = '<span class="dc-mega-icon"></span>';
                var clParentLi = clParent + '-li';
                var menuWidth = $dcMegaMenuObj.outerWidth();
                $('> li', $dcMegaMenuObj).each(function() {
                    //Set Width of sub
                    var $mainSub = $('> ul', this);
                    var $primaryLink = $('> a', this);
                    if ($mainSub.length) {
                        //$primaryLink.addClass(clParent).append($arrow);
                        $mainSub.addClass('sub').wrap('<div class="' + clContainer + '" />');

                        var pos = $(this).position();
                        pl = pos.left;

                        // checks whether its a mega menu. editd by CLASSIADSPRO   
                        if ($('ul.pacz_mega_menu', $mainSub).length) {
                            $(this).addClass(clParentLi);
                            $('.' + clContainer, this).addClass('mega');
                            $('> li', $mainSub).each(function() {
                                if (!$(this).hasClass(clWidget)) {
                                    $(this).addClass('mega-unit');
                                    if ($('> ul', this).length) {
                                        $(this).addClass(clSubParent);
                                        $('> a', this).addClass(clSubParent + '-a');
                                    } else {
                                        $(this).addClass(clSubLink);
                                        $('> a', this).addClass(clSubLink + '-a');
                                    }
                                }
                            });

                            // Create Rows
                            var hdrs = $('.mega-unit', this);
                            rowSize = parseInt(defaults.rowItems);
                            for (var i = 0; i < hdrs.length; i += rowSize) {
                                hdrs.slice(i, i + rowSize).wrapAll('<div class="row" />');
                            }

                            // Get Sub Dimensions & Set Row Height
                            $mainSub.show();

                            // Get Position of Parent Item
                            var pw = $(this).width();
                            var pr = pl + pw;

                            // Check available right margin
                            var mr = menuWidth - pr;

                            // // Calc Width of Sub Menu
                            var subw = $mainSub.outerWidth();
                            var totw = $mainSub.parent('.' + clContainer).outerWidth();
                            var cpad = totw - subw;

                            if (defaults.fullWidth === true) {
                                var fw = menuWidth - cpad;
                                $mainSub.parent('.' + clContainer).css({
                                    width: mega_div_width
                                });
                                $dcMegaMenuObj.addClass('full-width');
                            }
                            var iw = $('.mega-unit', $mainSub).outerWidth(true);
                            var rowItems = $('.row:eq(0) .mega-unit', $mainSub).length;
                            var inneriw = iw * rowItems;
                            var totiw = inneriw + cpad;

                            // Set mega header height
                            $('.row', this).each(function() {
                                $('.mega-unit:last', this).addClass('last');
                                var maxValue = undefined;
                                $('.mega-unit > a', this).each(function() {
                                    var val = parseInt($(this).height());
                                    if (maxValue === undefined || maxValue < val) {
                                        maxValue = val;
                                    }
                                });
                                $('.mega-unit > a', this).css('height', maxValue + 'px');
                                $(this).css('width', inneriw + 'px');
                            });

                            // Calc Required Left Margin incl additional required for right align

                           if(defaults.fullWidth == true){
                                params = {left: 0};
                            } else {
                                
                                var ml = mr < ml ? ml + ml - mr : (totiw - pw)/2;
                                var subLeft = pl - ml;

                                // If Left Position Is Negative Set To Left Margin
                                var params = {left: pl+'px', marginLeft: -ml+'px'};
                                
                                if(subLeft < 0){
                                    params = {left: 0};
                                }else if(mr < ml){
                                    params = {right: 0};
                                }
                            }
                            $('.'+clContainer,this).css(params);
                            
                            // Calculate Row Height
                            $('.row',$mainSub).each(function(){
                                var rh = $(this).height();
                                $('.mega-unit',this).css({height: rh+'px'});
                                $(this).parent('.row').css({height: rh+'px'});
                            });
                            $mainSub.hide();
                    
                        } else {

                            //var classiadsproSubW = $mainSub.outerWidth();
                            //mega_div_width;   

                            //classiadsproOffsetOut = menuWidth - classiadsproSubW;


                            //console.log(pl + ' ' + classiadsproSubW);

                            $('.'+clContainer,this).addClass('non-mega').css('left',pl+'px');
                        }
                        // CLASSIADSPRO edition
                        if (!$('ul', $mainSub).hasClass('pacz_mega_menu')) {
                            $('.' + clContainer, this).addClass('pacz-nested-sub');
                            //console.log($('.pacz-nested-sub > ul',this).width());
                            $pacz_nested_ul = $('.pacz-nested-sub > ul', this);
                            $pacz_nested_width = $pacz_nested_ul.width();

                            $pacz_nested_ul.find('ul').css('left', $pacz_nested_width + 'px');
                            $pacz_nested_ul.find('li').each(function() {
                                var $nested_sub = $('> ul', this);
                                if ($nested_sub.length) {
                                    jQuery(this).append('<i class="pacz-mega-icon pacz-theme-icon-next-small"></i>');
                                }
                                jQuery(this).hover(function() {
                                    jQuery(this).find('> ul').stop(true, true).delay(200).fadeIn(100);
                                }, function() {
                                    jQuery(this).find('> ul').stop(true, true).delay(200).fadeOut(100);
                                });
                            });

                        }
                    }
                });
                // Set position of mega dropdown to bottom of main menu
                var menuHeight = $('> li > a', $dcMegaMenuObj).outerHeight(true);
                $('.' + clContainer, $dcMegaMenuObj).css({
                    top: menuHeight + 'px'
                }).css('z-index', '1000');

                if (defaults.event == 'hover') {
                    // HoverIntent Configuration
                    var config = {
                        sensitivity: 1,
                        interval: 30,
                        over: megaOver,
                        timeout: 100,
                        out: megaOut
                    };
                    $('li', $dcMegaMenuObj).hoverIntent(config);
                }

                if (defaults.event == 'click') {

                    $('body').mouseup(function(e) {
                        if (!$(e.target).parents('.mega-hover').length) {
                            megaReset();
                        }
                    });

                    $('> li > a.' + clParent, $dcMegaMenuObj).click(function(e) {
                        var $parentLi = $(this).parent();
                        if ($parentLi.hasClass('mega-hover')) {
                            megaActionClose($parentLi);
                        } else {
                            megaAction($parentLi);
                        }
                        e.preventDefault();
                    });
                }

                // onLoad callback;
                defaults.onLoad.call(this);
            }
        });
    };
})(jQuery);

;


/**
 * @depends jquery
 * @name jquery.scrollto
 * @package jquery-scrollto {@link http://balupton.com/projects/jquery-scrollto}
 */

/**
 * jQuery Aliaser
 */
(function(window, undefined) {
    // Prepare
    var jQuery, $, ScrollTo;
    jQuery = $ = window.jQuery;

    /**
     * jQuery ScrollTo (balupton edition)
     * @version 1.2.0
     * @date July 9, 2012
     * @since 0.1.0, August 27, 2010
     * @package jquery-scrollto {@link http://balupton.com/projects/jquery-scrollto}
     * @author Benjamin "balupton" Lupton {@link http://balupton.com}
     * @copyright (c) 2010 Benjamin Arthur Lupton {@link http://balupton.com}
     * @license MIT License {@link http://creativecommons.org/licenses/MIT/}
     */
    ScrollTo = $.ScrollTo = $.ScrollTo || {
        /**
         * The Default Configuration
         */
        config: {
            duration: 400,
            easing: 'swing',
            callback: undefined,
            durationMode: 'each',
            offsetTop: 0,
            offsetLeft: 0
        },

        /**
         * Configure ScrollTo
         */
        configure: function(options) {
            // Apply Options to Config
            $.extend(ScrollTo.config, options || {});

            // Chain
            return this;
        },

        /**
         * Perform the Scroll Animation for the Collections
         * We use $inline here, so we can determine the actual offset start for each overflow:scroll item
         * Each collection is for each overflow:scroll item
         */
        scroll: function(collections, config) {
            // Prepare
            var collection, $container, container, $target, $inline, position,
                containerScrollTop, containerScrollLeft,
                containerScrollTopEnd, containerScrollLeftEnd,
                startOffsetTop, targetOffsetTop, targetOffsetTopAdjusted,
                startOffsetLeft, targetOffsetLeft, targetOffsetLeftAdjusted,
                scrollOptions,
                callback;

            // Determine the Scroll
            collection = collections.pop();
            $container = collection.$container;
            container = $container.get(0);
            $target = collection.$target;

            // Prepare the Inline Element of the Container
            $inline = $('<span/>').css({
                'position': 'absolute',
                'top': '0px',
                'left': '0px'
            });
            position = $container.css('position');

            // Insert the Inline Element of the Container
            $container.css('position', 'relative');
            $inline.appendTo($container);

            // Determine the top offset
            startOffsetTop = $inline.offset().top;
            targetOffsetTop = $target.offset().top;
            targetOffsetTopAdjusted = targetOffsetTop - startOffsetTop - parseInt(config.offsetTop, 10);

            // Determine the left offset
            startOffsetLeft = $inline.offset().left;
            targetOffsetLeft = $target.offset().left;
            targetOffsetLeftAdjusted = targetOffsetLeft - startOffsetLeft - parseInt(config.offsetLeft, 10);

            // Determine current scroll positions
            containerScrollTop = container.scrollTop;
            containerScrollLeft = container.scrollLeft;

            // Reset the Inline Element of the Container
            $inline.remove();
            $container.css('position', position);

            // Prepare the scroll options
            scrollOptions = {};

            // Prepare the callback
            callback = function(event) {
                // Check
                if (collections.length === 0) {
                    // Callback
                    if (typeof config.callback === 'function') {
                        config.callback.apply(this, [event]);
                    }
                } else {
                    // Recurse
                    ScrollTo.scroll(collections, config);
                }
                // Return true
                return true;
            };

            // Handle if we only want to scroll if we are outside the viewport
            if (config.onlyIfOutside) {
                // Determine current scroll positions
                containerScrollTopEnd = containerScrollTop + $container.height();
                containerScrollLeftEnd = containerScrollLeft + $container.width();

                // Check if we are in the range of the visible area of the container
                if (containerScrollTop < targetOffsetTopAdjusted && targetOffsetTopAdjusted < containerScrollTopEnd) {
                    targetOffsetTopAdjusted = containerScrollTop;
                }
                if (containerScrollLeft < targetOffsetLeftAdjusted && targetOffsetLeftAdjusted < containerScrollLeftEnd) {
                    targetOffsetLeftAdjusted = containerScrollLeft;
                }
            }

            // Determine the scroll options
            if (targetOffsetTopAdjusted !== containerScrollTop) {
                scrollOptions.scrollTop = targetOffsetTopAdjusted;
            }
            if (targetOffsetLeftAdjusted !== containerScrollLeft) {
                scrollOptions.scrollLeft = targetOffsetLeftAdjusted;
            }

            // Perform the scroll
            if ($.browser.safari && container === document.body) {
                window.scrollTo(scrollOptions.scrollLeft, scrollOptions.scrollTop);
                callback();
            } else if (scrollOptions.scrollTop || scrollOptions.scrollLeft) {
                $container.animate(scrollOptions, config.duration, config.easing, callback);
            } else {
                callback();
            }

            // Return true
            return true;
        },

        /**
         * ScrollTo the Element using the Options
         */
        fn: function(options) {
            // Prepare
            var collections, config, $container, container;
            collections = [];

            // Prepare
            var $target = $(this);
            if ($target.length === 0) {
                // Chain
                return this;
            }

            // Handle Options
            config = $.extend({}, ScrollTo.config, options);

            // Fetch
            $container = $target.parent();
            container = $container.get(0);

            // Cycle through the containers
            while (($container.length === 1) && (container !== document.body) && (container !== document)) {
                // Check Container for scroll differences
                var scrollTop, scrollLeft;
                scrollTop = $container.css('overflow-y') !== 'visible' && container.scrollHeight !== container.clientHeight;
                scrollLeft = $container.css('overflow-x') !== 'visible' && container.scrollWidth !== container.clientWidth;
                if (scrollTop || scrollLeft) {
                    // Push the Collection
                    collections.push({
                        '$container': $container,
                        '$target': $target
                    });
                    // Update the Target
                    $target = $container;
                }
                // Update the Container
                $container = $container.parent();
                container = $container.get(0);
            }

            // Add the final collection
            collections.push({
                '$container': $(
                    ($.browser.msie || $.browser.mozilla) ? 'html' : 'body'
                ),
                '$target': $target
            });

            // Adjust the Config
            if (config.durationMode === 'all') {
                config.duration /= collections.length;
            }

            // Handle
            ScrollTo.scroll(collections, config);

            // Chain
            return this;
        }
    };

    // Apply our jQuery Prototype Function
    $.fn.ScrollTo = $.ScrollTo.fn;

})(window);
;
/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function (factory) {
  if (typeof define == 'function' && define.amd) {
    // AMD
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node, CommonJS
    module.exports = factory(require('jquery'));
  } else {
      // Browser globals
    factory(jQuery);
  }
}(function ($) {

  var inviewObjects = [], viewportSize, viewportOffset,
      d = document, w = window, documentElement = d.documentElement, timer;

  $.event.special.inview = {
    add: function(data) {
      inviewObjects.push({ data: data, $element: $(this), element: this });
      // Use setInterval in order to also make sure this captures elements within
      // "overflow:scroll" elements or elements that appeared in the dom tree due to
      // dom manipulation and reflow
      // old: $(window).scroll(checkInView);
      //
      // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
      // intervals while the user scrolls. Therefore the inview event might fire a bit late there
      //
      // Don't waste cycles with an interval until we get at least one element that
      // has bound to the inview event.
      if (!timer && inviewObjects.length) {
         timer = setInterval(checkInView, 250);
      }
    },

    remove: function(data) {
      for (var i=0; i<inviewObjects.length; i++) {
        var inviewObject = inviewObjects[i];
        if (inviewObject.element === this && inviewObject.data.guid === data.guid) {
          inviewObjects.splice(i, 1);
          break;
        }
      }

      // Clear interval when we no longer have any elements listening
      if (!inviewObjects.length) {
         clearInterval(timer);
         timer = null;
      }
    }
  };

  function getViewportSize() {
    var mode, domObject, size = { height: w.innerHeight, width: w.innerWidth };

    // if this is correct then return it. iPad has compat Mode, so will
    // go into check clientHeight/clientWidth (which has the wrong value).
    if (!size.height) {
      mode = d.compatMode;
      if (mode || !$.support.boxModel) { // IE, Gecko
        domObject = mode === 'CSS1Compat' ?
          documentElement : // Standards
          d.body; // Quirks
        size = {
          height: domObject.clientHeight,
          width:  domObject.clientWidth
        };
      }
    }

    return size;
  }

  function getViewportOffset() {
    return {
      top:  w.pageYOffset || documentElement.scrollTop   || d.body.scrollTop,
      left: w.pageXOffset || documentElement.scrollLeft  || d.body.scrollLeft
    };
  }

  function checkInView() {
    if (!inviewObjects.length) {
      return;
    }

    var i = 0, $elements = $.map(inviewObjects, function(inviewObject) {
      var selector  = inviewObject.data.selector,
          $element  = inviewObject.$element;
      return selector ? $element.find(selector) : $element;
    });

    viewportSize   = viewportSize   || getViewportSize();
    viewportOffset = viewportOffset || getViewportOffset();

    for (; i<inviewObjects.length; i++) {
      // Ignore elements that are not in the DOM tree
      if (!$.contains(documentElement, $elements[i][0])) {
        continue;
      }

      var $element      = $($elements[i]),
          elementSize   = { height: $element[0].offsetHeight, width: $element[0].offsetWidth },
          elementOffset = $element.offset(),
          inView        = $element.data('inview');

      // Don't ask me why because I haven't figured out yet:
      // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
      // Even though it sounds weird:
      // It seems that the execution of this function is interferred by the onresize/onscroll event
      // where viewportOffset and viewportSize are unset
      if (!viewportOffset || !viewportSize) {
        return;
      }

      if (elementOffset.top + elementSize.height > viewportOffset.top &&
          elementOffset.top < viewportOffset.top + viewportSize.height &&
          elementOffset.left + elementSize.width > viewportOffset.left &&
          elementOffset.left < viewportOffset.left + viewportSize.width) {
        if (!inView) {
          $element.data('inview', true).trigger('inview', [true]);
        }
      } else if (inView) {
        $element.data('inview', false).trigger('inview', [false]);
      }
    }
  }

  $(w).on("scroll resize scrollstop", function() {
    viewportSize = viewportOffset = null;
  });

  // IE < 9 scrolls to focused elements without firing the "scroll" event
  if (!documentElement.addEventListener && documentElement.attachEvent) {
    documentElement.attachEvent("onfocusin", function() {
      viewportOffset = null;
    });
  }
}));

;/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 *
 * Requires: 1.2.2+
 */

(function($) {

    var types = ['DOMMouseScroll', 'mousewheel'];

    if ($.event.fixHooks) {
        for (var i = types.length; i;) {
            $.event.fixHooks[types[--i]] = $.event.mouseHooks;
        }
    }

    $.event.special.mousewheel = {
        setup: function() {
            if (this.addEventListener) {
                for (var i = types.length; i;) {
                    this.addEventListener(types[--i], handler, false);
                }
            } else {
                this.onmousewheel = handler;
            }
        },

        teardown: function() {
            if (this.removeEventListener) {
                for (var i = types.length; i;) {
                    this.removeEventListener(types[--i], handler, false);
                }
            } else {
                this.onmousewheel = null;
            }
        }
    };

    $.fn.extend({
        mousewheel: function(fn) {
            return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
        },

        unmousewheel: function(fn) {
            return this.unbind("mousewheel", fn);
        }
    });


    function handler(event) {
        var orgEvent = event || window.event,
            args = [].slice.call(arguments, 1),
            delta = 0,
            returnValue = true,
            deltaX = 0,
            deltaY = 0;
        event = $.event.fix(orgEvent);
        event.type = "mousewheel";

        // Old school scrollwheel delta
        if (orgEvent.wheelDelta) {
            delta = orgEvent.wheelDelta / 120;
        }
        if (orgEvent.detail) {
            delta = -orgEvent.detail / 3;
        }

        // New school multidimensional scroll (touchpads) deltas
        deltaY = delta;

        // Gecko
        if (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
            deltaY = 0;
            deltaX = -1 * delta;
        }

        // Webkit
        if (orgEvent.wheelDeltaY !== undefined) {
            deltaY = orgEvent.wheelDeltaY / 120;
        }
        if (orgEvent.wheelDeltaX !== undefined) {
            deltaX = -1 * orgEvent.wheelDeltaX / 120;
        }

        // Add event and delta to the front of the arguments
        args.unshift(event, delta, deltaX, deltaY);

        return ($.event.dispatch || $.event.handle).apply(this, args);
    }

})(jQuery);;// Generated by CoffeeScript 1.4.0
/*
Easy pie chart is a jquery plugin to display simple animated pie charts for only one value

Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.

Built on top of the jQuery library (http://jquery.com)

@source: http://github.com/rendro/easy-pie-chart/
@autor: Robert Fleischmann
@version: 1.1.0

Inspired by: http://dribbble.com/shots/631074-Simple-Pie-Charts-II?list=popular&offset=210
Thanks to Philip Thrasher for the jquery plugin boilerplate for coffee script
*/

(function($) {
    $.easyPieChart = function(el, options) {
        var addScaleLine, animateLine, drawLine, easeInOutQuad, rAF, renderBackground, renderScale, renderTrack,
            _this = this;
        this.el = el;
        this.$el = $(el);
        this.$el.data("easyPieChart", this);
        this.init = function() {
            var percent, scaleBy;
            _this.options = $.extend({}, $.easyPieChart.defaultOptions, options);
            percent = parseInt(_this.$el.data('percent'), 10);
            _this.percentage = 0;
            _this.canvas = $("<canvas width='" + _this.options.size + "' height='" + _this.options.size + "'></canvas>").get(0);
            _this.$el.append(_this.canvas);
            if (typeof G_vmlCanvasManager !== "undefined" && G_vmlCanvasManager !== null) {
                G_vmlCanvasManager.initElement(_this.canvas);
            }
            _this.ctx = _this.canvas.getContext('2d');
            if (window.devicePixelRatio > 1) {
                scaleBy = window.devicePixelRatio;
                $(_this.canvas).css({
                    width: _this.options.size,
                    height: _this.options.size
                });
                _this.canvas.width *= scaleBy;
                _this.canvas.height *= scaleBy;
                _this.ctx.scale(scaleBy, scaleBy);
            }
            _this.ctx.translate(_this.options.size / 2, _this.options.size / 2);
            _this.$el.addClass('easyPieChart');
            _this.$el.css({
                width: _this.options.size,
                height: _this.options.size,
                lineHeight: "" + _this.options.size + "px"
            });
            _this.update(percent);
            return _this;
        };
        this.update = function(percent) {
            percent = parseFloat(percent) || 0;
            if (_this.options.animate === false) {
                drawLine(percent);
            } else {
                animateLine(_this.percentage, percent);
            }
            return _this;
        };
        renderScale = function() {
            var i, _i, _results;
            _this.ctx.fillStyle = _this.options.scaleColor;
            _this.ctx.lineWidth = 1;
            _results = [];
            for (i = _i = 0; _i <= 24; i = ++_i) {
                _results.push(addScaleLine(i));
            }
            return _results;
        };
        addScaleLine = function(i) {
            var offset;
            offset = i % 6 === 0 ? 0 : _this.options.size * 0.017;
            _this.ctx.save();
            _this.ctx.rotate(i * Math.PI / 12);
            _this.ctx.fillRect(_this.options.size / 2 - offset, 0, -_this.options.size * 0.05 + offset, 1);
            _this.ctx.restore();
        };
        renderTrack = function() {
            var offset;
            offset = _this.options.size / 2 - _this.options.lineWidth / 2;
            if (_this.options.scaleColor !== false) {
                offset -= _this.options.size * 0.08;
            }
            _this.ctx.beginPath();
            _this.ctx.arc(0, 0, offset, 0, Math.PI * 2, true);
            _this.ctx.closePath();
            _this.ctx.strokeStyle = _this.options.trackColor;
            _this.ctx.lineWidth = _this.options.lineWidth;
            _this.ctx.stroke();
        };
        renderBackground = function() {
            if (_this.options.scaleColor !== false) {
                renderScale();
            }
            if (_this.options.trackColor !== false) {
                renderTrack();
            }
        };
        drawLine = function(percent) {
            var offset;
            renderBackground();
            _this.ctx.strokeStyle = $.isFunction(_this.options.barColor) ? _this.options.barColor(percent) : _this.options.barColor;
            _this.ctx.lineCap = _this.options.lineCap;
            _this.ctx.lineWidth = _this.options.lineWidth;
            offset = _this.options.size / 2 - _this.options.lineWidth / 2;
            if (_this.options.scaleColor !== false) {
                offset -= _this.options.size * 0.08;
            }
            _this.ctx.save();
            _this.ctx.rotate(-Math.PI / 2);
            _this.ctx.beginPath();
            _this.ctx.arc(0, 0, offset, 0, Math.PI * 2 * percent / 100, false);
            _this.ctx.stroke();
            _this.ctx.restore();
        };
        rAF = (function() {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) {
                return window.setTimeout(callback, 1000 / 60);
            };
        })();
        animateLine = function(from, to) {
            var anim, startTime;
            _this.options.onStart.call(_this);
            _this.percentage = to;
            startTime = Date.now();
            anim = function() {
                var currentValue, process;
                process = Date.now() - startTime;
                if (process < _this.options.animate) {
                    rAF(anim);
                }
                _this.ctx.clearRect(-_this.options.size / 2, -_this.options.size / 2, _this.options.size, _this.options.size);
                renderBackground.call(_this);
                currentValue = [easeInOutQuad(process, from, to - from, _this.options.animate)];
                _this.options.onStep.call(_this, currentValue);
                drawLine.call(_this, currentValue);
                if (process >= _this.options.animate) {
                    return _this.options.onStop.call(_this);
                }
            };
            rAF(anim);
        };
        easeInOutQuad = function(t, b, c, d) {
            var easeIn, easing;
            easeIn = function(t) {
                return Math.pow(t, 2);
            };
            easing = function(t) {
                if (t < 1) {
                    return easeIn(t);
                } else {
                    return 2 - easeIn((t / 2) * -2 + 2);
                }
            };
            t /= d / 2;
            return c / 2 * easing(t) + b;
        };
        return this.init();
    };
    $.easyPieChart.defaultOptions = {
        barColor: '#ef1e25',
        trackColor: '#f2f2f2',
        scaleColor: '#dfe0e0',
        lineCap: 'round',
        size: 110,
        lineWidth: 3,
        animate: false,
        onStart: $.noop,
        onStop: $.noop,
        onStep: $.noop
    };
    $.fn.easyPieChart = function(options) {
        return $.each(this, function(i, el) {
            var $el;
            $el = $(el);
            if (!$el.data('easyPieChart')) {
                return $el.data('easyPieChart', new $.easyPieChart(el, options));
            }
        });
    };
    return void 0;
})(jQuery);;/**
 * downCount: Simple Countdown clock with offset
 * Author: Sonny T. <hi@sonnyt.com>, sonnyt.com
 */

(function ($) {

    $.fn.downCount = function (options, callback) {
        var settings = $.extend({
                date: null,
                offset: null
            }, options);

        // Throw error if date is not set
        if (!settings.date) {
            $.error('Date is not defined.');
        }

        // Throw error if date is set incorectly
        if (!Date.parse(settings.date)) {
            $.error('Incorrect date format, it should look like this, 12/24/2012 12:00:00.');
        }

        // Save container
        var container = this;

        /**
         * Change client's local date to match offset timezone
         * @return {Object} Fixed Date object.
         */
        var currentDate = function () {
            // get client's current date
            var date = new Date();

            // turn date to utc
            var utc = date.getTime() + (date.getTimezoneOffset() * 60000);

            // set new Date object
            var new_date = new Date(utc + (3600000*settings.offset))

            return new_date;
        };

        /**
         * Main downCount function that calculates everything
         */
        function countdown () {
            var target_date = new Date(settings.date), // set target date
                current_date = currentDate(); // get fixed current date

            // difference of dates
            var difference = target_date - current_date;

            // if difference is negative than it's pass the target date
            if (difference < 0) {
                // stop timer
                clearInterval(interval);

                if (callback && typeof callback === 'function') callback();

                return;
            }

            // basic math variables
            var _second = 1000,
                _minute = _second * 60,
                _hour = _minute * 60,
                _day = _hour * 24;

            // calculate dates
            var days = Math.floor(difference / _day),
                hours = Math.floor((difference % _day) / _hour),
                minutes = Math.floor((difference % _hour) / _minute),
                seconds = Math.floor((difference % _minute) / _second);

                // fix dates so that it will show two digets
                days = (String(days).length >= 2) ? days : '0' + days;
                hours = (String(hours).length >= 2) ? hours : '0' + hours;
                minutes = (String(minutes).length >= 2) ? minutes : '0' + minutes;
                seconds = (String(seconds).length >= 2) ? seconds : '0' + seconds;

            // based on the date change the refrence wording
            var ref_days = (days === 1) ? 'day' : 'days',
                ref_hours = (hours === 1) ? 'hour' : 'hours',
                ref_minutes = (minutes === 1) ? 'minute' : 'minutes',
                ref_seconds = (seconds === 1) ? 'second' : 'seconds';

            // set to DOM
            container.find('.days').text(days);
            container.find('.hours').text(hours);
            container.find('.minutes').text(minutes);
            container.find('.seconds').text(seconds);

            container.find('.days_ref').text(ref_days);
            container.find('.hours_ref').text(ref_hours);
            container.find('.minutes_ref').text(ref_minutes);
            container.find('.seconds_ref').text(ref_seconds);
        };
        
        // start
        var interval = setInterval(countdown, 1000);
    };

})(jQuery);

;/*
 * debouncedresize: special jQuery event that happens once after a window resize
 *
 * latest version and complete README available on Github:
 * https://github.com/louisremi/jquery-smartresize
 *
 * Copyright 2012 @louis_remi
 * Licensed under the MIT license.
 *
 * This saved you an hour of work?
 * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
 */
(function($) {

    var $event = $.event,
        $special, resizeTimeout;

    $special = $event.special.debouncedresize = {
        setup: function() {
            $(this).on("resize", $special.handler);
        },
        teardown: function() {
            $(this).off("resize", $special.handler);
        },
        handler: function(event, execAsap) {
            // Save the context
            var context = this,
                args = arguments,
                dispatch = function() {
                    // set correct event type
                    event.type = "debouncedresize";
                    $event.dispatch.apply(context, args);
                };

            if (resizeTimeout) {
                clearTimeout(resizeTimeout);
            }

            execAsap ? dispatch() : resizeTimeout = setTimeout(dispatch, $special.threshold);
        },
        threshold: 150
    };

})(jQuery);;
// jquery.events.frame.js
// 1.1 - lite
// Stephen Band
// 
// Project home:
// webdev.stephband.info/events/frame/
//
// Source:
// http://github.com/stephband/jquery.event.frame

(function(jQuery, undefined) {

    var timer;

    // Timer constructor
    // fn - callback to call on each frame, with context set to the timer object
    // fd - frame duration in milliseconds

    function Timer(fn, fd) {
        var self = this,
            clock;

        function update() {
            self.frameCount++;
            fn.call(self);
        }

        this.frameDuration = fd || 25;
        this.frameCount = -1;
        this.start = function() {
            update();
            clock = setInterval(update, this.frameDuration);
        };
        this.stop = function() {
            clearInterval(clock);
            clock = null;
        };
    }

    // callHandler() is the callback given to the timer object,
    // it makes the event object and calls the handler
    // context is the timer object

    function callHandler() {
        var fn = jQuery.event.special.frame.handler,
            event = jQuery.Event("frame"),
            array = this.array,
            l = array.length;

        // Give event object properties
        event.frameCount = this.frameCount;

        // Call handler on each elem in array
        while (l--) {
            fn.call(array[l], event);
        }
    }

    if (!jQuery.event.special.frame) {
        jQuery.event.special.frame = {
            // Fires the first time an event is bound per element
            setup: function(data, namespaces) {
                if (timer) {
                    timer.array.push(this);
                } else {
                    timer = new Timer(callHandler, data && data.frameDuration);
                    timer.array = [this];

                    // Queue timer to start as soon as this thread has finished
                    var t = setTimeout(function() {
                        timer.start();
                        clearTimeout(t);
                        t = null;
                    }, 0);
                }
                return;
            },
            // Fires last time event is unbound per element
            teardown: function(namespaces) {
                var array = timer.array,
                    l = array.length;

                // Remove element from list
                while (l--) {
                    if (array[l] === this) {
                        array.splice(l, 1);
                        break;
                    }
                }

                // Stop and remove timer when no elems left
                if (array.length === 0) {
                    timer.stop();
                    timer = undefined;
                }
                return;
            },
            handler: function(event) {
                // let jQuery handle the calling of event handlers
                jQuery.event.handle.apply(this, arguments);
            }
        };
    }

})(jQuery);;
/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function ($) {
  var inviewObjects = {}, viewportSize, viewportOffset,
      d = document, w = window, documentElement = d.documentElement, expando = $.expando, timer;

  $.event.special.inview = {
    add: function(data) {
      inviewObjects[data.guid + "-" + this[expando]] = { data: data, $element: $(this) };

      // Use setInterval in order to also make sure this captures elements within
      // "overflow:scroll" elements or elements that appeared in the dom tree due to
      // dom manipulation and reflow
      // old: $(window).scroll(checkInView);
      //
      // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
      // intervals while the user scrolls. Therefore the inview event might fire a bit late there
      // 
      // Don't waste cycles with an interval until we get at least one element that
      // has bound to the inview event.  
      if (!timer && !$.isEmptyObject(inviewObjects)) {
         timer = setInterval(checkInView, 250);
      }
    },

    remove: function(data) {
      try { delete inviewObjects[data.guid + "-" + this[expando]]; } catch(e) {}

      // Clear interval when we no longer have any elements listening
      if ($.isEmptyObject(inviewObjects)) {
         clearInterval(timer);
         timer = null;
      }
    }
  };

  function getViewportSize() {
    var mode, domObject, size = { height: w.innerHeight, width: w.innerWidth };

    // if this is correct then return it. iPad has compat Mode, so will
    // go into check clientHeight/clientWidth (which has the wrong value).
    if (!size.height) {
      mode = d.compatMode;
      if (mode || !$.support.boxModel) { // IE, Gecko
        domObject = mode === 'CSS1Compat' ?
          documentElement : // Standards
          d.body; // Quirks
        size = {
          height: domObject.clientHeight,
          width:  domObject.clientWidth
        };
      }
    }

    return size;
  }

  function getViewportOffset() {
    return {
      top:  w.pageYOffset || documentElement.scrollTop   || d.body.scrollTop,
      left: w.pageXOffset || documentElement.scrollLeft  || d.body.scrollLeft
    };
  }

  function checkInView() {
    var $elements = $(), elementsLength, i = 0;

    $.each(inviewObjects, function(i, inviewObject) {
      var selector  = inviewObject.data.selector,
          $element  = inviewObject.$element;
      $elements = $elements.add(selector ? $element.find(selector) : $element);
    });

    elementsLength = $elements.length;
    if (elementsLength) {
      viewportSize   = viewportSize   || getViewportSize();
      viewportOffset = viewportOffset || getViewportOffset();

      for (; i<elementsLength; i++) {
        // Ignore elements that are not in the DOM tree
        if (!$.contains(documentElement, $elements[i])) {
          continue;
        }

        var $element      = $($elements[i]),
            elementSize   = { height: $element.height(), width: $element.width() },
            elementOffset = $element.offset(),
            inView        = $element.data('inview'),
            visiblePartX,
            visiblePartY,
            visiblePartsMerged;
        
        // Don't ask me why because I haven't figured out yet:
        // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
        // Even though it sounds weird:
        // It seems that the execution of this function is interferred by the onresize/onscroll event
        // where viewportOffset and viewportSize are unset
        if (!viewportOffset || !viewportSize) {
          return;
        }
        
        if (elementOffset.top + elementSize.height > viewportOffset.top &&
            elementOffset.top < viewportOffset.top + viewportSize.height &&
            elementOffset.left + elementSize.width > viewportOffset.left &&
            elementOffset.left < viewportOffset.left + viewportSize.width) {
          visiblePartX = (viewportOffset.left > elementOffset.left ?
            'right' : (viewportOffset.left + viewportSize.width) < (elementOffset.left + elementSize.width) ?
            'left' : 'both');
          visiblePartY = (viewportOffset.top > elementOffset.top ?
            'bottom' : (viewportOffset.top + viewportSize.height) < (elementOffset.top + elementSize.height) ?
            'top' : 'both');
          visiblePartsMerged = visiblePartX + "-" + visiblePartY;
          if (!inView || inView !== visiblePartsMerged) {
            $element.data('inview', visiblePartsMerged).trigger('inview', [true, visiblePartX, visiblePartY]);
          }
        } else if (inView) {
          $element.data('inview', false).trigger('inview', [false]);
        }
      }
    }
  }

  $(w).bind("scroll resize scrollstop", function() {
    viewportSize = viewportOffset = null;
  });
  
  // IE < 9 scrolls to focused elements without firing the "scroll" event
  if (!documentElement.addEventListener && documentElement.attachEvent) {
    documentElement.attachEvent("onfocusin", function() {
      viewportOffset = null;
    });
  }
})(jQuery);;
function ChopScroll (handler, timeout, name) {
  this.timeout = timeout;
  this.handler = handler;
  this.name = name || 'unnamed';
  this.isExecuteTime = true;
  this.interval = '';
  var _this = this;
  init();

  function init() {
    //reset the execute determiner
    jQuery(window).scroll(function () {
      _this.isExecuteTime = true;
    });

    //execute the handler based on the timeout user passed
    _this.interval = setInterval(function () {
      if (_this.isExecuteTime) {
        try {
          handler();
        } catch (err) {
          console.log(err);
        }
        //turn off the exec time until user scroll again
        _this.isExecuteTime = false;
      }
    }, _this.timeout);
  }

}

// mega menu 
;(function ($, window, document, undefined) {

  var pluginName = "MegaMenu",
    defaults = {
      propertyName: "value"
    };
  var DELAY = 250;

  // the list of menus
  var menus = [];

  function CustomMenu(element, options) {
    this.element = element;

    this.options = $.extend({}, defaults, options);

    this._defaults = defaults;
    this._name = pluginName;

    this.init(element);
  } 

  CustomMenu.prototype = {
    isOpen: false,
    timeout: null,
    init: function (element) {

      var that = this;
      var id = $(element).attr('id');

      $("#" + id).each(function(index, menu) {

        that.node = menu;
        that.addListeners(menu);

        $(menu).addClass("dropdownJavascript");
        menus.push(menu);

        $(menu).find('ul > li').each(function(index, submenu) {
          if ($(submenu).find('ul').length > 0 ) {
            $(submenu).addClass('with-menu');
          }
        });
      });
    },
    addListeners: function(menu) {
      var that = this;
      $(menu).mouseover(function(e) {
        that.handleMouseOver.call(that, e);
      }).mouseout(function(e) {
          that.handleMouseOut.call(that, e);
        });
    },
    handleMouseOver: function (e) {
      var that = this;
      // clear the timeout
      this.clearTimeout();

      // find the parent list item
      //var item = ('target' in e ? e.target : e.srcElement);
      var item = e.target || e.srcElement;
      while (item.nodeName != 'LI' && item != this.node) {
        item = item.parentNode;
      }

      // if the target is within a list item, set the timeout
      if (item.nodeName == 'LI') {
        this.toOpen = item;
        this.timeout = setTimeout(function() {
          that.open.call(that);
        }, this.options.delay);
      }

    },
    handleMouseOut: function () {
      var that = this;
      // clear the timeout
      this.clearTimeout();

      this.timeout = setTimeout(function() {
        that.close.call(that);
      }, this.options.delay);

    },
    clearTimeout: function () {

      // clear the timeout
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

    },
    open: function () {

      var that = this;
      // store that the menu is open
      this.isOpen = true;

      // loop over the list items with the same parent
      var items = $(this.toOpen).parent().children('li');
      $(items).each(function(index, item) {
        $(item).find("ul").each(function(index, submenu) {
          if (item != that.toOpen) {
            // close the submenu
            $(item).removeClass("dropdownOpen");
            that.close(item);

          } else if (!$(item).hasClass('dropdownOpen')) {

            // open the submenu
            //if ( !$(item).parents('li').hasClass('has-mega-menu') ) {
              $(item).addClass("dropdownOpen");
            //}


            // determine the location of the edges of the submenu
            var left = 0;
            var node = submenu;
            while (node) {
              //abs is because when you make menus right to left
              //the offsetLeft would be negative
              left += Math.abs(node.offsetLeft);
              node = node.offsetParent;
            }
            var right = left + submenu.offsetWidth;


            //We should refactor this code to execute only when menu is vertical
            var menuHeight = $(submenu).outerHeight();
            var parentTop = $(submenu).offset().top - $(window).scrollTop();
            var totalHeight = menuHeight + parentTop;
            var windowHeight = window.innerHeight;

           /* if (totalHeight > windowHeight) {
              var bestTop = (windowHeight - totalHeight) - 20;
              $(submenu).css('margin-top', bestTop + "px");
            }*/

            //remove any previous classes
            $(item).removeClass('dropdownRightToLeft');

            // move the submenu to the right of the item if appropriate
            if (left < 0) $(item).addClass('dropdownLeftToRight');

            // move the submenu to the left of the item if appropriate
            if (right > document.body.clientWidth) {
              $(item).addClass('dropdownRightToLeft');
            }

          }
        });
      });

    },


    close: function (node) {

      // if no node was specified, close all menus
      if (!node) {
        this.isOpen = false;
        node = this.node;
      }

      // loop over the items, closing their submenus
      $(node).find('li').each(function(index, item) {
        $(item).removeClass('dropdownOpen');
      });

    }
  };

  $.fn[pluginName] = function (options) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName,
          new CustomMenu(this, options));
      }
    });
  };

})(jQuery, window, document);
;;
(function($, window, undefined) {

	'use strict';

	// global
	var Modernizr = window.Modernizr,
		$body = $('body');

	$.DLMenu = function(options, element) {
		this.$el = $(element);
		this._init(options);
	};

	$.DLMenu.defaults = {
		animationClasses: {
			classin: 'pacz-vm-animate-in-' + $('body').attr('data-vm-anim'),
			classout: 'pacz-vm-animate-out-' + $('body').attr('data-vm-anim')
		},
		onLevelClick: function(el, name) {
			return false;
		},
		onLinkClick: function(el, ev) {
			return false;
		}
	};

	$.DLMenu.prototype = {
		_init: function(options) {

			this.options = $.extend(true, {}, $.DLMenu.defaults, options);
			this._config();

			var animEndEventNames = {
					'WebkitAnimation': 'webkitAnimationEnd',
					'OAnimation': 'oAnimationEnd',
					'msAnimation': 'MSAnimationEnd',
					'animation': 'animationend'
				},
				transEndEventNames = {
					'WebkitTransition': 'webkitTransitionEnd',
					'MozTransition': 'transitionend',
					'OTransition': 'oTransitionEnd',
					'msTransition': 'MSTransitionEnd',
					'transition': 'transitionend'
				};
			this.animEndEventName = animEndEventNames[Modernizr.prefixed('animation')] + '.dlmenu';
			this.transEndEventName = transEndEventNames[Modernizr.prefixed('transition')] + '.dlmenu',
			this.supportAnimations = Modernizr.cssanimations,
			this.supportTransitions = Modernizr.csstransitions;

			this._initEvents();

		},
		_config: function() {
			this.open = false;
			var $backText = $('body').attr('data-backText');
			this.$trigger = this.$el.children('.pacz-vm-trigger');
			this.$menu = this.$el.children('ul.pacz-vm-menu');
			this.$menuitems = this.$menu.find('li:not(.pacz-vm-back)');
			this.$el.find('ul.sub-menu').prepend('<li class="pacz-vm-back"><a href="#">' + $backText + '</a></li>');
			this.$back = this.$menu.find('li.pacz-vm-back');
		},
		_initEvents: function() {

			var self = this;

			this.$trigger.on('click.dlmenu', function() {

				if (self.open) {
					self._closeMenu();
				} else {
					self._openMenu();
				}
				return false;

			});

			this.$menuitems.on('click.dlmenu', function(event) {

				event.stopPropagation();

				var $item = $(this),
					$submenu = $item.children('ul.sub-menu');

				if ($submenu.length > 0) {

					var $flyin = $submenu.clone().css('opacity', 0).insertAfter(self.$menu),
						onAnimationEndFn = function() {
							self.$menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classout).addClass('pacz-vm-subview');
							$item.addClass('pacz-vm-subviewopen').parents('.pacz-vm-subviewopen:first').removeClass('pacz-vm-subviewopen').addClass('pacz-vm-subview');
							$flyin.remove();
						};

					setTimeout(function() {
						$flyin.addClass(self.options.animationClasses.classin);
						self.$menu.addClass(self.options.animationClasses.classout);
						if (self.supportAnimations) {
							self.$menu.on(self.animEndEventName, onAnimationEndFn);
						} else {
							onAnimationEndFn.call();
						}

						self.options.onLevelClick($item, $item.children('a:first').text());
					});

					return false;

				} else {
					self.options.onLinkClick($item, event);
				}

			});

			this.$back.on('click.dlmenu', function(event) {

				var $this = $(this),
					$submenu = $this.parents('ul.sub-menu:first'),
					$item = $submenu.parent(),

					$flyin = $submenu.clone().insertAfter(self.$menu);

				var onAnimationEndFn = function() {
					self.$menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classin);
					$flyin.remove();
				};

				setTimeout(function() {
					$flyin.addClass(self.options.animationClasses.classout);
					self.$menu.addClass(self.options.animationClasses.classin);
					if (self.supportAnimations) {
						self.$menu.on(self.animEndEventName, onAnimationEndFn);
					} else {
						onAnimationEndFn.call();
					}

					$item.removeClass('pacz-vm-subviewopen');

					var $subview = $this.parents('.pacz-vm-subview:first');
					if ($subview.is('li')) {
						$subview.addClass('pacz-vm-subviewopen');
					}
					$subview.removeClass('pacz-vm-subview');
				});

				return false;

			});

		},
		closeMenu: function() {
			if (this.open) {
				this._closeMenu();
			}
		},
		_closeMenu: function() {
			var self = this,
				onTransitionEndFn = function() {
					self.$menu.off(self.transEndEventName);
					self._resetMenu();
				};

			this.$menu.removeClass('pacz-vm-menuopen');
			this.$menu.addClass('pacz-vm-menu-toggle');
			this.$trigger.removeClass('pacz-vm-active');

			if (this.supportTransitions) {
				this.$menu.on(this.transEndEventName, onTransitionEndFn);
			} else {
				onTransitionEndFn.call();
			}

			this.open = false;
		},
		openMenu: function() {
			if (!this.open) {
				this._openMenu();
			}
		},
		_openMenu: function() {
			var self = this;
			$body.off('click').on('click.dlmenu', function() {
				self._closeMenu();
			});
			this.$menu.addClass('pacz-vm-menuopen pacz-vm-menu-toggle').on(this.transEndEventName, function() {
				$(this).removeClass('pacz-vm-menu-toggle');
			});
			this.$trigger.addClass('pacz-vm-active');
			this.open = true;
		},
		_resetMenu: function() {
			this.$menu.removeClass('pacz-vm-subview');
			this.$menuitems.removeClass('pacz-vm-subview pacz-vm-subviewopen');
		}
	};

	var logError = function(message) {
		if (window.console) {
			window.console.error(message);
		}
	};

	$.fn.dlmenu = function(options) {
		if (typeof options === 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			this.each(function() {
				var instance = $.data(this, 'dlmenu');
				if (!instance) {
					logError("cannot call methods on dlmenu prior to initialization; " +
						"attempted to call method '" + options + "'");
					return;
				}
				if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {
					logError("no such method '" + options + "' for dlmenu instance");
					return;
				}
				instance[options].apply(instance, args);
			});
		} else {
			this.each(function() {
				var instance = $.data(this, 'dlmenu');
				if (instance) {
					instance._init();
				} else {
					instance = $.data(this, 'dlmenu', new $.DLMenu(options, this));
				}
			});
		}
		return this;
	};

})(jQuery, window);;;(function ($, window, document, undefined) {

  // Defaults
  var pluginName = "sectiontrans",
    defaults = {
      effect: "fade"
    };

  // The actual plugin constructor
  function Plugin(element, options) {
    this.element = element;

    //merge options and defaults
    this.options = $.extend({}, defaults, options);

    this._defaults = defaults;
    this._name = pluginName;

    this.effectClassName = 'intro-effect-' + this.options.effect;

    this.init();
  }

  Plugin.prototype = {

    init: function () {
      // refreshing the page...
      var pageScroll = this.scrollY();
      this.noscroll = pageScroll === 0;

      this.disable_scroll();

      $(this.element).addClass(this.effectClassName);

      if (pageScroll) {
        this.isRevealed = true;
        $(this.element).addClass('notrans');
        $(this.element).addClass('modify');
      }

      var that = this;

      window.addEventListener('scroll', function(e) {
        that.scrollPage.call(that, e);
      });
    },
    keys: [32, 37, 38, 39, 40],
    docElem: window.document.documentElement,
    scrollVal: 0,
    isRevealed: false,
    noscroll: false,
    isAnimating: false,
    trigger: $('button.trigger'),
    preventDefault: function (e) {
      e = e || window.event;
      if (e.preventDefault)
        e.preventDefault();
      e.returnValue = false;
    },
    ie: (function () {
      var undef, rv = -1; // Return value assumes failure.
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf('MSIE ');
      var trident = ua.indexOf('Trident/');

      if (msie > 0) {
        // IE 10 or older => return version number
        rv = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      } else if (trident > 0) {
        // IE 11 (or newer) => return version number
        var rvNum = ua.indexOf('rv:');
        rv = parseInt(ua.substring(rvNum + 3, ua.indexOf('.', rvNum)), 10);
      }

      return ((rv > -1) ? rv : undef);
    }()),
    wheel: function (e) {
      // for IE
      if( this.ie ) {
      preventDefault(e);
      }
    },
    disable_scroll: function () {
      window.onmousewheel = document.onmousewheel = this.wheel;
    },
    enable_scroll: function () {
      window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null;
    },
    scrollY: function () {
      return window.pageYOffset || this.docElem.scrollTop;
    },
    scrollPage: function () {
      this.scrollVal = this.scrollY();

      if (this.noscroll) {
        if (this.scrollVal < 0) return false;
        // keep it that way
        window.scrollTo(0, 0);
      }

      if ($(this.element).hasClass('notrans')) {
        $(this.element).removeClass('notrans');
        return false;
      }

      if (this.isAnimating) {
        return false;
      }

      if (this.scrollVal <= 0 && this.isRevealed) {
        this.toggle(0);
      }
      else if (this.scrollVal > 0 && !this.isRevealed) {
        this.toggle(1);
      }
    },
    toggle: function (reveal) {
      this.isAnimating = true;

      if (reveal) {
        $(this.element).addClass('pacz-intro-triggered');
        $('.' + this.effectClassName).next().next().addClass('pacz-intro-triggered' + ' ' + 'page-effect-'+this.options.effect);
        $('body').addClass('pacz-intro-triggered').trigger('page_intro');
      }
      else {
        this.noscroll = true;
        this.disable_scroll();
        $(this.element).removeClass('pacz-intro-triggered');
        $('.' + this.effectClassName).next().next().removeClass('pacz-intro-triggered' + ' ' + 'page-effect-'+this.options.effect);
        $('body').removeClass('pacz-intro-triggered').trigger('page_outro');
      }

      var that = this;
      // simulating the end of the transition:
      setTimeout(function () {
        that.isRevealed = !that.isRevealed;
        that.isAnimating = false;
        if (reveal) {
          that.noscroll = false;
          that.enable_scroll();
        }
      }, 1200);
    }

  };

  // A really lightweight plugin wrapper around the constructor,
  // preventing against multiple instantiations
  $.fn[pluginName] = function (options) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName,
          new Plugin(this, options));
      }
    });
  };

})(jQuery, window, document);;

/**
 * Request Animation Frame Polyfill.
 * @author Tino Zijdel
 * @author Paul Irish
 * @see https://gist.github.com/paulirish/1579671
 */
;(function() {
	var vendors = ['webkit', 'moz', 'ms', 'o'], vp = null;
	for (var x = 0; x < vendors.length && !window.requestAnimationFrame && !window.cancelAnimationFrame; x++)
	{
		vp = vendors[x];
		window.requestAnimationFrame = window.requestAnimationFrame || window[vp + 'RequestAnimationFrame'];
		window.cancelAnimationFrame = window.cancelAnimationFrame || window[vp + 'CancelAnimationFrame'] || window[vp + 'CancelRequestAnimationFrame'];
	}
	if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) || !window.requestAnimationFrame || !window.cancelAnimationFrame) //iOS6 is buggy.
	{
		var lastTime = 0;
		window.requestAnimationFrame = function(callback, element)
		{
			var now = window.performance.now();
			var nextTime = Math.max(lastTime + 16, now);
			return setTimeout(function() { callback(lastTime = nextTime); }, nextTime - now);
		};
		window.cancelAnimationFrame = clearTimeout;
	}
}());