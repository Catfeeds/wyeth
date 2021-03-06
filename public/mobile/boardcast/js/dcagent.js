!
function(t, e) {
    "object" == typeof exports && "undefined" != typeof module ? e(exports) : "function" == typeof define && define.amd ? define(["exports"], e) : e(t.DCAgent = {})
} (this,
function(t) {
    "use strict";
    function e() {}
    function n(t) {
        return "function" == typeof t
    }
    function r(t) {
        return t && "[object Object]" === Yt.call(t)
    }
    function o(t) {
        console.log("---- DCAgent log start ----\n" + t + "\n---- DCAgent log end   ----")
    }
    function i(t) {
        var e, n, r;
        return e = Date.now(),
        n = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx",
        r = n.replace(/[xy]/g,
        function(t) {
            var n;
            return n = (e + 16 * Math.random()) % 16 | 0,
            e = Math.floor(e / 16),
            "x" === t ? n.toString(16) : (7 & n | 8).toString(16)
        }),
        (t || "") + r.replace(/-/g, "").toUpperCase()
    }
    function c(t) {
        var e, n;
        for (e in t) n = t[e],
        t[e] = n;
        var r = arguments.length >= 2 ? [].slice.call(arguments, 1) : [];
        return r.forEach(function(r) {
            var o;
            o = [];
            for (e in r) n = r[e],
            o.push(t[e] = n);
            return o
        }),
        t
    }
    function u(t) {
        return Jt(function() {
            var e = "0";
            t.setItem(e, e);
            var n = t.getItem(e) === e;
            return t.removeItem(e),
            n
        })
    }
    function a(t) {
        for (var e = arguments.length <= 1 || void 0 === arguments[1] ? 0 : arguments[1], n = "", r = 0; e > r; r += 1) n += t;
        return n
    }
    function s(t, e, n) {
        return t ? t && t.length >= n ? t: t + a(e, Math.ceil(n - t.length) / e.length) : t
    }
    function f(t, e, n) {
        return function() {
            Array.isArray(e) || (e = [e]),
            Array.isArray(n) || (n = [n]);
            var r, o;
            for (r = 0; r < e.length; r += 1) if (o = e[r], Jt(o, this, arguments) === !1) return;
            var i = Jt(t, this, arguments);
            if (i === !1) return ! 1;
            for (r = 0; r < n.length; r += 1) o = n[r],
            Jt(o, this, arguments);
            return i
        }
    }
    function g(t) {
        if (!t) return "";
        var e = t.match(/^(https?\:)\/\/(([^:\/?#]*)(?:\:([0-9]+))?)(\/[^?#]*)(\?[^#]*|)(#.*|)$/);
        return e ? e[3] : ""
    }
    function d(t, e, n) {
        return [].slice.call(t, e, n)
    }
    function l(t) {
        var e = arguments.length <= 1 || void 0 === arguments[1] ? 0 : arguments[1],
        n = arguments.length <= 2 || void 0 === arguments[2] ? 10 : arguments[2];
        return t >= 1e21 && (t = 9527e16),
        kt.parseInt(t, n) || e
    }
    function p(t) {
        return Math.min(99e19, t)
    }
    function b(t) {
        try {
            return t ? JSON.stringify(t) : null
        } catch(e) {
            o("invalid json format")
        }
        return null
    }
    function m(t) {
        try {
            return t ? JSON.parse(t) : null
        } catch(e) {
            o("invalid json string")
        }
        return null
    }
    function j(t, e) {
        var n = this;
        this.duration = e,
        this.status = "running",
        this.timer = Bt(function() {
            return n.run()
        },
        this.duration),
        this.run = function() {
            "cancelled" !== n.status && (Xt(n.timer), Dt.attempt(t), n.timer = Bt(function() {
                return n.run()
            },
            n.duration))
        },
        this.stop = function() {
            n.status = "stopped",
            Xt(n.timer)
        },
        this.reset = function(t) {
            "cancelled" !== n.status && (n.stop(), t && (n.duration = t), n.run())
        },
        this.cancel = function() {
            this.status = "cancelled",
            Xt(this.timer)
        }
    }
    function T(t) {
        Nt && (Nt.stop(), Bt(function() {
            Nt && Nt.reset(t)
        },
        t), t && (Ht.interval = t))
    }
    function I() {
        Nt && (Nt.cancel(), Nt = null)
    }
    function E() {
        Nt && Nt.stop()
    }
    function v() {
        Nt && Nt.run()
    }
    function O(t, e) {
        Nt = new j(t, e)
    }
    function h() {
        if (Kt) return Kt;
        if (te += 1, !(te > 4)) {
            var t = {
                egret: "egret",
                layabox: "layabox",
                cocos: "cc.game",
                impact: "ig",
                phaser: "Phaser",
                pixi: "PIXI",
                create: "createjs",
                three: "THREE",
                gameMaker: "asset_get_type",
                playCanvas: "pc.fw",
                turbulenz: "TurbulenzEngine",
                quintus: "Quintus",
                melon: "me.game",
                lychee: "lychee",
                wade: "wade.addSceneObject",
                crafty: "Crafty",
                lime: "lime.Scene",
                enchant: "enchant",
                isogenic: "IgeEngine",
                gameclosure: "GC.Application",
                panda: "game.Scene",
                kiwi: "Kiwi",
                jaws: "jaws",
                sirius2d: "ss2d",
                collie: "collie",
                physics: "Physics",
                stage: "Stage.Anim",
                babylon: "BABYLON"
            };
            for (var e in t) {
                var n = t[e];
                if (n.indexOf(".") > -1) {
                    var r = n.split("."),
                    o = kt[r[0]];
                    if (o && o[r[1]]) return Kt = e,
                    e
                } else if (kt[n]) return Kt = e,
                e
            }
        }
    }
    function y() {
        var t = Ht.loginTime || Ht.initTime;
        return {
            loginTime: t,
            onlineTime: Dt.parseInt(Date.now() / 1e3) - t || 1,
            extendMap: {
                from: Ht.from,
                engine: h() || "",
                app: Ht.app
            }
        }
    }
    function _(t, e) {
        var n = {
            headerInfo: pe,
            onlineInfo: y(),
            errorInfoList: Zt.concat(),
            eventInfoList: be.concat()
        };
        return t && (n.paymentInfo = t),
        e && (n.userInfo = e),
        n
    }
    function S() {
        be.length = 0,
        Zt.length = 0
    }
    function A() {
        if (Wt && n(Wt.createElement)) {
            var t = Wt.createElement("div");
            if (!t) return ! 1;
            if (n(t.querySelector)) {
                t.innerHTML = "<i></i>";
                var e = t.querySelector("i");
                return !! e && "I" === e.tagName
            }
            if (n(t.getElementsByTagName)) {
                var r = t.getElementsByTagName("i");
                return !! r && 1 === r.length
            }
        }
        return ! 1
    }
    function N(t) {
        return pe.appId + "." + t
    }
    function L(t, e) {
        t = N(t),
        fn.setItem(t, e),
        sn.set(t, e, 3650)
    }
    function D(t) {
        return t = N(t),
        fn.getItem(t) || sn.get(t)
    }
    function R(t, e) {
        fn.setItem(N(t), e)
    }
    function w(t) {
        return fn.getItem(N(t))
    }
    function C(t) {
        fn.removeItem(N(t))
    }
    function x() {
        xt.setItem(Ct.LOGOUT_TIME, Dt.parseInt(Date.now() / 1e3)),
        (Zt.length || be.length) && xt.setItem(Ct.QUIT_SNAPSHOT, Dt.jsonStringify(_()))
    }
    function U() {
        var t = xt.getItem(Ct.QUIT_SNAPSHOT);
        return t && Dt.jsonParse(t)
    }
    function M(t) {
        me >= wt.MAX_ERROR_COUNT || (Zt.push(t), me += 1)
    }
    function P(t) {
        be.push(t)
    }
    function V(t, e) {
        if (!t) return void Dt.tryThrow("Missing eventId");
        var n = function(t) {
            return t.replace(/%/g, "_")
        };
        arguments.length > 2 && (e = arguments[2]);
        var r = {};
        if (Dt.isObject(e)) for (var o in e) r[n(o)] = "number" == typeof e[o] ? e[o] : encodeURIComponent(e[o]);
        var i = {
            eventId: n(t),
            eventMap: r
        };
        return Rt.addEvent(i),
        e && e.immediate === !0 && (Lt.reset(), Lt.run()),
        i
    }
    function G() {
        return pe.uid || ""
    }
    function H(t) {
        var e = kt.egret,
        n = new e.URLLoader,
        r = Date.now();
        n.addEventListener(e.Event.COMPLETE,
        function(e) {
            var n = Date.now() - r,
            o = e.target,
            i = "success" === o.data;
            Dt.attempt(i ? t.success: t.error, o, [o, n, n >= t.timeout]),
            Dt.attempt(t.complete, o, [o, n])
        });
        var o = new e.URLRequest(t.url);
        o.method = t.method || e.URLRequestMethod.POST,
        o.data = Dt.jsonStringify(t.data),
        n.load(o)
    }
    function F() {
        return kt.cc.loader.getXMLHttpRequest()
    }
    function k(t) {
        var e = pn();
        dn && (e.timeout = t.timeout),
        e.open(t.method || "POST", t.url, !0),
        Tn(e, "text/plain; charset=UTF-8");
        var n = Date.now();
        e.onreadystatechange = function() {
            if (4 === this.readyState) {
                var e = this.status >= 200 && this.status < 300,
                r = Date.now() - n;
                Dt.attempt(e ? t.success: t.error, this, [this, r]),
                Dt.attempt(t.complete, this, [this, r]),
                this.onreadystatechange = null,
                this.ontimeout = null
            }
        },
        dn && (e.ontimeout = function() {
            var e = Date.now() - n;
            Dt.attempt(t.error, this, [this, e, !0]),
            Dt.attempt(t.complete, this, [this, e]),
            this.onreadystatechange = null,
            this.ontimeout = null
        }),
        e.send(Dt.jsonStringify(t.data))
    }
    function B(e, n) {
        var r = Date.now();
        if (!n) {
            if (r - hn < wt.ASAP_TIMEOUT) return void Dt.tryThrow("Request dropped: rate limit");
            hn = r
        }
        On += 1,
        t.report = e.data,
        In({
            url: e.url,
            data: e.data,
            success: function(t, n) {
                Dt.attempt(e.success, t, [t, n])
            },
            error: function(t, n, r) {
                gn += 1,
                Dt.attempt(e.error, t, [t, n, r])
            },
            complete: function(t, n) {
                if (Dt.attempt(e.complete, t, [t, n]), t.getAllResponseHeaders && t.getResponseHeader) {
                    var r = t.getAllResponseHeaders(),
                    o = "X-Rate-Limit";
                    if ( - 1 !== r.indexOf(o)) {
                        var i = Dt.parseInt(t.getResponseHeader(o));
                        i > 1 && Lt.reset(1e3 * i)
                    }
                }
            }
        })
    }
    function X(t) {
        if (!t) return ! 1;
        var e = t.onlineInfo.onlineTime;
        return 1 > e || e > wt.MAX_ONLINE_TIME ? (Dt.tryThrow("Illegal online time"), !1) : !0
    }
    function q() {
        return Ht.inited ? void 0 : (Dt.tryThrow("DCAgent.init needed"), !1)
    }
    function K() {
        return Ht.loginTime ? void 0 : (Dt.tryThrow("DCAgent.login needed"), !1)
    }
    function Y() {
        return Ht.destroyed ? (Dt.tryThrow("DCAgent is destroyed already"), !1) : void 0
    }
    function z(t) {
        return t + "?__deuid=" + pe.uid + "&__deappid=" + pe.appId
    }
    function Q(t) {
        return t + "?type=h520&appId=" + pe.appId + "&uid=" + pe.uid + "&mac=" + (pe.mac || "") + "&imei=" + (pe.imei || "") + "&idfa=" + (pe.idfa || "")
    }
    function J(t, e, n) {
        if (t || !Dt.hiddenProperty || !Wt[Dt.hiddenProperty]) {
            var r = {
                url: Pt.appendOnline(Pt.API_PATH)
            };
            if (On && On % Ht.oss === 0 && Rt.addEvent({
                eventId: Ct.REQ_KEY,
                eventMap: {
                    succ: On - gn,
                    fail: gn,
                    total: On
                }
            }), r.data = Rt.collect(e, n), Mt.isParamsValid(r.data)) {
                Rt.clear();
                var o = r.data.errorInfoList,
                i = r.data.eventInfoList; (i.length || o.length) && (r.error = function() {
                    o.forEach(function(t) {
                        Rt.addError(t)
                    }),
                    i.forEach(function(t) {
                        Rt.addEvent(t)
                    })
                }),
                B(r, t)
            }
        }
    }
    function W(t) {
        t || (t = Dt.isDebug ? wt.ASAP_TIMEOUT_DEBUG: wt.ASAP_TIMEOUT),
        Xt(En),
        Lt.stop(),
        En = Bt(function() {
            Lt.run()
        },
        t)
    }
    function $(t) {
        if (!t) return void Dt.tryThrow("Missing accountID");
        if (pe.accountId === t) return void(Ht.loginTime = Ht.loginTime || Dt.parseInt(Date.now() / 1e3));
        Vt.setPollingDebounce(Ht.interval),
        J(!0),
        Ht.loginTime = Dt.parseInt(Date.now() / 1e3);
        var e = Ct.ACCOUNT_RELATED_SETTINGS + "," + Ct.ACCOUNT_ROLE_SETTINGS;
        e.split(",").forEach(function(t) {
            return pe[t] = ""
        }),
        pe.age = wt.DEFAULT_AGE,
        pe.gender = wt.DEFAULT_GENDER,
        pe.roleLevel = wt.DEFAULT_ROLE_LEVEL,
        pe.accountId = t,
        J(!0)
    }
    function Z(t, e, n, r) {
        var o = arguments;
        Ct.ACCOUNT_ROLE_SETTINGS.split(",").forEach(function(t, e) {
            return pe[t] = o[e] || ""
        }),
        pe.roleLevel = Dt.parseInt(r) || 1
    }
    function tt(t, e, n, r) {
        Z(t, e, n, r),
        V("DE_EVENT_CREATE_ROLE", {
            roleId: String(t),
            roleRace: String(e),
            roleClass: String(n)
        })
    }
    function et(t) {
        pe.gender = 2 === t ? 2 : 1
    }
    function nt(t) {
        pe.gameServer = String(t)
    }
    function rt(t) {
        t = Dt.parseInt(t),
        pe.age = t > 0 && 128 > t ? t: 0
    }
    function ot(t) {
        pe.accountType = String(t)
    }
    function it(t, e) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_TASK, {
            actionType: "taskUnfinish",
            taskId: String(t),
            elapsed: e
        })
    }
    function ct(t, e) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_TASK, {
            actionType: "taskFinish",
            taskId: String(t),
            elapsed: e
        })
    }
    function ut(t) {
        if (!t || !t.hasOwnProperty("amount")) return void Dt.tryThrow("Missing amount");
        var e = {
            currencyAmount: Dt.max(parseFloat(t.amount, 10) || 0),
            currencyType: t.currencyType || "CNY",
            payType: String(t.payType || ""),
            iapid: String(t.iapid || ""),
            payTime: Dt.parseInt(Date.now() / 1e3),
            extendMap: {
                orderId: String(t.orderId || "")
            }
        };
        return e.currencyAmount <= 0 ? void Dt.tryThrow("amount must be greater than 0") : (J(!0, e), e)
    }
    function at(t, e) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_MISSION, {
            actionType: "guankaUnfinish",
            guankaId: String(t),
            duration: e
        })
    }
    function st(t, e) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_MISSION, {
            actionType: "guankaFinish",
            guankaId: String(t),
            duration: e
        })
    }
    function ft(t, e, n) {
        return t = Dt.parseInt(t),
        e = Dt.parseInt(e),
        n = Dt.parseInt(n),
        0 > t || 0 > e || t > e || 0 > n ? (Dt.tryThrow("Argument error"), !1) : (pe.roleLevel = e, void V(Ct.EVT_LEVEL, {
            startLevel: t,
            endLevel: e,
            duration: n
        }))
    }
    function gt(t, e, n, r) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_ITEM, {
            actionType: "itemUse",
            itemId: String(t),
            itemNum: e,
            reason: String(r),
            missonID: String(n)
        })
    }
    function dt(t, e, n, r) {
        return e = Dt.parseInt(e),
        0 > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_ITEM, {
            actionType: "itemGet",
            itemId: String(t),
            itemNum: e,
            reason: String(r),
            missonID: String(n)
        })
    }
    function lt(t, e, n, r, o) {
        return e = Dt.parseInt(e),
        r = Dt.parseInt(r),
        0 > e || 0 > r ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_ITEM, {
            actionType: "itemBuy",
            itemId: String(t),
            itemNum: e,
            coinType: String(n),
            coinNum: r,
            missonID: String(o)
        })
    }
    function pt(t, e, n, r) {
        return e = Dt.parseInt(e),
        t = Dt.parseInt(t),
        0 > e || 0 > t ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_COIN, {
            actionType: "coinUse",
            coinType: String(n),
            balanceNum: e,
            coinNum: t,
            reason: String(r)
        })
    }
    function bt(t, e, n, r) {
        return e = Dt.parseInt(e),
        t = Dt.parseInt(t),
        0 > e || 0 > t || t > e ? (Dt.tryThrow("Argument error"), !1) : void V(Ct.EVT_COIN, {
            actionType: "coinGet",
            coinType: String(n),
            balanceNum: e,
            coinNum: t,
            reason: String(r)
        })
    }
    function mt() {
        for (var t = ["pagehide", "beforeunload", "unload"], e = 0; e < t.length; e += 1) if ("on" + t[e] in kt) return t[e]
    }
    function jt(t) {
        if (kt.addEventListener) {
            var e = mt();
            e && kt.addEventListener(e, t)
        }
    }
    function Tt(t) {
        if (Ht.storage && (jt(Rt.saveToStorage), !t)) {
            var e = Rt.loadFromStorage();
            e && (B({
                url: Pt.appendOnline(Pt.API_PATH),
                data: e
            },
            !0), xt.removeItem(Ct.QUIT_SNAPSHOT))
        }
    }
    function It() {
        kt.addEventListener && kt.addEventListener("error",
        function(t) {
            Dt.attempt(function() {
                var e = {},
                n = ["colno", "filename", "lineno", "message"];
                n.forEach(function(n) {
                    return e[n] = t[n] || "1"
                });
                var r = t.error || {};
                if (e.stack = encodeURIComponent(r.stack || r.stacktrace || ""), e.type = r.name || "Error", e.timestamp = parseInt(t.timeStamp / 1e3), Dt.isFunction(Ht.getErrorScene)) {
                    var o = Dt.attempt(Ht.getErrorScene, r, [t]);
                    if (o) {
                        if (Dt.isObject(o)) {
                            var i = "";
                            for (var c in o) i += " " + c + "=" + o[c] + "\n";
                            o = i
                        } else o = String(o);
                        e.stack += "\n\nError scene:\n" + encodeURIComponent(o)
                    }
                }
                Rt.addError(e)
            })
        },
        !1)
    }
    function Et() {
        var t = Date.now().toString(36).toUpperCase(),
        e = Gt.engine;
        return e.egret ? Ct.EGRET_PREFIX + t: e.layabox ? Ct.LAYA_PREFIX + t: e.cocos ? Ct.COCOS_PREFIX + t: Ct.UNKNOW_ENGINE + t
    }
    function vt() {
        var t;
        try {
            if (Gt.engine.layabox) {
                var e = kt.layabox.getDeviceInfo() || {};
                t = e.mac || e.idfa,
                t = t && t.replace(/[-_:=\s]+/g, "").toUpperCase()
            }
        } catch(n) {
            t = null
        }
        return t = Dt.padding(t, Ct.PADDING_STRING, wt.UID_MIN_LENGTH),
        t || Dt.uuid(Et())
    }
    function Ot(t) {
        if (pe.uid) {
            var e = Dt.padding(pe.uid, Ct.PADDING_STRING, wt.UID_MIN_LENGTH);
            t !== e && (pe.uid = e, t = e, xt.setItem(Ct.CREATE_TIME, Dt.parseInt(Date.now() / 1e3)))
        }
        var n = t || vt();
        return xt.setUID(Ct.CLIENT_KEY, n),
        n
    }
    function ht(t) {
        var e = xt.getUID(Ct.CLIENT_KEY),
        n = e ? 0 : 1,
        r = Ot(e);
        pe.uid = r,
        pe.accountId = r,
        t.errorReport && It(),
        Ht.initTime = Dt.parseInt(Date.now() / 1e3);
        var o = xt.getItem(Ct.CREATE_TIME);
        o || (o = Ht.initTime, xt.setItem(Ct.CREATE_TIME, o)),
        Ht.createTime = Dt.parseInt(o);
        var i = on.href || "!";
        Rt.addEvent({
            eventId: Ct.EVT_PV,
            eventMap: {
                page: encodeURI(i.split("?")[0])
            }
        });
        var c = n ? {
            actTime: o,
            regTime: o
        }: null;
        J(!0, null, c),
        Tt(n);
        var u = Dt.isDebug ? wt.MIN_ONLINE_INTERVAL_DEBUG: wt.MIN_ONLINE_INTERVAL,
        a = 1e3 * Math.max(u, parseFloat(t.interval || u));
        Lt.set(J, a),
        Ht.interval = a,
        Ht.inited = !0
    }
    function yt(t) {
        return Ht.storage = Dt.isLocalStorageSupported(xt),
        t.uid || Ht.storage ? Ht.inited ? "Initialization ignored": t && t.appId ? (t.appId = t.appId.toUpperCase(), Ht.oss = "number" == typeof t.oss ? t.oss: 0, Ht.getErrorScene = t.getErrorScene, Ht.app = t.appName || "", Ht.from = t.from || Dt.getHostName(Wt.referrer), void Ct.USER_INIT_BASE_SETTINGS.split(",").forEach(function(e) {
            t.hasOwnProperty(e) && (pe[e] = t[e])
        })) : "Missing appId": Ut.hasStorage ? "Storage quota error": "Storage not support"
    }
    function _t(t) {
        if (Mt.shouldNotBeDestoryed() !== !1) {
            var e = yt(t);
            if (e) return Dt.tryThrow(e);
            ht(t),
            Dt.isDebug || B({
                url: Pt.appendEcho(Ut.protocol + "//" + Ct.HOST + "/echo"),
                method: "GET"
            },
            !0)
        }
    }
    function St() {
        return Ht.inited
    }
    function At() {
        I(),
        Ht.destroyed = !0
    }
    var Nt, Lt = {
        get reset() {
            return T
        },
        get cancel() {
            return I
        },
        get stop() {
            return E
        },
        get run() {
            return v
        },
        get set() {
            return O
        }
    },
    Dt = {
        get isDebug() {
            return zt
        },
        get noop() {
            return e
        },
        get isFunction() {
            return n
        },
        get isObject() {
            return r
        },
        get log() {
            return o
        },
        get tryThrow() {
            return Qt
        },
        get uuid() {
            return i
        },
        get extend() {
            return c
        },
        get attempt() {
            return Jt
        },
        get isLocalStorageSupported() {
            return u
        },
        get repeat() {
            return a
        },
        get padding() {
            return s
        },
        get aspect() {
            return f
        },
        get getHostName() {
            return g
        },
        get hiddenProperty() {
            return $t
        },
        get slice() {
            return d
        },
        get parseInt() {
            return l
        },
        get max() {
            return p
        },
        get jsonStringify() {
            return b
        },
        get jsonParse() {
            return m
        }
    },
    Rt = {
        get getOnlineInfo() {
            return y
        },
        get collect() {
            return _
        },
        get clear() {
            return S
        },
        get saveToStorage() {
            return x
        },
        get loadFromStorage() {
            return U
        },
        get addError() {
            return M
        },
        get addEvent() {
            return P
        }
    },
    wt = {
        get REQUEST_TIME_OUT() {
            return ee
        },
        get MAX_ONLINE_TIME() {
            return ne
        },
        get MIN_ONLINE_INTERVAL() {
            return re
        },
        get MIN_ONLINE_INTERVAL_DEBUG() {
            return oe
        },
        get UID_MIN_LENGTH() {
            return ie
        },
        get ASAP_TIMEOUT() {
            return ce
        },
        get ASAP_TIMEOUT_DEBUG() {
            return ue
        },
        get MAX_ERROR_COUNT() {
            return ae
        },
        get DEFAULT_AGE() {
            return se
        },
        get DEFAULT_GENDER() {
            return fe
        },
        get DEFAULT_ROLE_LEVEL() {
            return ge
        },
        get DEFAULT_NET_TYPE() {
            return de
        },
        get DEFAULT_PLATFORM() {
            return le
        }
    },
    Ct = {
        get HOST() {
            return De
        },
        get CREATE_TIME() {
            return Re
        },
        get EGRET_PREFIX() {
            return we
        },
        get LAYA_PREFIX() {
            return Ce
        },
        get COCOS_PREFIX() {
            return xe
        },
        get UNKNOW_ENGINE() {
            return Ue
        },
        get PARENT_KEY() {
            return Me
        },
        get EVENTS_KEY() {
            return Pe
        },
        get ERRORS_KEY() {
            return Ve
        },
        get CLIENT_KEY() {
            return Ge
        },
        get QUIT_SNAPSHOT() {
            return He
        },
        get LOGOUT_TIME() {
            return Fe
        },
        get API_PATH() {
            return ke
        },
        get PADDING_STRING() {
            return Be
        },
        get REQ_KEY() {
            return Xe
        },
        get USER_INIT_BASE_SETTINGS() {
            return qe
        },
        get ACCOUNT_RELATED_SETTINGS() {
            return Ke
        },
        get ACCOUNT_ROLE_SETTINGS() {
            return Ye
        },
        get EVT_COIN() {
            return ze
        },
        get EVT_ITEM() {
            return Qe
        },
        get EVT_LEVEL() {
            return Je
        },
        get EVT_MISSION() {
            return We
        },
        get EVT_TASK() {
            return $e
        },
        get EVT_PV() {
            return Ze
        }
    },
    xt = {
        get setUID() {
            return L
        },
        get getUID() {
            return D
        },
        get setItem() {
            return R
        },
        get getItem() {
            return w
        },
        get removeItem() {
            return C
        }
    },
    Ut = {
        get hasStorage() {
            return en
        },
        get isStandardBrowser() {
            return nn
        },
        get hasCookie() {
            return rn
        },
        get protocol() {
            return cn
        },
        get useXDR() {
            return un
        },
        get device() {
            return Ae
        }
    },
    Mt = {
        get isParamsValid() {
            return X
        },
        get shouldBeInited() {
            return q
        },
        get shouldBeLoggedIn() {
            return K
        },
        get shouldNotBeDestoryed() {
            return Y
        }
    },
    Pt = {
        get API_PATH() {
            return yn
        },
        get appendOnline() {
            return z
        },
        get appendEcho() {
            return Q
        }
    },
    Vt = {
        get setPollingDebounce() {
            return W
        }
    },
    Gt = {
        get engine() {
            return qt
        },
        get "default" () {
            return h
        }
    },
    Ht = {
        inited: !1
    },
    Ft = (1, eval)("this"),
    kt = Ft || {},
    Bt = kt.setTimeout,
    Xt = kt.clearTimeout,
    qt = {
        isEgret: !!kt.egret,
        isLayabox: !!kt.layabox,
        isCocos: !!kt.cc && !!kt.cc.game
    };
    qt.isEgret && (Bt = function(t, e) {
        kt.egret.setTimeout(t, kt, e)
    },
    Xt = function(t) {
        kt.egret.clearTimeout(t)
    });
    var Kt, Yt = Object.prototype.toString,
    zt = kt.DCAGENT_DEBUG_OPEN,
    Qt = zt ?
    function(t) {
        throw new Error(t)
    }: function(t) {
        o(t)
    },
    Jt = zt ?
    function(t, e, r) {
        return n(t) ? t.apply(e, r) : void 0
    }: function(t, e, r) {
        if (n(t)) try {
            return t.apply(e, r)
        } catch(i) {
            o("exec error for function:\n " + t.toString())
        }
    },
    Wt = Ft.document || {},
    $t = "hidden" in Wt ? "hidden": "webkitHidden" in Wt ? "webkitHidden": "mozHidden" in Wt ? "mozHidden": "msHidden" in Wt ? "msHidden": null,
    Zt = [],
    te = 0;
    t.version = 26;
    var ee = 3e4,
    ne = 172800,
    re = 40,
    oe = 15,
    ie = 32,
    ce = 5e3,
    ue = 2e3,
    ae = 100,
    se = 0,
    fe = 0,
    ge = 0,
    de = 3,
    le = 0,
    pe = {
        accountId: "",
        accountType: "",
        age: wt.DEFAULT_AGE,
        appId: "",
        appVersion: "",
        brand: "",
        channel: "",
        customDeviceId: "",
        gameServer: "",
        gender: wt.DEFAULT_GENDER,
        idfa: "",
        imei: "",
        lonLat: "",
        mac: "",
        netType: wt.DEFAULT_NET_TYPE,
        operator: "",
        osVersion: "",
        platform: wt.DEFAULT_PLATFORM,
        resolution: "",
        roleClass: "",
        roleId: "",
        roleLevel: wt.DEFAULT_ROLE_LEVEL,
        roleRace: "",
        simCardOp: "",
        uid: "",
        ver: t.version
    },
    be = [],
    me = 0,
    je = wt.DEFAULT_PLATFORM,
    Te = kt.screen || {},
    Ie = Te.width && Te.width + "*" + Te.height,
    Ee = "0*0";
    Ie || (Ie = Ee);
    var ve = "",
    Oe = "",
    he = kt.navigator && kt.navigator.userAgent || "";
    if (!he) {
        var ye = ["ios", "android"];
        if (qt.layabox) {
            var _e = kt.layabox.getDeviceInfo() || {};
            Ie = _e.resolution || Ee,
            Oe = _e.phonemodel,
            je = ye.indexOf(_e.os.toLowerCase()),
            ve = (_e.os + " " + _e.osversion).toLowerCase()
        } else if (qt.cocos) {
            var Se = kt.cc.view.getViewPortRect() || {};
            Ie = Se.width + "*" + Se.height,
            je = ye.indexOf(kt.cc.sys.os.toLowerCase())
        } - 1 === [0, 1, 2, 3].indexOf(je) && (je = wt.DEFAULT_PLATFORM)
    }
    var Ae = {
        resolution: Ie,
        brand: Oe,
        osVersion: ve,
        platform: je
    };
    for (var Ne in Ae) pe[Ne] = pe[Ne] || Ae[Ne];
    var Le, De = "rd.gdatacube.net",
    Re = "dcagent_create_time",
    we = "EGRET",
    Ce = "LAYA",
    xe = "COCOS",
    Ue = "UE",
    Me = "dcagent_parent_id",
    Pe = "dcagent_client_events",
    Ve = "dcagent_client_errors",
    Ge = "dcagent_client_id",
    He = "dcagent_snapshot",
    Fe = "dc_p_lo",
    ke = "/dc/hh5/sync",
    Be = "0A",
    Xe = "DE_EVENT_OSS",
    qe = "appId,appVersion,brand,channel,customDeviceId,idfa,imei,lonLat,mac,netType,operator,osVersion,platform,simCardOp,uid",
    Ke = "accountId,accountType,age,gender,gameServer",
    Ye = "roleId,roleRace,roleClass,roleLevel",
    ze = "DE_EVENT_COIN_ACTION",
    Qe = "DE_EVENT_ITEM_ACTION",
    Je = "DE_EVENT_LEVELUP",
    We = "DE_EVENT_GUANKA_ACTION",
    $e = "DE_EVENT_TASK_ACTION",
    Ze = "DE_EVENT_PV",
    tn = {
        get: function(t) {
            var e = "(^|)\\s*" + t + "=([^\\s]*)",
            n = Wt.cookie.match(new RegExp(e));
            return n && n.length >= 3 ? decodeURIComponent(n[2]) : null
        },
        set: function(t, e, n, r, o, i) {
            var c;
            n && (c = new Date, c.setTime(c.getTime() + 864e5 * n));
            var u = n ? " expires=" + c.toGMTString() : "",
            a = " path=" + (o || "/"),
            s = r ? " domain=" + r: "",
            f = i ? " secure": "";
            Wt.cookie = t + "=" + encodeURIComponent(e) + u + a + s + f
        },
        remove: function(t, e, n) {
            tn.set(t, "", -1, e, n)
        }
    },
    en = !!kt.localStorage || qt.isEgret || qt.isCocos || qt.isLayabox,
    nn = A(),
    rn = nn && "cookie" in Wt,
    on = Ft.location || {},
    cn = "https:" === on.protocol ? "https:": "http:",
    un = !!kt.XDomainRequest,
    an = Ut.hasCookie ? tn: {
        get: Dt.noop,
        set: Dt.noop
    },
    sn = an;
    Le = qt.isEgret ? kt.egret.localStorage: qt.isCocos ? kt.cc.sys.localStorage: en ? kt.localStorage: {
        getItem: e,
        setItem: e,
        removeItem: e
    };
    var fn = Le,
    gn = 0,
    dn = !0,
    ln = Ut.useXDR ?
    function() {
        return new kt.XDomainRequest
    }: function() {
        return new kt.XMLHttpRequest
    },
    pn = qt.isCocos ? F: ln,
    bn = pn(),
    mn = !0;
    try {
        bn.contentType = "text/plain; charset=UTF-8"
    } catch(jn) {
        mn = !1
    }
    var Tn = Ut.useXDR ?
    function(t, e) {
        mn && (t.contentType = e)
    }: function(t, e) {
        t.setRequestHeader("Content-Type", e)
    };
    try {
        bn.timeout = 1
    } catch(jn) {
        dn = !1
    }
    var In = function() {
        return kt.XMLHttpRequest || qt.isCocos ? k: qt.isEgret ? H: (Dt.log("XMLHttpRequest not found"), Dt.noop)
    } ();
    t.report;
    var En, vn, On = 0,
    hn = Date.now() - wt.ASAP_TIMEOUT,
    yn = Ut.protocol + "//" + Ct.HOST + Ct.API_PATH,
    _n = {
        login: $,
        getUid: G,
        onEvent: V
    },
    Sn = {
        onCoinGet: bt,
        onCoinUse: pt,
        onItemBuy: lt,
        onItemProduce: dt,
        onItemUse: gt,
        onLevelUp: ft,
        onMissionFinished: st,
        onMissionUnfinished: at,
        onPayment: ut,
        onTaskFinished: ct,
        onTaskUnfinished: it,
        setAccountType: ot,
        setAge: rt,
        setGameServer: nt,
        setGender: et,
        setRoleInfo: Z,
        createRole: tt
    },
    An = [Mt.shouldNotBeDestoryed, Mt.shouldBeInited],
    Nn = [Mt.shouldNotBeDestoryed, Mt.shouldBeLoggedIn],
    Ln = [function() {
        return Vt.setPollingDebounce()
    }];
    for (vn in _n) t[vn] = Dt.aspect(_n[vn], An, "onEvent" === vn && Ln);
    for (vn in Sn) t[vn] = Dt.aspect(Sn[vn], Nn, "onPayment" !== vn && Ln);
    t.init = _t,
    t.isReady = St,
    t.destroy = At;
    var Dn = kt.DCAgentObject;
    if (Dn) {
        var Rn = kt[Dn];
        if (Dt.isFunction(Rn)) {
            var wn = Rn.cache;
            wn.length && (wn.forEach(function(e) {
                Dt.attempt(t[e[0]], t, Dt.slice(e, 1))
            }), wn.length = 0)
        }
    }
    var Cn = {
        get isNew() {
            var t = Ht.loginTime || Ht.initTime;
            return Ht.createTime === t
        },
        get initTime() {
            return Ht.initTime
        },
        get createTime() {
            return Ht.createTime
        },
        get loginTime() {
            return Ht.loginTime
        },
        get lastLogoutTime() {
            return parseInt(xt.getItem(Ct.LOGOUT_TIME))
        },
        get reportCount() {
            return On
        },
        get reportFailedCount() {
            return gn
        }
    };
    t.state = Ht,
    t.player = Cn
});