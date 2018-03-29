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

    <!--dynamic table-->
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="/admin_style/flatlab/assets/data-tables/DT_bootstrap.css" />
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
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
                            课程推送
                            <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                        </header>
                        <div class="panel-body">
                            <form id="search" action="#" class="form-horizontal tasi-form" style="margin-bottom: 10px;">
                                <div class="form-group" style="display: flex;flex-direction: row-reverse">
                                    <div class="col-md-6">
                                        <div class="input-group input-large">
                                            <span class="input-group-addon">推送时间范围</span>
                                            <input type="text" class="form-control dpd1" name="from" value="<?php echo !empty($params['from']) ? $params['from'] : ''; ?>">
                                            <span class="input-group-addon">至</span>
                                            <input type="text" class="form-control dpd2" name="to" value="<?php echo !empty($params['to']) ? $params['to'] : ''; ?>">
                                            <span class="input-group-addon">课程id</span>
                                            <input type="text" class="form-control" name="cid" value="<?php echo !empty($params['cid']) ? $params['cid'] : ''; ?>">
                                            <span class="input-group-btn">
                                            <button class="btn btn-white" name="sort" type="submit" value="id">搜索</button>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <section id="unseen">
                                <div class="space15"></div>
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>课程ID</th>
                                        <th>名称</th>
                                        <th>待推送(总计)</th>
                                        <th>已推送(总计)</th>
                                        <th>推送详情</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (!empty($list))
                                    @foreach ($list as $k => $item)

                                    <tr id="push{{ $item->id }}">
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            @if($user_info->user_type != 2)
                                            <a href="/admin/course/index?id={{ $item->id }}">{{ $item->course_name }}</a>
                                            @else
                                            {{ $item->course_name }}
                                            @endif
                                        </td>
                                        <td>{{ $item->wait_num }}</td>
                                        <td>{{ $item->excepted }}</td>
                                        <td> <button data-toggle="modal" data-target="#detail" class="btn btn-primary btn-xs" onclick="getDetail('{{ $item->cid }}','{{ $params['from'] }}','{{ $params['to'] }}')"><i class="fa fa-info-circle "></i>详情</button></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <?php echo $list->appends($params)->render(); ?>
                            </section>
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑推送</h4>
            </div>

            <div class="col-md-12 col-xs-11" style="margin: 10px 0 0 0">
                <label class="col-sm-3 control-label col-lg-3">修改推送时间</label>
                <input type="text" class="form-control course-push-date-picker">
            </div>
            <div class="col-md-12 col-xs-11" style="margin: 10px 0 0 0">
                <label class="col-sm-3 control-label col-lg-3">修改报名区间start</label>
                <input type="text" class="form-control sign-start-date-picker">
            </div>
            <div class="col-md-12 col-xs-11" style="margin: 10px 0 20px 0">
                <label class="col-sm-3 control-label col-lg-3">修改报名区间end</label>
                <input type="text" class="form-control sign-end-date-picker">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="submitChange">提交更改</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="deleteDetail()">&times;</button>
                <h4 class="modal-title" id="myModalLabel">推送详情(<?php if($params['from'] && $params['to']){ echo $params['from']."~".$params['to']; }else{ echo "3天内"; }  ?>)</h4>
            </div>
            <div class="push_data" id="push_data">
                <span style="margin-top: 15px">正在统计，请稍候...</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="deleteDetail()">确认</button>
            </div>
        </div>
    </div>
</div>


<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/admin_style/flatlab/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="/admin_style/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/data-tables/DT_bootstrap.js"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>

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

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>


<script>
    $(function(){
        $(".dpd1").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        $(".dpd2").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
<script type="text/javascript">
    var idEdit;
    $('.course-push-date-picker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: true
    });
    $('.sign-start-date-picker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: true
    });
    $('.sign-end-date-picker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: true
    });

    //点击编辑
    function clickEdit() {
        console.log(idEdit);
        var push = $('#push'+idEdit);
        $('.course-push-date-picker').val(push.children('.push_time').text());
        $('.sign-start-date-picker').val(push.children('.sign_start').text());
        $('.sign-end-date-picker').val(push.children('.sign_end').text());
    }

    //关闭时删除之前加载的数据
    function deleteDetail() {
        $('.push_data').html('<span type="margin-top: 15px">正在统计，请稍候...</span>');
    }

    function getDetail(cid,from,to) {
        // 获取推送信息
        $.ajax({
            type: "POST",
            url: '/admin/huiyao_course_push/detail',
            dataType: 'json',
            data: { cid: cid,from: from,to: to},
            success: function(msg) {
                if(msg){
                    $('.push_data').html(msg);
                }else{
                    $('.push_data').html('<br> 无数据 <br>');
                }
            }
        });
    }

    $('#submitChange').click(function () {
        var push_time = $('.course-push-date-picker').val();
        if(!push_time) {
            noty({text: "请填写推送时间", type: "warning", timeout: 2000});
            return true;
        }
        var postData = {
            'id': idEdit,
            'push_time': push_time,
            'sign_start': $('.sign-start-date-picker').val(),
            'sign_end': $('.sign-end-date-picker').val()
        };
        $.post("/admin/course_push/edit", postData, function (data) {
            if (data.status == 1) {
                noty({text: data.msg, type: "success", timeout: 2000});
                $('#editModal').modal('toggle')
                window.location.reload();
            } else {
                noty({text: data.msg, type: "error", timeout: 2000});
            }
        }, 'json');
    })

    function deleteConfirm(id) {
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '确认',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '取消',
                    className: 'btn-default'
                }
            },
            message: '确定删除该推送吗？',
            callback: function(result) {
                if(result) {
                    $.post("/admin/course_push/delete", {'id': id}, function (data) {
                        if (data.status == 1) {
                            noty({text: data.msg, type: "success", timeout: 2000});
                            window.location.reload();
                        } else {
                            noty({text: data.msg, type: "error", timeout: 2000});
                        }
                    }, 'json');
                } else {
                    return true;
                }
            },
            //title: "bootbox confirm也可以添加标题哦",
        });
    }
    function submitMyForm () {
        $('#search').submit();
    }
</script>
</body>
</html>