define(function(require, exports,modules) {
	var win = window,	
		doc = document;
	var $list = {};
	var Loading = function(){

	};
	Loading.init = function (option) {
		$list = option.$list,
		ajaxFunc = option.ajaxFunc;
		//下拉加载更多
        $(document).on('scroll touchmove',function(){
            if($(win).scrollTop()+$(win).height()+50>=$(doc).height()){
            	if($('.animate-loading-spiner').length==0 && $('.nomore').length==0){
            		$('<div class="center loading-more"><div class="animate-loading-spiner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>').insertAfter($list);
            	}
                ajaxFunc();
            }
        });
	};
	Loading.finish = function(){
		$('.loading-more').remove();
	}
	Loading.noMore = function(){
		$('.loading-more').remove();
		$('<div class="center nomore" style="color:#666;padding: .5em 0 1em 0;">暂无更多</div>').insertAfter($list);
	}
	modules.exports = Loading;
});
