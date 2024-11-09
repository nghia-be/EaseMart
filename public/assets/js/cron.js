(()=>{
        var O = class {
                constructor() {
                    this.minute = new f(c.Minute,0,59),
                        this.hour = new f(c.Hour,0,23),
                        this.day_of_month = new f(c.DayOfMonth,1,31),
                        this.month = new f(c.Month,1,12),
                        this.day_of_week = new f(c.DayOfWeek,0,6)
                }
                [Symbol.iterator]() {
                    return [this.minute, this.hour, this.day_of_month, this.month, this.day_of_week][Symbol.iterator]()
                }
                hasErrors() {
                    return !Array.from(this).every(t=>t.errors.length == 0)
                }
            }
            , c = (s=>(s[s.Minute = 0] = "Minute",
                s[s.Hour = 1] = "Hour",
                s[s.DayOfMonth = 2] = "DayOfMonth",
                s[s.Month = 3] = "Month",
                s[s.DayOfWeek = 4] = "DayOfWeek",
                s))(c || {})
            , f = class {
                constructor(t, n, r) {
                    this.type = t,
                        this.lower = n,
                        this.upper = r,
                        this.tokens = [],
                        this.errors = [],
                        this.warnings = []
                }
                get list() {
                    let t = [].concat(...this.tokens.map(n=>n.list()));
                    return Array.from(new Set(t)).sort((n,r)=>n - r)
                }
                isAll() {
                    let t = this.list;
                    return t[0] === this.lower && t[this.upper - this.lower] === this.upper
                }
                isPresent() {
                    return this.length > 0 && this.errors.indexOf("missing") == -1
                }
                isFullWidth(t) {
                    return t instanceof u ? this.lower == t.lower && this.upper == t.upper : !1
                }
                get length() {
                    return this.tokens.reduce((t,n)=>t + n.length, 0)
                }
            }
            , G = {
                jan: 1,
                feb: 2,
                mar: 3,
                apr: 4,
                may: 5,
                jun: 6,
                jul: 7,
                aug: 8,
                sep: 9,
                oct: 10,
                nov: 11,
                dec: 12,
                [Symbol.iterator]: function*() {
                    for (let e of Object.keys(this))
                        yield[e, this[e]]
                }
            }
            , Q = {
                sun: 0,
                mon: 1,
                tue: 2,
                wed: 3,
                thu: 4,
                fri: 5,
                sat: 6,
                7: 0,
                [Symbol.iterator]: function*() {
                    for (let e of Object.keys(this))
                        yield[e, this[e]]
                }
            }
            , X = [null, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
            , F = class {
                constructor(t) {
                    this.spec = t
                }
            }
            , u = class extends F {
                constructor(n, r, i, s=1) {
                    super(n);
                    this.step = 1;
                    this.lower = r,
                        this.upper = i,
                        this.step = s
                }
                list() {
                    let n = [];
                    for (var r = this.lower; r <= this.upper; r += this.step)
                        n.push(r);
                    return n
                }
                get length() {
                    return Math.floor((this.upper - this.lower) / this.step) + 1
                }
                subset() {
                    return !this.spec.startsWith("*")
                }
            }
            , p = class extends F {
                constructor(n, r) {
                    super(n);
                    this.value = r
                }
                list() {
                    return [this.value]
                }
                get length() {
                    return 1
                }
                subset() {
                    return !0
                }
            }
        ;
        function K(e) {
            switch (e[0]) {
                case "@yearly":
                    return ["0", "0", "1", "1", "*", ...e.slice(1)];
                case "@annually":
                    return ["0", "0", "1", "1", "*", ...e.slice(1)];
                case "@monthly":
                    return ["0", "0", "1", "*", "*", ...e.slice(1)];
                case "@weekly":
                    return ["0", "0", "*", "*", "0", ...e.slice(1)];
                case "@daily":
                    return ["0", "0", "*", "*", "*", ...e.slice(1)];
                case "@midnight":
                    return ["0", "0", "*", "*", "*", ...e.slice(1)];
                case "@hourly":
                    return ["0", "*", "*", "*", "*", ...e.slice(1)]
            }
            return e
        }
        function Y(e) {
            if (e == null)
                return e;
            for (let[t,n] of G)
                e = e.replace(new RegExp(t,"ig"), n);
            return e
        }
        function Z(e) {
            if (e == null)
                return e;
            for (let[t,n] of Q)
                e = e.replace(new RegExp(t,"ig"), n);
            return e
        }
        function g(e, t) {
            if (t == "" || t == null) {
                e.errors.push("missing");
                return
            }
            t.split(",").forEach(r=>{
                    let s = r.replace("*", e.lower + "-" + e.upper).match(/\d+|./g);
                    if (s == null || isNaN(Number(s[0]))) {
                        e.errors.push("invalid");
                        return
                    }
                    if (s.length === 1) {
                        let a = Number(s[0]);
                        e.tokens.push(new p(r,a));
                        return
                    }
                    if (s.length === 3) {
                        var o = Number(s[0])
                            , l = Number(s[2]);
                        if (isNaN(o) || isNaN(l)) {
                            e.errors.push("invalid");
                            return
                        }
                        switch (s[1]) {
                            case "-":
                                if (l < o) {
                                    e.errors.push("invalid");
                                    return
                                }
                                e.tokens.push(new u(r,o,l));
                                return;
                            case "/":
                                if (l == 0) {
                                    e.errors.push("invalid_step");
                                    return
                                }
                                e.warnings.push("non-standard"),
                                    e.tokens.push(new u(r,o,e.upper,l));
                                return;
                            default:
                                e.errors.push("invalid");
                                return
                        }
                    }
                    if (s.length === 5) {
                        var m = Number(s[0])
                            , d = Number(s[2])
                            , h = Number(s[4]);
                        if (isNaN(m) || isNaN(d) || isNaN(h)) {
                            e.errors.push("invalid");
                            return
                        }
                        if (s[1] != "-" || s[3] != "/") {
                            e.errors.push("invalid");
                            return
                        }
                        if (d < m) {
                            e.errors.push("invalid");
                            return
                        }
                        if (h === 0) {
                            e.errors.push("invalid_step");
                            return
                        }
                        e.tokens.push(new u(r,m,d,h));
                        return
                    }
                    e.errors.push("invalid")
                }
            );
            let n = e.list;
            return (n[0] < e.lower || n[n.length - 1] > e.upper) && e.errors.push("out_of_range"),
                e.tokens = e.tokens.filter((r,i)=>{
                        if (r instanceof p) {
                            for (let s = i + 1; s < e.tokens.length; s++)
                                if (e.tokens[s].list().indexOf(r.value) >= 0)
                                    return !1
                        }
                        return !0
                    }
                ),
                e.tokens = e.tokens.sort((r,i)=>r instanceof p && i instanceof p ? r.value - i.value : r instanceof u && i instanceof u ? r.lower - i.lower : r instanceof u ? 1 : -1),
                e.errors = Array.from(new Set(e.errors)),
                e.warnings = Array.from(new Set(e.warnings)),
                e
        }
        function C(e) {
            var t = new O
                , n = e.trim().split(/\s+/).filter(r=>r);
            n = K(n),
            n.length > 5 && (t.command = n.slice(5).join(" "),
                n = n.slice(0, 5)),
                n[3] = Y(n[3]),
                n[4] = Z(n[4]),
                g(t.minute, n[0]),
                g(t.hour, n[1]),
                g(t.day_of_month, n[2]),
                g(t.month, n[3]),
                g(t.day_of_week, n[4]);
            for (let r of t)
                if (!r.isPresent())
                    return t;
            if (t.union_days = !(n[2][0] == "*" || n[4][0] == "*"),
            t.day_of_month.length < 3) {
                let r = t.day_of_month.list;
                t.month.list.every(s=>r.every(o=>o > X[s])) && t.day_of_month.errors.push("impossible")
            }
            return t
        }
        var $ = [null, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
            , ee = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
            , te = {
                [0]: D,
                [1]: A,
                [2]: x,
                [3]: M,
                [4]: E
            }
            , H = class {
            }
            , N = class {
                constructor() {
                    this.minutes = "";
                    this.hours = "";
                    this.dayOfMonth = "";
                    this.months = "";
                    this.dayOfWeek = "";
                    this.full = []
                }
            }
        ;
        function D(e) {
            return ":" + ("0" + e).slice(-2)
        }
        function A(e) {
            let t = e < 12 ? "am" : "pm";
            return e = e % 12 || 12,
            e + t
        }
        function ne(e, t) {
            let n = e < 12 ? "am" : "pm";
            return e = e % 12 || 12,
            e + D(t) + n
        }
        function M(e) {
            return $[e]
        }
        function E(e) {
            return ee[e]
        }
        function x(e) {
            switch (e > 20 ? e % 100 % 10 : e) {
                case 1:
                    return e + "st";
                case 2:
                    return e + "nd";
                case 3:
                    return e + "rd";
                default:
                    return e + "th"
            }
        }
        function v(e) {
            if (e == null)
                return "";
            switch (e.length) {
                case 0:
                    return "";
                case 1:
                    return e[0];
                case 2:
                    return e.join(" and ")
            }
            return e.slice(0, -1).join(", ") + ", and " + e.slice(-1)
        }
        function re(e, t) {
            if (t.isAll()) {
                e.full.push(e.minutes = "every minute");
                return
            }
            e.full.push("at"),
                e.minutes = b(t, "minute", D, !1),
                e.full.push(e.minutes)
        }
        function I(e, t) {
            if (t.isAll())
                return;
            if (t.length < 3) {
                e.hours = v(t.list.map(A)),
                    e.full.push("during", e.hours);
                return
            }
            let n = "during"
                , r = t.tokens[0];
            r instanceof u && (r.step > 1 ? n = "in" : r.subset() && (n = "from")),
                e.hours = b(t, "hour", A),
                e.full.push(n, e.hours)
        }
        function ie(e, t) {
            t.isAll() || (e.full.push("on the"),
                e.dayOfMonth = b(t, "day", x),
                e.full.push(e.dayOfMonth))
        }
        function se(e, t) {
            if (t.isAll())
                return;
            if (t.length < 3) {
                e.months = v(t.list.map(M)),
                    e.full.push("of", e.months);
                return
            }
            let n = "of";
            t.tokens[0]instanceof u && t.tokens[0].subset() && (n = "from"),
                e.months = b(t, "month", M),
                e.full.push(n, e.months)
        }
        function R(e, t) {
            t.isAll() || (e.full.push("on"),
                t.length <= 3 ? e.dayOfWeek = v(t.list.map(E)) : e.dayOfWeek = b(t, "day", E),
                e.full.push(e.dayOfWeek))
        }
        function oe(e, t, n) {
            if (t.length == 1 && n.length == 1) {
                let r = t.list[0]
                    , i = n.list[0];
                return e.minutes = ":" + r,
                    e.hours = "" + i,
                    e.full.push("at", ne(i, r))
            }
            if (t.length == 1 && t.list[0] == 0) {
                e.minutes = "the start of",
                    e.full.push("at", e.minutes),
                    n.isAll() ? (e.hours = "every hour",
                        e.full.push(e.hours)) : I(e, n);
                return
            }
            re(e, t),
                I(e, n)
        }
        function ue(e, t, n, r) {
            if (!(t.isAll() && n.isAll() && r.isAll())) {
                if (t.length == 1 && n.length == 1) {
                    e.dayOfMonth = x(t.list[0]),
                        e.months = M(n.list[0]),
                        e.full.push("on", e.months, e.dayOfMonth),
                        R(e, r);
                    return
                }
                ie(e, t),
                    se(e, n),
                    R(e, r)
            }
        }
        function b(e, t, n, r=!0) {
            let i = e.tokens.map(s=>s.length == 1 ? n(s.list()[0]) : ae(s, t, n, r));
            return v(i)
        }
        function ae(e, t, n, r=!0) {
            var i = [];
            return e.step === 1 && !e.subset() ? "every " + t : (e.step > 1 && (i.push("every"),
                r ? i.push(x(e.step), t) : i.push(String(e.step), t + "s")),
            e.subset() && (i.length > 0 && i.push("from"),
                i.push(n(e.lower), "through", n(e.upper))),
                i.join(" "))
        }
        function q(e) {
            let t = new H;
            if (e == "@reboot")
                return t.description = "After system reboot",
                    t;
            let n = C(e);
            if (t.schedule = n,
                n.hasErrors())
                return n.day_of_month.errors.indexOf("impossible") != -1 ? t.description = "Never" : t.description = "",
                    t;
            let r = new N;
            oe(r, n.minute, n.hour),
                ue(r, n.day_of_month, n.month, n.day_of_week);
            let i = r.full.filter(s=>s && s.length).join(" ");
            return i = i.replace("at every", "every").replace(/ of (of|in|from|during) /, " of ").replace("on the every", "on every"),
                i = i.charAt(0).toUpperCase() + i.slice(1),
                t.description = i,
                t
        }
        function P(e) {
            let t = te[e.type];
            return v(e.list.map(n=>t(n)))
        }
        function U(e, t, n) {
            var r = e.history
                , i = document
                , s = navigator || {}
                , o = localStorage
                , l = encodeURIComponent
                , m = r.pushState
                , d = function() {
                return o.cid || (o.cid = Math.random().toString(36)),
                    o.cid
            }
                , h = function(a, y, _, S, J, V, L) {
                var W = "https://www.google-analytics.com/collect"
                    , j = function(w) {
                    var T = [];
                    for (var k in w)
                        w.hasOwnProperty(k) && w[k] !== void 0 && T.push(l(k) + "=" + l(w[k]));
                    return T.join("&")
                }({
                    v: "1",
                    ds: "web",
                    aip: n.anonymizeIp ? 1 : void 0,
                    tid: t,
                    cid: d(),
                    t: a || "pageview",
                    sd: n.colorDepth && e.screen.colorDepth ? e.screen.colorDepth + "-bits" : void 0,
                    dr: i.referrer || void 0,
                    dt: i.title,
                    dl: i.location.origin + i.location.pathname + i.location.search,
                    ul: n.language ? (s.language || "").toLowerCase() : void 0,
                    de: n.characterSet ? i.characterSet : void 0,
                    sr: n.screenSize ? (e.screen || {}).width + "x" + (e.screen || {}).height : void 0,
                    vp: n.screenSize && e.visualViewport ? (e.visualViewport || {}).width + "x" + (e.visualViewport || {}).height : void 0,
                    ec: y || void 0,
                    ea: _ || void 0,
                    el: S || void 0,
                    ev: J || void 0,
                    exd: V || void 0,
                    exf: L !== void 0 && !L ? 0 : void 0
                });
                if (s.sendBeacon)
                    s.sendBeacon(W, j);
                else {
                    var z = new XMLHttpRequest;
                    z.open("POST", W, !0),
                        z.send(j)
                }
            };
            r.pushState = function(a) {
                return typeof r.onpushstate == "function" && r.onpushstate({
                    state: a
                }),
                    setTimeout(h, n.delay || 10),
                    m.apply(r, arguments)
            }
                ,
                h(),
                e.ma = {
                    trackEvent: function(a, y, _, S) {
                        return h("event", a, y, _, S)
                    },
                    trackException: function(a, y) {
                        return h("exception", null, null, null, null, a, y)
                    }
                }
        }
        U(window, "UA-105924607-4", {
            anonymizeIp: !0,
            colorDepth: !0,
            characterSet: !0,
            screenSize: !0,
            language: !0
        });
        var le = {
            [0]: ".minute",
            [1]: ".hour",
            [2]: ".day-of-month",
            [3]: ".month",
            [4]: ".day-of-week"
        };
        function he(e, t) {
            if (!!t) {
                if (e.isAll()) {
                    t.innerText = "all";
                    return
                }
                t.innerText = P(e)
            }
        }
        function B(e, t=!1) {
            let n = document.querySelector("div.editor .description .text")
                , r = q(e);
            if (t) {
                let i = "";
                r.description,
                    history.replaceState({}, "", i)
            }
            n != null && (n.innerHTML = r.description);
            for (let i of r.schedule) {
                let s = le[i.type]
                    , o = document.querySelector(".breakdown " + s + ".text");
                o != null && he(i, o)
            }
        }
        document.addEventListener("DOMContentLoaded", ()=>{
                let e = document.querySelector(".schedule input")
                    , t = document.querySelector(".schedule .copy");
                if (document.querySelectorAll("a[href*='deadmanssnitch.com']").forEach(r=>r.addEventListener("click", ()=>window.fathom.trackGoal("UEQEOATT", 0))),
                    !e)
                    return;
                e.addEventListener("keyup", r=>{
                        let i = r.target;
                        B(i.value, !0)
                    }
                )
                let n = window.location.hash;
                n && (e.value = n.replace(/^#|_/g, " ").trim()),
                    B(e.value)
            }
        );
    }
)();
