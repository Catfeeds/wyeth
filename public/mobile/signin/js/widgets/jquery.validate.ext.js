define(function(require, exports) {
	/*
	 * jQuery.validate的optional(element)，用于表单控件的值不为空时才触发验证。 
	 * jQuery.validator.addMethod("division", function(value, element) {  
	 * return this.optional(element) || value % 2 == 0 && value % 3 == 0;  }, 
	 * "必须能被2和3整除");  
	 * 如果值为空时也要触发验证，移除optional(element)
	 * $f.addMethod("division", function(value, element) {  
	 * return value % 2 == 0 && value % 3 == 0;  }, 
	 * "必须能被2和3整除");
	 */ 
	/*$.validator.addMethod("load", function(value, element, params) {
		if ( this.optional(element) ) //this.optional(element) == false 即不为空
			return "dependency-mismatch";
		var previous = this.previousValue(element);
		//if ( previous.old !== value ) {//如果跟上次一样这不去请求
			previous.old = value;
			param = params.split("%");
			// 重新组装参数，替换#
			var url = param[0];
			var params2 = '';
			if (param[1]) {
				var ss = param[1].split('&');
				for (var i = 0; i < ss.length; i++) {
					var ss2 = ss[i].split('=');
					var v = ss2[1];
					if (v.charAt(0) == '#') 
						v = $(v).val();
					if(v!=null&&v!=undefined)
					params2 +=  ss2[0] + '=' + v+'&';
				}
			}
			var str = findUserName(url,params2);
			if(str == 'true'){
				previous.valid = true;
			}else{
				previous.valid = false;
			}
		} else if( this.pending[element.name] ) {
			return "pending";
		}
		return previous.valid;
	}, "输入数据不合格");*/

	$.validator.addMethod("myemail", function(value, element,param) {
		return this.optional(element) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);}, "请输入正确的邮箱地址(XXX@XXX.XXX)");

	//手机号码验证
	$.validator.addMethod("validmobile", function(value, element) {
		var length = value.length;
		var mobile = /^[1][3,4,5,7,8]\d{9}$/;
		return this.optional(element) || (length == 11 && mobile.test(value));
	}, "手机号码格式不对");

	//电话号码验证
	jQuery.validator.addMethod("validphone", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel = /^0[0-9]{2,3}\-[2-9][0-9]{6,7}$/;
		return this.optional(element) || (tel.test(value));
	}, "电话号码格式为：区号-固定号码");

	//只能输入数字
	jQuery.validator.addMethod("onlynum", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel = /^[0-9]+$/;
		return this.optional(element) || (tel.test(value));
	}, "只能输入数字");

	//只能输入两位数字
	jQuery.validator.addMethod("numWith2Decimal", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var num = /^-?\d+\.?\d{0,2}$/;
		return this.optional(element) || (num.test(value));
	}, "只能输入两位小数");
	//密码验证，数字及字母
	jQuery.validator.addMethod("password2", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel = /^(?!^\d+$)(?!^[a-zA-Z]+$)[0-9a-zA-Z]{6,30}$/;
		return this.optional(element) || (tel.test(value));
	}, "只能输入6~30位的数字、字母组合");

	jQuery.validator.addMethod("sequence4", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel = /^[1-9]{1}[0-9]{0,3}$/;
		return this.optional(element) || (tel.test(value));
	}, "只能输入1~9999的数字");
	
	jQuery.validator.addMethod("idcardNumber", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel =  /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
		return this.optional(element) || (tel.test(value));
	}, "输入的身份证不合法");
	
	jQuery.validator.addMethod("weixin", function(value, element) {
		if(value == null || value == undefined)
			return true;
		var tel =  /^[a-zA-Z\d_]{5,30}$/;
		return this.optional(element) || (tel.test(value));
	}, "输入的5~30位的数字、字母或下划线");
	
	jQuery.validator.addMethod("trim", function(value, element) {
		if(value == null || value == undefined)
			return true;
		return this.optional(element) || !(value.indexOf(" ")>-1);
	}, "不可以输入空格");
	
	/*
	 * 覆盖错误信息样式
	 * */
	(function($) {
	$.extend($.validator.defaults, {
		errorElement: "p",
		errorPlacement: function(error, element) {	
			
			if(element.parent(".form-group").length==0){
				error.appendTo(element.parent(".js-valid"));
			}else{
				error.appendTo(element.parent(".form-group"));
			}
			
		},
		success: function(label) {
			label.remove();
		}
	});
	})(jQuery);

	/*function showMessage(error,element){
		if (element.hasClass('error')) {
			var name = element.attr('name') || element.attr('id');
			var label = element.next("label[for='" + name + "']");
			if ( label.length == 0 ) error.insertAfter(element);
			error.show();
		}
	}*/
});
