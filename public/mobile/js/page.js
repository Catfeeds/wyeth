$(function(){
	SizeElem();
	
	BindEvent();
	
});

function SizeElem(){
	
}

function BindEvent(){
	
	
}

function ShowLoading(text){
	$("div.content").hide();
	$("div.loading").show();
}

function HideLoading(delay, callback){
	if(!delay){
		delay = 10;
	}
	setTimeout(function () {
        $("div.loading").fadeOut(300, function () {
            $("div.content").show();
            if(callback && $.type(callback) == "function"){
            	callback.call();
            }
        });
    }, delay);
}

function ShowAlert(msg){
	//alert(msg);
	//return;
	if($("#alert").length > 0){
		$("#alert").show().find(".msg").html(msg);
		return;
	}
	var html = '';
	html += '<div id="alert" class="dialog">';
	html += 	'<div class="overlay"></div>';
	html += 	'<div class="box">';
	html += 	'<div class="head">消息提示</div>';
	html +=    		'<div class="body">';
	html +=	   		'<div class="ico-alert"></div>';
	html +=	   		'<div class="msg">' + msg + '</div>';
	html +=     '</div>';
	html +=     '<div class="foot">';
    html +=     	'<a class="btn btn-blue btn-confirm">确定</a>';
	html +=     '</div>';
	html += '</div>';
	$("body").append(html).find("a.btn").on("click", function(){
		if($(this).hasClass("btn-confirm")){
			$("#alert").hide();
		}
	});
}

function GetQuery(name){
	if(!name){
		var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+","g")); 
	    if(result == null){
	    	return "";
	    }
	    for(var i = 0; i < result.length; i++){
	        result[i] = result[i].substring(1);
	    }
	    return result;
	}
	if(typeof(name) == "string"){
		var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));
	    if(result == null || result.length < 1){
	        return "";

	    }
	    return result[1];
	}
	else if(typeof(name) == "number"){
		if(index == null){
	         return "";

	     }
	     var queryStringList = GetQuery();
	     if (index >= queryStringList.length){
	         return "";
	     }
	     var result = queryStringList[index];
	     var startIndex = result.indexOf("=") + 1;
	     result = result.substring(startIndex);
	     return result;
	}
}


