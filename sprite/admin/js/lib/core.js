define(['/sprite/js/lib/zepto.js'], function(){
	(function(e) {
	    e._parseJSON = e.parseJSON,
	    e.parseJSON = function(t) {
	        return e._parseJSON(t.replace(/(^|[\r\n])\t*/g, "").replace(/\t/g, "\\t").replace(/\\x3c/g, "<").replace(/\\x27/g, "'"))
	    }
	})(Zepto),
	function(e) {
		if (window.EMF) return;
		var d={},n = window.EMF = {
	        delay: function(i, f, t) {
			    if(d[i]){window.clearTimeout(d[i]),delete d[i]}
			    return d[i] = window.setTimeout(function() {f(),delete d[i]},t)
	        },
	        PageController: {}
		}
	}(Zepto),
	function(e, t) {
	    var n, r, o,
	    i = e.tmpl,
		s = {success: "suc", failure: "err", tip: "tip"};
	    n = t("<div></div>").html('<div id="tips"><div class="mask"></div><div class="tipContent"></div></div>').children();
	    e.tips = {
	        show: function(e, f, i) {
			    n.appendTo("body");
	            i === undefined && (i = 2e3);
	            if (e && e.length) {
	                var u = f && f.type && s[f.type] || f.success,
			        r = t(".tipContent", n);
	                n.hide(),
	                r.attr("class", "tipContent " + u).html(e);
					var x = window.innerWidth,g = window.innerHeight;
						n.show();
						wl=(x - r.width())/2;
						wt=(g - r.height())*0.360;
						r.css("left",wl),r.css("top",wt);
			        if(i!==0){
				        setTimeout(function() {
							n.animate({opacity: 0}, 500, "ease-out", function() {n.css({opacity: "1"}).hide()})
				        },i)
				    }
	            }
	        }
	    }
	}(window.EMF, Zepto),
	function(e, t) {
	    var i, s, o,
	    i = t("<div></div>").html('<div class="oa_mask" style="display:none"><img src="/sprite/images/loading11.gif" id="lodingImg" style="width:60px;height:60px;"/></div>').children()
	    s = t("img", i)
		e.load = {
			show: function(){
				i.appendTo("body")
				s.css({"position":"absolute"});
			    //var h=(t(window).height()-s.height())/2,w=(t(window).width()-s.width())/2;
			    var h=(t(window).height()-60)/2,w=(t(window).width()-60)/2;
			    h = h < 0 ? 0 : h,w = w < 0 ? 0 : w;
			    s.css({top:h, left:w})
				i.animate({opacity: 0.25}, 800, 'ease-out')
				i.css("display","block");
			    //i.fadeIn(800)
			},
	   		hide : function() {
				i.fadeOut()
			}
		}
	    e.ajax = function(n, r, i, s, o, u, a) {
	        a = a == 0 ? a : !0,
	        u = u || "json",
	        r = r || {},
	        r.ajax = 1
	        t.ajax({
	            type: "POST",
	            url: n,
	            async: a,
	            data: r,
	            dataType: u,
	            scriptCharset: "utf8",
		        /*beforeSend : function(){
		        	e.load.show()
		        	
		        },*/
	            success: function(d) {
	                typeof d == "string" && (d = t.parseJSON(d));
	                i && i(d, r)
	            },
	            error: function(e) {
	                s && s(e)
	            },
	            /*complete: o || function() {
	            	e.load.hide()
	            }*/
	        })
	    }
	}(window.EMF, Zepto),
	function(e, t, n) {
		var i,s;
	    window.W == n && (window.W = {}),
	    W.ajax = function(n, r, i, s, o, u, a) {e.ajax(n, r, i, s, o, u, a)},
	    W.suc = function(t, i) {e.tips.show(t, {type: "success"}, i)},
	    W.err = function(t, i) {e.tips.show(t, {type: "failure"}, i)},
	    W.tip = function(t, i) {e.tips.show(t, {type: "tip"}, i)}
		W.loading = function(t, i) {e.load.show()}
		W.hide = function(t, i) {e.load.hide()}
	} (EMF, Zepto)
})