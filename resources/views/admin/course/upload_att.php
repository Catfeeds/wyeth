<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>上传课件</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_responsive.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />

   <link href="/admin_style/adminlab/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/uniform/css/uniform.default.css" />
    <style type="text/css">
        *{ margin: 0px; padding: 0px;}
        #return_image{ border-top: 1px solid #CCC; padding-top: 15px; margin-top: 15px; float: left; width: 94%; margin-left: 2%; padding-left: 2%;}
        #return_image li{ width: 120px; list-style: none; float: left; margin-right: 15px; text-align: center; line-height: 22px;}
        #return_image .img_p img{ width: 120px; border: 1px solid #ccc; padding: 3px;}
        input[type='checkbox'] {margin: 0 10px 0 0;}
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top" style="background: #FFF !important;">
   <!-- BEGIN HEADER -->
   <p class="swf_button" style="margin-left: 20px; margin-top: 20px; line-height: 30px;"><span id="spanButtonPlaceholder"></span><font>　　~请选择要上传的课件</font></p>
   <input id="btnCancel" type="button" value="取消上传" disabled="disabled" style="display:none;" />
   <div class="fieldset flash" id="fsUploadProgress"></div>
   <p id="divStatus" style="display:none;">文件已上传</p>
   <div class="return_image">
       <div style="display: flex; flex-direction: row">
           <input id="check_all" type="checkbox" onclick="checkAllChange(this)" style="margin: 6px 0 0 20px ">
           <label for="check_all" style="margin-right: 20px">全选</label>
           <a href="javascript:;" class="del_btn" onclick="del_selected(this)">删除选中</a>
       </div>

       <ul id="return_image">
            <?php if(!empty($list)){
                foreach($list as $key=>$item){
                    ?>
                    <li>
                        <p class="img_p"><img src="<?php echo $item->img; ?>"></p>
                        <p class="bot_p"><input class="delete_check" id="<?php echo $item->id; ?>" type="checkbox" onchange="onCheckChange(this, '<?php echo $item->id; ?>')"><a href="javascript:;" class="del_btn" onclick="del_img(this,'<?php echo $item->id; ?>')">删除</a> </p>
                    </li>
           <?php
                }
            }
            ?>
       </ul>
   </div>

   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid" style="margin-top: 0px; text-align: center;">

   </div>
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="/admin_style/adminlab/js/jquery-1.8.3.min.js"></script>
   <script src="/admin_style/adminlab/assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="/admin_style/adminlab/js/jquery.blockui.js"></script>
   <script type="text/javascript">
       var idChecked = [];

       function show_uplad_ware(src,img_url,img_id){
           var html = '<li> <p class="img_p"><img src="'+img_url+'"></p> <p class="bot_p"><input class="delete_check" type="checkbox" onchange="onCheckChange(this, '+img_id+')"><a href="javascript:;" class="del_btn" onclick="del_img(this,\''+img_id+'\')">删除</a> </p> </li>';
           $("#return_image").append(html);
       }

       Array.prototype.remove = function(val) {
           var index = this.indexOf(val);
           if (index > -1) {
               this.splice(index, 1);
           }
       };

       function checkAllChange(obj) {
           var arr = $('.delete_check');
           var len = arr.length;
           if ($(obj).attr('checked') != undefined) {
               for (var i = 0; i < len; i++) {
                   arr[i].checked = true;
                   idChecked.push(arr[i].id);
               }
           } else {
               for (var i = 0; i < len; i++) {
                   arr[i].checked = false;
                   idChecked.remove(arr[i].id);
               }
           }

       }

       function onCheckChange(obj, id) {
           if ($(obj).attr('checked') != undefined) {
               idChecked.push(id);
           } else {
               idChecked.remove(id);
           }
       }

       function del_selected(obj) {
           console.log($('.delete_check').eq(0));
           console.log($(obj));
           var p = 0;
           layer.confirm('确定要删除选中图片吗？', function (index) {
               for (var i = 0; i < idChecked.length; i++) {
                   $.post('/admin/course/del_img/'+idChecked[i],{
                           'del_img':'del_img'
                       },function(result){
                           if(result.status == '1'){
                               p++;
                               if (p == idChecked.length) {
                                   var arr = $('.delete_check');
                                   for (var j = 0; j < arr.length; j++) {
                                       if (arr[j].checked) {
                                           arr.eq(j).parent().parent().fadeOut();
                                       }
                                   }
                                   idChecked = [];
                               }
//
                           }else{
                               alert(result.msg);
                               return false;
                           }
                       },'json'
                   );
                   layer.close(index);
               }
           })
       }

       function del_img(obj,id){
           layer.confirm('确定要删除该图片吗？', function(index){
               $.post('/admin/course/del_img/'+id,{
                       'del_img':'del_img'
                   },function(result){
                       if(result.status == '1'){
                           $(obj).parent().parent().fadeOut();
                       }else{
                           alert(result.msg);
                           return false;
                       }
                   },'json'
               );
               layer.close(index);
           });
       }
   </script>
   <link href="/admin_style/adminlab/assets/swfupload/css/default.css" rel="stylesheet" type="text/css" />
   <script src="/admin_style/adminlab/assets/swfupload/swfupload/swfupload.js" type="text/javascript"></script>
   <script src="/admin_style/adminlab/assets/swfupload/js/swfupload.swfobject.js" type="text/javascript"></script>

   <script src="/admin_style/adminlab/assets/swfupload/js/swfupload.queue.js" type="text/javascript"></script>

   <script src="/admin_style/adminlab/assets/swfupload/js/fileprogress.js" type="text/javascript"></script>
   <script src="/admin_style/adminlab/assets/swfupload/js/handlers.js" type="text/javascript"></script>
   <script type="text/javascript">
       var swfu;
       SWFUpload.onload = function () {
           var settings = {
               flash_url : "/admin_style/adminlab/assets/swfupload/swfupload/swfupload.swf",
               post_params: {
                   "field" : "thumb",
                   'admin_id':'1',
                   'type':'one_image',
                   'gif_no_water':'1',
                   'watermark':'0',
                   'thumb':'1'
               },
               file_size_limit : "2MB",//文件大小限制
               file_types : "*.jpg;*.gif;*.png;*.jpeg;",
               upload_url: "/admin/course/save_upload/<?php echo $id; ?>",
               button_image_url: "/admin_style/adminlab/assets/swfupload/images/flash_btn.png",
//file_types : "*.*",
//file_types : "*.flv",
               file_types_description : "All Files",//文件类型
//file_types_description : "*.flv",//文件类型
               file_upload_limit : 50,
               file_queue_limit : 0,
               custom_settings : {
                   progressTarget : "fsUploadProgress",
                   cancelButtonId : "btnCancel",
                   field_key: "thumb"
               },
               debug: false,

// Button Settings
               button_placeholder_id : "spanButtonPlaceholder",//按钮id
               button_text: "",//按钮文字<span class="theFont">浏览</span>
               button_text_style: ".theFont { font-size: 16; }",//按钮文字字号
               button_text_left_padding: 12,//按钮左边距
               button_text_top_padding: 3,//按钮上边距
               button_width: "83",//按钮宽
               button_height: "29",//按钮高
//button_width: 61,
//button_height: 22,

// The event handler functions are defined in handlers.js
               swfupload_loaded_handler : swfUploadLoaded,
               file_queued_handler : fileQueued,
               file_queue_error_handler : fileQueueError,
               file_dialog_complete_handler : fileDialogComplete,
               upload_start_handler : uploadStart,
               upload_progress_handler : uploadProgress,
               upload_error_handler : uploadError,
               upload_success_handler : uploadSuccess,
               upload_complete_handler : uploadComplete,
               queue_complete_handler : queueComplete, // Queue plugin event

// SWFObject settings
               minimum_flash_version : "9.0.28",
               swfupload_pre_load_handler : swfUploadPreLoad,
               swfupload_load_failed_handler : swfUploadLoadFailed
           };

           swfu = new SWFUpload(settings);
       }
       </script>

       <!--[if lt IE 9]>
   <script src="/admin_style/adminlab/js/excanvas.js"></script>
   <script src="/admin_style/adminlab/js/respond.js"></script>
   <![endif]-->
   <script src="/admin_style/layer/layer.js"></script>
   <!-- END JAVASCRIPTS -->   
</body>
<!-- END BODY -->
</html>