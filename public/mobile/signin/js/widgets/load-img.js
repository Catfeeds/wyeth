define(function(require, exports,modules) {

	var Loading = {};
	//加载图片资源
	/*
		Loading.loadImg({
			imgs: imgs,
			progress: $('#progressNum'),
			callback: function(){}
		})
	*/
	Loading.loadImg = function (option) {
		var $$Img = option.imgs,
			$target = option.progress,
			funcComplete = option.callback;
		var imgSources =[];
		for(var o in $$Img.manifest){
			imgSources.push($$Img.path+$$Img.manifest[o].src);		
		}
		for (var i = 0; i < imgSources.length; i++) {
			imgSources[i] = (imgSources[i]);
		};
		var loadImage = function (path, callback) {
			var img = new Image();
			img.onload = function () {
				img.onload = null;
				callback(path);
			}
	       	img.src = path;
		}
		var imgLoader = function (imgs, callback) {
			var len = imgs.length, i = 0;
			while (imgs.length) {
				loadImage(imgs.shift(), function (path) {
					callback(path, ++i, len);
				})
			}
	    }
		var percent = 0;
		imgLoader(imgSources, function (path, curNum, total) {
			percent = curNum / total;
			if($target){
				$target.html(Math.floor(percent * 100)+"%");
			}
		 	//console.log("加载："+Math.floor(percent * 100) + '%')
			if (percent == 1 && funcComplete) {
				setTimeout(function () {
					funcComplete();
					console.log('loading success');
				}, 500);
		 	}
		});
	};
	modules.exports = Loading;
});
