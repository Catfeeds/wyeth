<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>添加标签</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
    <link href="/js/select2/select2.min.css" rel="stylesheet">
    <style type="text/css">
        .form-group {
            display: flex;
            align-items: baseline;
            margin-top: 30px;
        }
        .form-reserve {
            justify-content: flex-end;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top" style="background: #FFF !important;">
<!-- BEGIN HEADER -->
<form class="cmxform form-horizontal tasi-form col-lg-4" id="courseForm" method="post" action="" enctype="multipart/form-data">
<div class="form-group ">
    <label for="title" class="control-label col-lg-2">标签名称</label>
    <div class="col-lg-4" style="flex-grow: 1">
        <input class=" form-control" id="name" name="name" minlength="2" type="text">
    </div>
</div>

<div class="form-group form-reserve">
    <button class="btn btn-primary" type="submit" onclick="addTag()" style="margin: 0 15px">添加</button>
</div>
</form>

<!-- Load javascripts at bottom, this will reduce page load time -->
<script src="/admin_style/adminlab/js/jquery-1.8.3.min.js"></script>
<script src="/admin_style/adminlab/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/admin_style/adminlab/js/jquery.blockui.js"></script>

<script src="/admin_style/adminlab/assets/swfupload/js/fileprogress.js" type="text/javascript"></script>
<script src="/admin_style/adminlab/assets/swfupload/js/handlers.js" type="text/javascript"></script>

<!--[if lt IE 9]>
<script src="/admin_style/adminlab/js/excanvas.js"></script>
<script src="/admin_style/adminlab/js/respond.js"></script>
<![endif]-->
<script src="/admin_style/layer/layer.js"></script>

<script type="text/javascript">
    function addTag() {
        var name = $("#name").val();
        console.log(name);
//        $.post('/admin/course/del_img/'+idChecked[i],{
//                'del_img':'del_img'
//            },function(result){
//                if(result.status == '1'){
//                    p++;
//                    if (p == idChecked.length) {
//                        var arr = $('.delete_check');
//                        for (var j = 0; j < arr.length; j++) {
//                            if (arr[j].checked) {
//                                arr.eq(j).parent().parent().fadeOut();
//                            }
//                        }
//                        idChecked = [];
//                    }
////
//                }else{
//                    alert(result.msg);
//                    return false;
//                }
//            },'json'
//        );
        $.post('/admin/tags/add',
            {
                'name': name
            }, function (result) {
                if (result.status == '1') {
                    console.log(result);
                } else {
                    alert(result.msg);
                    return false;
                }
            }, 'json');
    }
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>