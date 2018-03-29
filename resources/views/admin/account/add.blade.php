<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>惠氏妈妈微课堂</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css" />

    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">

    <!--  summernote -->
    <link href="/admin_style/flatlab/assets/summernote/dist/summernote.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" class="">
<!--header start-->
<?php echo $header; ?>
<!--header end-->
<!--sidebar start-->
<?php echo $sidebar;?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
<section class="wrapper site-min-height">
<!-- page start-->
<div class="row">
<div class="col-lg-12">
<section class="panel">
    <header class="panel-heading">
        编辑帐号
    </header>
    <div class="panel-body">
        <div class=" form">
            <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                <div class="form-group ">
                    <label for="teacher_hospital" class="control-label col-lg-2">帐号</label>
                    <div class="col-lg-6">
                        <input class=" form-control" id="word" name="username" minlength="2" type="text" value="<?php echo isset($info->username)?$info->username:''; ?>" <?php echo isset($info->username)?'disabled':''; ?>>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="teacher_hospital" class="control-label col-lg-2">密码</label>
                    <div class="col-lg-6">
                        <input class="form-control" id="word" name="password" minlength="2" type="text" value="<?php echo isset($info->password)?'******':''; ?>" <?php echo isset($info->password)?'disabled':''; ?>>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="teacher_hospital" class="control-label col-lg-2">姓名</label>
                    <div class="col-lg-6">
                        <input class=" form-control" id="word" name="fullname" minlength="2" type="text" value="<?php echo isset($info->fullname)?$info->fullname:''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">区域</label>
                    <div class="col-lg-6">
                        <div class="shihe_1" style="float: left;">
                            <select name="area" class="form-control m-bot15">
                                <option value="all" <?php echo isset($info->area)&&$info->area=='all'?'selected':''; ?>>所有</option>
                                <option value="东区" <?php echo isset($info->area)&&$info->area=='东区'?'selected':''; ?>>东区</option>
                                <option value="西区" <?php echo isset($info->area)&&$info->area=='西区'?'selected':''; ?>>西区</option>
                                <option value="南区" <?php echo isset($info->area)&&$info->area=='南区'?'selected':''; ?>>南区</option>
                                <option value="北区" <?php echo isset($info->area)&&$info->area=='北区'?'selected':''; ?>>北区</option>
                                <option value="总部" <?php echo isset($info->area)&&$info->area=='总部'?'selected':''; ?>>总部</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">帐号类型</label>
                    <div class="col-lg-6">
                        <div class="shihe_1" style="float: left;">
                            <select id="user_type_select" name="user_type" class="form-control m-bot15" onchange="userTypeChange(this.value)">
                                <option value="0" <?php echo isset($info->user_type)&&$info->user_type=='0'?'selected':''; ?>>管理员</option>
                                <option value="1" <?php echo isset($info->user_type)&&$info->user_type=='1'?'selected':''; ?>>运营人员</option>
                                <option value="2" <?php echo isset($info->user_type)&&$info->user_type=='2'?'selected':''; ?>>下载课件</option>
                                <option value="3" <?php echo isset($info->user_type)&&$info->user_type=='3'?'selected':''; ?>>上传课件</option>
                                <option value="4" <?php echo isset($info->user_type)&&$info->user_type=='4'?'selected':''; ?>>大平台物料管理</option>
                                <option value="5" <?php echo isset($info->user_type)&&$info->user_type=='5'?'selected':''; ?>>奖品管理</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div id="platform_group" class="form-group">
                    <label class="control-label col-lg-2">所属平台</label>
                    <div class="col-lg-6">
                        <div class="shihe_1" style="float: left;">
                            <select id="user_platform_select" name="user_platform" class="form-control m-bot15">
                                <?php foreach ($platform as $p) {?>
                                <option value="<?=$p['id']?>" <?php if (!empty($info->user_platform) && $p['id'] == $info->user_platform){ echo 'selected'; }?>><?=$p['author_name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">关联课程</label>
                    <div class="col-md-9">
                        <select multiple="multiple" class="multi-select" id="cids" name="cids[]">
                            <?php foreach ($courses as $course) { ?>
                            <option value="<?=$course['id']?>"
                                <?php if (!empty($info->cids) && in_array($course['id'], $info->cids)){ echo 'selected'; }?>
                                ><?=$course['title']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-danger" type="submit">保存</button>
                        <button class="btn btn-default" type="reset">重置</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
</div>
</div>
<!-- page end-->
</section>
</section>
<!--main content end-->
<!--footer start-->
<?php echo $footer;?>
<!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<!--this page plugins-->

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>


<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<script>
    $('#cids').multiSelect({
        selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='课程名称'>",
        selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='课程名称'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    function userTypeChange (value) {
        if (value == 4) {
            $('#platform_group').show();
            $('#user_platform_select').val(1);
        } else {
            $('#platform_group').hide();
            $('#user_platform_select').val(0);
        }
    }

    $(document).ready(function () {
        @if($info)
        if ('<?php echo $info->user_type; ?>' == '4') {
            $('#platform_group').show();
        } else {
            $('#platform_group').hide();
        }
        @else
            $('#platform_group').hide();
        @endif
    })
</script>
</body>
</html>