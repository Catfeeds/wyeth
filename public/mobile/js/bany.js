Function.prototype.bind = function(){
	var a = Array.prototype.slice.call(arguments), m = this, o = a.shift();
	return function(){
		return m.apply(o == null ? this : o, a.concat( Array.prototype.slice.call(arguments)));
	}
};

String.prototype.trim = function(){
//	return this.replace(/^\s+/,"").replace(/\s\s*$/, "");
	return this.replace(/^\s+|\s+$/g, "");
};
/**
* 字符串转Date，若有format则返回Date格式化后的字符串
*/
String.prototype.toDate = function(format){
    if(this.toString()==='') return '';
    var str;
    if(this.indexOf('.') != -1){
        str = this.substring(0,this.indexOf('.'));
    }else{
        str = this.toString();
    }
    var reg = /-/g;
    str = str.replace(reg,'/');
    if(str.indexOf('T')){
        str = str.replace('T',' ');
    }
    if(format){
        return new Date(str).format(format);
    }else{
        return new Date(str);
    }
}
/**
 * 去除HTML编码 生成可以在html文档显示的字符
 */
String.prototype.htmlEncode = function(){
    // var reg = /&|<|>|'|"/g;
    // return this.replace(reg,'');
    return this.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&#34;").replace(/\'/g,"&#39;");
    //return this.replace(/&/g,"&amp;").replace(/>/g,"&gt;").replace(/\"/g,"&#34;").replace(/\'/g,"&#39;");
};

/**
 * 返回字字符串的Unicode编码的长度
 */
String.prototype.uniLength = function(){
	return this.replace(/[^\x00-\xff]/g, "**").length;
};

//截取字符串中len长度的子字符串 Unicode编码
String.prototype.uniSub = function(start,len){
	var s = this.replace(/[*]/g, " ").replace(/[^\x00-\xff]/g, "**");
	len = s.substr(start, len).replace(/[*]{2}/g, " ").replace(/[*]{2}/g, "").length;
	start = s.slice(0, start).replace(/[*]{2}/g, " ").replace(/[*]{2}/g, "").length;
	return this.substr(start, len);
};
//截取左边 len长度的字符串  Unicode编码
String.prototype.uniLeft = function(len,omit){
	omit || (omit = "");
	return this.uniLength()>len?this.uniSub(0, len-omit.uniLength())+omit:this;
};

/**
 * 时间格式化
 YYYY  ： 四位年份
 YY      ： 二位年份
 MM    :  两位月份
 M       ： 月份
 DD      ： 日期两位
 D         ： 日期
 hh       ： 小时两位
 h         ： 小时
 mm      ： 分钟两位
 m         ： 分钟
 ss          ： 秒两位
 s          ：秒
 */
Date.prototype.format = function(join){
	return (join || "YYYY-MM-DD").replace(/YYYY/g,this.getFullYear())
		.replace(/YY/g,String(this.getYear()).slice(-2))
		.replace(/MM/g,("0" + (this.getMonth() + 1)).slice(-2))
		.replace(/M/g,this.getMonth() + 1)
		.replace(/DD/g,("0" + this.getDate()).slice(-2))
		.replace(/D/g,this.getDate())
		.replace(/hh/g,("0" + this.getHours()).slice(-2))
		.replace(/h/g,this.getHours())
		.replace(/mm/g,("0" + this.getMinutes()).slice(-2))
		.replace(/m/g,this.getMinutes())
		.replace(/ss/g,("0" + this.getSeconds()).slice(-2))
		.replace(/s/g,this.getSeconds());
};
//扩展
var util = {};
void function(){
	//扩展object
	function extra(k,v){
		if(typeof k == "string"){
			this[k] = v;
			return this;
		}
		var ag = Array.prototype.slice.call(arguments), m,n;
		while(ag.length){
			m = ag.shift();
			//alert(m.onCensor);
			for(n in m){
				this[n] = m[n];
			}
		}
		return this;
	}
	//简单的对 最后一个参数的扩展（将最后一个参数前面的参数属性扩展到最后一个参数上)
	//例如：
	//var c = util.extra(a,b,{}); 产生一个c，将包含a和b的所有属性，但不对a和b产生印象
	util.extra = function(){
		return extra.apply(Array.prototype.pop.call(arguments),arguments);
	};

	//扩展this
	function extend(){
		var ag = Array.prototype.slice.call(arguments),m;
		if(typeof this == "function"){
			this.prototype.extend = extra;
			this._inits_ = [];
			while(ag.length){
				m = ag.shift();
				if(typeof m == "function"){
					extra.call(this,m);
					this._inits_.unshift(m);
					m = m.prototype;
				}
				extra.call(this.prototype,m);
			}
		}
		else{
			while(m = ag.shift()){
				if(typeof m == "function"){
					try{
						m = new m();
					}catch(e){
						m = m.prototype;
					}
				}
				extra.call(this,m);
			}
		}
		return this;
	}
	//继承与扩展
	//将前面的object扩展到最后一个函数的原型链上 用于类的继承
	//例如 ，下面讲到的 Ajax类就是继承了Event类
	util.extend = function(){
		return extend.apply(Array.prototype.pop.call(arguments),arguments);
	};
}();

void function(){
	function push(arr,v){
		arr.push(v);
		return v;
	}
	function append(obj,v,k){
		obj[k] = v;
	}
	function back(){
		return arguments[1];
	}

	//o 需要循环的 数组或者对象或者list对象（argumengs，nodeList对象）
	//fn 循环是执行的函数
	//exe fn返回的数据将写入exe
	//scope fn函数固定的this指向
	util.forEach = function(o, fn, exe, scope){
		if(scope == null){
			scope = this;
		}
		if(o){
			var doExe = exe? exe.push ? push : append : back,m;
			if(typeof o == "object" && typeof o.length == "number" && o.length >= 0){
				for(var i = 0; i < o.length; i += 1){
					m = fn.call(scope, o[i], i);
					if(m === false){
						break;
					}
					doExe( exe,m, i);
				}
			}
			else{
				for(var n in o){
					m = fn.call(scope, o[n], n);
					if(m === false){
						break;
					}
					doExe( exe,m, n);
				}
			}
		}
		return exe || scope;
	}
}();

void function(){
	var iData = {};
	function get(str){
		var data = {};
		util.forEach(str.replace(/^[\s#\?&]+/, "").replace(/&+/, "&").split(/&/),function(v){
			var s = v.split("=");
			if(s[0]!=""){
				s[1] = decodeURIComponent(s[1] || "");
				if(data[s[0]] == null){
					data[s[0]] = s[1];
				}
				else if(data[s[0]].push){
					data[s[0]].push(s[1]);
				}
				else{
					data[s[0]] = [data[s[0]], s[1]];
				}
			}
		});
		return data;
	}
	util.parseURI = function(str){
		if(iData[str]){
			return iData[str];
		}
		return iData[str] = get(str);
	};
	//获取页面？后面的参数
	//x.html?a=1&b=2
	//util.getSearch()  => {a:1,b:2}
	//util.getSearch("a") ==> 1
	util.getSearch = function(str){
		var o = util.parseURI(document.location.search);
		return str == null?o:o[str];
	};

	//将obj对象转换为url参数形式
	//obj = {a:1,b:2}
	//util.stringifyURI(obj)  => a=1&b=2
	//k 默认为 &
	util.stringifyURI = function(obj,k){
		if(!obj){
			return "";
		}
		var rv = [];
		util.forEach(obj,function(m,n){
			if(Object.prototype.toString.call(m) === "[object Array]"){
				for(var i=0;i<m.length;i+=1){
					rv.push(n + "=" + encodeURIComponent(m[i]));
				}
			}
			else{
				rv.push(n + "=" + encodeURIComponent(m));
			}
		});
		return rv.join(k || "&");
	};
}();

Math.times = function(){

    var _list = arguments,
        times = 1,
        result = 1;
    for(var i =0;i<_list.length;i++){
        var y = (_list[i]+'').split(".")[1];
        if(y){
            times *= Math.pow(10,y.length) ;
//            var _times = Math.pow(10,y.length);
//            result = (result * (_list[i] * _times));
            result = result * (_list[i].toString().split(".").join("") * 1);

        }else{
            result = (result * (_list[i]));
        }

    }

    return result/times;

}
/**
 * Math添加精确加法
 * x,y = number
 */
Math.plus  = function(){

    var _list = arguments,
        times = 1,
        result = 0;
    //先获取最大倍数
    var len = _list.length;
    for(var i=0;i<len;i++){
        var y = (_list[i]+'').split(".")[1];
        if(y && times < y.length){
            times = y.length;
        }
    }

    times = Math.pow(10,times) ;

    //整数计算
    for(var i=0;i<len;i++){

        result = (result + (_list[i] * times));

    }

    return result/times;

}


//使用页面模板类
/*
	getLocal("text/tpl") 会将html页面中 type为text/tpl的script节点中的字符串作为模板来使用
	如下是定义了模板为a
	<script type="text/tpl">
	<!--^a-->
		这里是模板A的数据
		{#*} 这个替换为传入参数
		{#x} 这个替换为传入参数的x属性值
		{#x.z} 这个替换为传入参数的x属性值的z属性值
		{#x|zz} 这个是将传入参数的x属性值作为第一个参数传入format中的zz函数
		{#x|zz(t)} t将作为zz函数的第二个参数值
	<!--a$-->
	</script>
	如下是使用模板
	apply("a",1);
	apply("a",{x:1});
	apply("a",{x:{z:2}}});
*/
void function(){
    util.system = util.system || {};
    util.system.tpl = util.system.tpl || {};
	//tempMReg 获取模板值
	//tempRRe    对模板中 {#...}进行替换操作
	var tempMReg = /<!--\s*\^(\w+)-->(.*)<!--\1\$\s*-->/g, tempRRe = /\{#(.*?)(?:\|([\w\$]+)(?:\((.*?)\))?)?\}/g;

	//获取obj中对应的值
	function getValue(obj,key){
		if(key == "*"){
			return obj;
		}
		var x = key.split(/[.]+/);
		while(obj != null && x.length){
			obj = obj[x.shift()];
		}
		return obj;
	}

	//模板类
	util.Template = util.extend({
		//获取页面中模板数据
		getLocal:function(type){
			var s = document.getElementsByTagName("script"), n, i = 0;
			if(type == null){
				type = "text/puitemplate";
			}
			for(; i < s.length; i += 1){
				if((n = s[i]).getAttribute("type") == type){
					this.setModel(n.innerHTML);
				}
			}
			for(; i < s.length; i += 1){
				if((n = s[i]).getAttribute("type") == type){
                    //多次调用 防止重复设置
					n.parentNode.removeChild(n);
				}
			}
			return this;
		},
		//将一个带有模板数据的字符串转换为模板集
		setModel : function(str){
			var v = str.replace(/^\s+|\s+$|\n+|\r+/g, "").replace(/>\s+</g, "><"), arr;
			while(arr = tempMReg.exec(v)){
				this.data[arr[1]] = arr[2];
			}
			return this;
		},
		//应用模板
		apply : function(mId, obj){
            var _x = this.data[mId] || util.system.tpl[mId];
            if(!_x) return '';
			else{
				var me = this,f = me.format;
                var cache = {};
				f._data_.push(obj);
                var i =0;
				var x = _x.replace(tempRRe, function(str, key, fun, parms){
                    if(cache[str]){
                        return cache[str];
                    }
					var v = getValue(obj,key),rv = v;
					if(fun && f[fun]){
                        v = [v].concat((parms || "").split(/,/));
                        v.push(obj);
						rv = f[fun].apply(me, v);
					}
					if(rv == null){
						rv = "";
					}
                    cache[str] = rv;
					return rv;
				});
                // console.log(i)
                cache = undefined;
				f._data_.pop();
				return x;
			}
//			return "";
		},
		//获取当前的apply下的全局数据
		//默认获取当前正在被替换的数据 obj
		get:function(n){
			var d = this.format._data_;
			if(n == null){
				n = -1;
			}
			if(n < 0){
				n = d.length + n;
			}
			return d[n];
		},
		//执行format中另外的函数
		call:function(){
			var ag = Array.prototype.slice.call(arguments),key = ag.shift();
			return this.format[key].apply(this,ag);
		}
	},function(){
		this.data = {};
		this.format = util.extra(util.Template.format,{_data_:[]});
	});

	function getLeft(v,left){
		left = parseInt(left) || 0;
		if(left){
			return v.uniLeft(left,"..");
		}
		return v;
	}

	util.Template.format = {
		//运用其他模板
		apply:function(obj, mId){
			return this.apply(mId, obj);
		},
		//循环数组 引用其他模板
		applyArray:function(arr, mId, split){
			return arr?D.forEach(arr,function(obj){
				return this.apply(mId, obj);
			}, [],this).join(split || ""):"";
		},
		//格式化字符串 使得次字符串执行innerHTML的时候，按照次字符串原本的内容显示
		htmlEncode:function(v,left){
			if(v == null){
				v = "";
			}
			return getLeft(String(v).htmlEncode(),left);
		},
		//格式化字符串，并将回车替换为<br />
		htmlEncodeBr:function(v,left){
			if(v == null){
				v = "";
			}
			return getLeft(String(v).htmlEncode().replace(/\n/g,"<br />"),left);
		},
		//格式化为页面节点的属性值 比如value值
		valueEncode:function(v,left){
			if(v == null){
				v = "";
			}
			return getLeft(String(v).replace(/\"/g,"&#34;").replace(/\'/g,"&#39;"),left);
		}
	};

    $(function(){
        util.tpl = new util.Template();
        util.tpl.getLocal("text/tpl");
    })
}();

function ajax(url, data, success, fail, type, closeLoading) {
    // if (!closeLoading) {
        // util.openLoad(0);
    // }
    type = type || 'POST';
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType : 'json',
        success: function(d) {
            if(d.status == 1){
                success(d);

            }else{
                if(fail){
                    fail(d);
                }else{
                    alert(d.msg);
                }
            }
        },
        error : function(){
            alert('请求失败，请稍后重试');
        },
        error: function(d) {}
    });
}


void function(){
	//已经加载完毕的js
	var jsLoaded = {},jsPath = "/res/script/";

	//进栈 操作
	function stackPush(urls, callBack, charset){

		callBack && this.backs.push(callBack);
		if(typeof urls == "string"){
			this.jss.push([urls, stackShift, charset]);
		}
		else{
			for(var i = 0; i < urls.length; i += 1){
				this.jss.push([urls[i], stackShift, charset]);
			}
		}
		//如果没有在进行中，启动出栈
		if(this.flag==0){
			this.flag = 1;
			stackShift.call(this);
		}
	}

	//出栈
	function stackShift(){
		//如果存在待加载的js，优先加载js
		if(this.jss.length){
			//使用 shift 将前面的js先加载
			disorderJS.apply(this, this.jss.shift());
			return ;
		}
		//没有等待加载的js的时候，才进行回调出栈操作
		if(this.backs.length){
			//使用pop，将靠后进入的先出栈执行
			this.backs.pop().call(this);
			stackShift.call(this);
			return ;
		}
		//没有js和回调出栈，设置为0，表示无操作
		this.flag = 0;
	}

	//加载script脚本
	function loadJS(url, callBack, charset){
		//alert(url);
        var _url = url;
        if(url.indexOf('?') == -1){
            if(util.config && util.config.version){
                _url = _url + '?' + util.config.version;
            }
        }
		var t = document.createElement("script");
		t.setAttribute("type", "text/javascript");
		charset && t.setAttribute("charset", charset);
		t.onreadystatechange = t.onload = t.onerror = function(){
			if(!t.readyState || t.readyState == 'loaded' || t.readyState == 'complete'){
				t.onreadystatechange = t.onload = t.onerror = null;
				t = null;
				//防止回调的时候，script还没执行完毕
				callBack && setTimeout(function(){
					callBack(url);
				}, 100);
			}
		};
		t.src = _url;
		document.getElementsByTagName("head")[0].appendChild(t);
	}
	//js加载完毕后调用
	function requireJSed(url){
		var x = jsLoaded[url];
		if(x && x!==true){
			for(var i=0;i<x.length;i+=1){
				x[i][0].call(x[i][1],x[i][2]);
			}
			jsLoaded[url] = true;
		}
	}
	//加载js
	function requireJS(src, callBack, charset){
		var url, self = this;
		//替换url为真是的地址
		//./打头的src，定位在path目录中
		url = src.replace(/^\.\//, this.path || jsPath);
		if(!/\.[^\/]+$/.test(url)){
			url += ".js";
		}
		//如果这个js已经加载完毕，直接延时调用回调函数
		if(jsLoaded[url] === true){
			setTimeout(function(){
				callBack.call(self, src);
			}, 100);
			return;
		}
		//如果这个js正在加载中，添加回调函数到回调数组中
		if(jsLoaded[url]){
			jsLoaded[url].push([callBack,self,src]);
		}
		//设置js加载完毕的回调数组
		jsLoaded[url] = [[callBack,self,src]];
		//加载js
		loadJS(url,requireJSed,charset);
	}

	//无序下载
	//多个js一起加载
	function disorderJS(urls, callBack, charset){
		//单个js，直接调用requireJS
		if(typeof urls == "string"){
			requireJS.call(this, urls, callBack, charset);
			return this;
		}
		//存放被加载的js的对象
		var led = {};

		function loadBack(src){
			//加载完成一个，就删除一个
			delete led[src];
			//led为空的时候，表示全部加载完成
			for(var n in led){
				return;
			}
			callBack.call(this);
			loadBack = function(){};
		}

		//分布加载js
		for(var i = 0; i < urls.length; i += 1){
			led[urls[i]] = true;
			requireJS.call(this, urls[i], loadBack, charset);
		}
		return this;
	}

	//异步加载js的类
	util.chain = util.extend({
		//进行异步加载js
		//最后两个参数为回调和加载js的字符集设置
		//例如 require("a.js","b.js",["c1.js","c2.js"],"d.js",[function(){}],[charset]);
		//其中 a b c d 按照序列加载
		//c1 c2将同时加载，加载完毕后，再加载d
		require:function(){
			var ag = Array.prototype.slice.call(arguments), l = ag.length;
			if(l == 1){
				stackPush.call(this, ag[0]);
				return this;
			}
			l -= 1;
			if(typeof ag[l] == "function"){
				stackPush.call(this, ag.slice(0, l), ag[l]);
				return this;
			}
			l -= 1;
			if(ag[l] == null || typeof ag[l] == "function"){
				stackPush.call(this, ag.slice(0, l), ag[l], ag[l + 1]);
				return this;
			}
			stackPush.call(this, ag);
			return this;
		}
	},function(path){

		this.flag = 1;
		this.jss = [];
		this.backs = [];
		this.path = path;
		//页面ready后，执行出栈操作
        var that  = this;
		$(function(){
			//出栈操作
			stackShift.call(that);
		});
	});
	var reone = new util.chain();
	//new一个js加载线
	util.require =  reone.require.bind(reone);

	//防止在重复加载（同步加载的，调用函数设置后，可以防止重复加载）
	util.required = function(src){
		jsLoaded[src] = true;
	};
	util.loadJS = loadJS;
}();

util.getLocal = function(key){
    var val = localStorage.getItem(key);
    var result = '';
    if(val){
        try{
            result = JSON.parse(val);
        }catch(e){
            result = val;
        }
    }else{
        return '';
    }
    return result;
}
util.setLocal = function(key,val){
    var str = JSON.stringify(val);
    localStorage.setItem(key,str);
}
util.removeLocal = function(key){
    localStorage.removeItem(key);
}