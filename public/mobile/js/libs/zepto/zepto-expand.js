;(function($){
    var _ajax=$.ajax;
    
    $.ajax=function(opt){
        var $target = opt.$target;
        if($target){
            if($target.attr('_loading')=='true'){
                return;
            }else{
                $target.attr('_loading','true');
            }
        }
        
        var fn = {
            error:function(XMLHttpRequest, textStatus, errorThrown){},
            success:function(data, textStatus){}
        }
        if(opt.error){
            fn.error=opt.error;
        }
        if(opt.success){
            fn.success=opt.success;
        }
        //扩展增强处理
        var _opt = $.extend(opt,{
            error:function(XMLHttpRequest, textStatus, errorThrown){
                fn.error(XMLHttpRequest, textStatus, errorThrown);
                onCallback();
            },
            success:function(data, textStatus){
                fn.success(data, textStatus);
                onCallback();
            },
            beforeSend:function(XHR){

            },
            complete:function(XHR, TS){
                onCallback();
            }
        });
        _ajax(_opt);
        function onCallback(){
            if($target){
                $target.attr('_loading','false');
            }
        }
    };
})(Zepto);