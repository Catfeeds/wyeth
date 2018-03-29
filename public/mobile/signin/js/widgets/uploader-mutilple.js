define(function(require, exports, module) {

    var webuploader = require('webuploader');
    var BASE_URL = 'http://localhost:8088/static/js/libs/webuploader/0.1.6/';

    function init(imgBtn){
        var $dndDiv = $('.source-list'),
            $imgBtn = $(imgBtn),
            file;
        var $imgList = $('.pic-list');
        var uploader = WebUploader.create({
            auto: true,
            swf: BASE_URL + 'Uploader.swf',
            server: '/file/upload',
            pick: {
                id: imgBtn,
                multiple: true
            },
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            quality: 100,//图片压缩质量
            fileNumLimit: 3,
            sendAsBinary: true,
            duplicate: false
        });
        var PicCount = 0;
        //文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            //if($imgList.find('dd').length)
            if(!/image\//g.test(file.type)){
                alert('请选择图片文件上传','warning');
                uploader.cancelFile(file);
                return;
            }
            //文件超过5M校验
            if(file.size >= 5*1024*1024){
                uploader.cancelFile(file);
                file.setStatus('invalid');
                var cancelList = uploader.getFiles('invalid');
                var str='', 
                    len = cancelList.length;
                for(var i=0;i<len;i++){
                   if(i==len-1){
                    str+=cancelList[i].name;
                   }else{
                    str+=cancelList[i].name+"，";
                   }
                }
                alert('"'+str+'"文件超过5M不能上传','warning',5000);
                return;
            }      
            PicCount++;
            var count = PicCount+$imgList.find('dd').length-1;
            if(count>3){
                uploader.cancelFile(file);
                $('#btnSelectPic').hide();
                return;
            }
            if(count==3){
                $('#btnSelectPic').hide();
            }
            uploader.makeThumb( file, function( error, src ) {
               var imgDiv = '';
                if ( !error ) {
                    //支持预览则添加预览图
                    imgDiv='<dd id="'+file.id+'"><img src="'+src+'"><div class="hover">图片上传中...</div><div class="btn-close" style="display:none;"><span class="icon icon-close"></span></div></dd>';
                    $(imgDiv).insertBefore($imgBtn);
                } 
            }, 188, 188);
            file = file;
        });
        //文件上传中
        uploader.on( 'uploadProgress', function( file, percentage ) {
           $('#'+file.id).find('.hover').html(parseInt(percentage*100)+'%<br>图片上传中');
        });
        //上传成功
        uploader.on( 'uploadSuccess', function( file, response ) {
            $('#'+file.id).find('img').attr('src',response.data.url);
        });
        //上传失败
        uploader.on( 'uploadError', function( file ) {
            var $li = $('#'+file.id);
            MZ.alert({
                content: '上传失败，请重新选择上传',
                callback: function(e){
                    $li.remove();
                    uploader.removeFile(file);
                }
            });
        });
        //上传操作完成，不分成功失败
        uploader.on( 'uploadComplete', function( file ) {   
            $('.btn-close').show();
            $('.hover').hide();
            PicCount = 0;
            if(file.statusText == 'http' || file.statusText == 'abort' || file.statusText == 'server'){
            }else{
                uploader.removeFile(file);
            }
        });

        uploader.on('error',function(code){
            if(code == 'Q_TYPE_DENIED'){
                alert('请选择图片文件上传','warning');
            }
            if(code == 'F_DUPLICATE'){
                alert('此图片已上传','warning');
            }  
            /*if(code == 'Q_EXCEED_NUM_LIMIT'){
                alert('一次最多只能上传3张图片','warning');
            }  */
        })
        $('.pic-list').delegate('.btn-close','click',function(e){
            var $this = $(this);
            $this.parent('dd').remove();
            $('#btnSelectPic').show();
        })
        return uploader;
    }
    module.exports = init;

});