<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>惠氏妈妈微课堂</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-colorpicker/css/colorpicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css"/>

    <!--dynamic table-->
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet"/>
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/admin_style/flatlab/assets/data-tables/DT_bootstrap.css"/>
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .txt-div {
            margin-right: 65px;
        }

        .row-bot {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
        }

        .txt-gray {
            color: dimgrey;
        }

        body {
            /*background-color: white;*/
            color: rgba(0, 0, 0, 0.7);
        }
    </style>
</head>

<body>
<section id="container">
    <!--header start-->
<?php echo $header; ?><!--header end-->
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

                        <div class="panel-body">
                            <div class="tab-content">

                                <section id="unseen">
                                    <h4 style="margin-left: 10px; margin-top:6px;">事件管理</h4>
                                    <div style="padding-top: 20px;">
                                        <div class="row-bot panel-heading tab-bg-dark-navy-blue">
                                            <ul class="nav nav-tabs">
                                                <li class="active" id="eve-list"><a href="#evlist" data-toggle="tab">事件列表</a></li>
                                                <li class="" id="eve-trash"><a href="#trash" data-toggle="tab">事件回收站</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <form id="search" action="#">
                                    <div class="row">
                                        <div class="col-lg-12"
                                             style="display: flex; flex-direction: row; justify-content: flex-end; margin-top: 25px;">
                                            <button type="button" class="btn btn-sm" id="remove-but"
                                                    style="font-size: 14px; background-color: rgba(255,60,60,0.9); color: white" onclick="deleteEvents()">
                                                <span class="glyphicon glyphicon-trash" style="font-size: 12px;"></span>
                                                删除
                                            </button>
                                            <div class="input-group col-md-3"
                                                 style="margin-left: 25px;margin-right: 25px;">
                                                <input type="text" class="form-control" placeholder="请输入事件名/ID"
                                                       name="id" value="<?php echo !empty($params['id'])?$params['id']:''?>" />

                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary"
                                                             type="submit"> 搜索</button>
                                                </span>

                                            </div>

                                            <div class="dropdown">
                                                <div id="dropdownMenu1" data-toggle="dropdown">
                                                    <i class="glyphicon glyphicon-question-sign"
                                                       style="color: rgba(0,0,0,0.3); font-size: 20px; margin-top:5px;"></i>
                                                </div>
                                                <ul class="dropdown-menu"
                                                    style="margin-left: -450px;font-size: 12.5px; width:475px; padding: 15px 15px;">
                                                    <li>
                                                        <p>数据指标说明</p>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li style="">
                                                        <p>事件数: 所选日期内，事件发生的次数。
                                                            <br><br>
                                                            事件达成用户数: 所选日期内，触发某事件的设备数。
                                                            <br><br>
                                                            每启动发生次数: 所选日期内，平均每次使用应用期间触发该事件的次数。
                                                            <br><br>
                                                            注：为了保证运算速度，我们只提供最近一年的自定义事件相关数据。如需更多,请联系我们。</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="tab-content" style="margin-top: 50px;">
                                        {{--事件列表--}}
                                        <div class="tab-pane fade in active" id="evlist">
                                            <div class="container" style="padding-left: 0;">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table table-condensed table-hover table-bordered table-event"
                                                               style="text-align: center;;">
                                                            <thead>
                                                            <tr style="text-align: center;">
                                                                <td><label><input type="checkbox" name="checkBox"
                                                                                  value='all'></label></td>
                                                                <td>事件名称</td>
                                                                <td>事件ID</td>
                                                                <td>平台</td>
                                                                <td>事件数</td>
                                                                <td>事件达成用户数</td>
                                                                {{--<td>每启动发生次数<i class="glyphicon glyphicon-resize-vertical" style="color: rgba(0,0,0,0.3)"></i></td>--}}
                                                                <td>Label状态</td>
                                                                <td>操作</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($list)) {
                                                            foreach ($list as $item) {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <label><input type="checkbox" name="checkBox"
                                                                                  value='{{ $item->name }}'></label>
                                                                </td>
                                                                {{--复选框--}}
                                                                <td>
                                                                    <a href="/admin/event/detail/{{ $item->name }}">{{ $item->desc }}</a>
                                                                </td>
                                                                {{--事件名称--}}
                                                                <td>{{ $item->name }}</td>
                                                                {{--事件ID--}}
                                                                <td><i class="fa fa-html5"
                                                                       style="font-size: 18px; color: rgba(0,0,0,0.4);"></i>
                                                                </td>
                                                                {{--平台--}}
                                                                <td>{{ $item->num }}</td>
                                                                {{--事件数--}}
                                                                <td>{{ $item->user_num }}</td>
                                                                {{--事件达成用户数--}}
                                                                {{--<td>{{$item['occ_num']}}</td>--}}
                                                                {{--每启动发生次数--}}
                                                                <td style="
                                                                    @if($item->lab_status =='过量使用')
                                                                            color: orange;
                                                                    @elseif($item->lab_status =='失效')
                                                                            color: black;
                                                                    @endif">{{ $item->lab_status }}</td>
                                                                {{--Label状态--}}
                                                                <td>
                                                                    <a href="/admin/event/detail/{{ $item->name }}">查看</a>
                                                                </td>
                                                                {{--操作--}}
                                                            </tr>
                                                            <?php }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                        {!! $list->render() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{--事件回收站--}}
                                        <div class="tab-pane fade" id="trash">
                                            <div class="container" style="padding-left: 0;">
                                                <div class="row col-lg-12">
                                                    <table class="table table-condensed table-hover table-bordered table-event"
                                                           style="text-align: center;;">
                                                        <thead>
                                                        <tr style="text-align: center;">
                                                            {{--<td><label><input type="checkbox" name="checkBox"--}}
                                                                                  {{--value='all'></label></td>--}}
                                                            <td>事件名称</td>
                                                            <td>事件ID</td>
                                                            {{--<th>事件类型</th>--}}
                                                            <td>平台</td>
                                                            <td>事件数s</td>
                                                            <td>事件达成用户数</td>
                                                            {{--<td>每启动发生次数<i class="glyphicon glyphicon-resize-vertical" style="color: rgba(0,0,0,0.3)"></i></td>--}}
                                                            <td>Label状态</td>
                                                            <td>操作</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(!empty($trashList)) {
                                                        foreach ($trashList as $k => $item) {
                                                        ?>
                                                        <tr>
                                                            {{--<td>--}}
                                                                {{--<label><input type="checkbox" name="checkBox"--}}
                                                                              {{--value='{{ $item->name }}'></label>--}}
                                                            {{--</td>--}}
                                                            {{--复选框--}}
                                                            <td>
                                                                <a href="/admin/event/detail/{{ $item->name }}">{{ $item->desc }}</a>
                                                            </td>
                                                            {{--事件名称--}}
                                                            <td>{{ $item->id }}</td>
                                                            {{--事件ID--}}
                                                            <td><i class="fa fa-html5"
                                                                   style="font-size: 18px; color: rgba(0,0,0,0.4);"></i>
                                                            </td>
                                                            {{--平台--}}
                                                            <td>{{ $item->num }}</td>
                                                            {{--事件数--}}
                                                            <td>{{ $item->user_num }}</td>
                                                            {{--事件达成用户数--}}
                                                            {{--<td>{{ $item->occ_num}}</td>--}}
                                                            {{--每启动发生次数--}}
                                                            <td><a href="#" style="
                                                                @if($item->lab_status=='过量使用')
                                                                        color: orange;
                                                                @elseif($item->lab_status=='失效')
                                                                        color: black;
                                                                @endif">{{ $item->lab_status }}</a></td>
                                                            {{--Label状态--}}
                                                            <td>
                                                                <a href="/admin/event/detail/{{ $item->name }}">查看</a>
                                                            </td>
                                                            {{--操作--}}
                                                        </tr>
                                                        <?php }
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                    {!! $trashList->render() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </section>
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
<script src="/js/bootbox.4.4.0.min.js"></script>
<!-- <script src="/admin_style/js/jqPaginator.js"></script> -->
<script src="/admin_style/flatlab/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/admin_style/flatlab/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript"
        src="/admin_style/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/data-tables/DT_bootstrap.js"></script>
<script src="/admin_style/flatlab/js/respond.min.js"></script>

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>


<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script>

    $('.table-event thead tr td label input').on('click', function () {
        if($(this)["0"].checked === true) {
            $('.table-event tbody tr td label input').each(function () {
                $(this)["0"].checked = true;
            });
        } else {
            $('.table-event tbody tr td label input').each(function () {
                $(this)["0"].checked = false;
            });
        }
    });

    $('.table-event tbody tr td label input').on('click', function () {
        if($(this)["0"].checked === false) {
            $('.table-event thead tr td label input')["0"].checked = false;
        }
    });

    $('#eve-list').on('click',function () {
        $('#remove-but').show();
    })
    $('#eve-trash').on('click',function () {
        $('#remove-but').hide();
    })

    function deleteEvents() {

        var checkVal = [];
        $('.table-event tbody tr td label input[name="checkBox"]:checked').each(function () {
            checkVal.push($(this).val());
        });
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
            message: '确定删除这些事件吗？',
            callback: function (result) {
                // events为要删除的事件名数组
                var events = [];
                $('.table-event tbody tr td label input:checked').each(function () {
                        events.push($(this).context.value);
                    }
                );
                console.log(events);
                // 请求接口进行删除操作

                if (result) {
                    $.post("/admin/event/delete", {'ids': events}, function (data) {
                        console.log(data);
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
            }
        });
    }
</script>
</body>
</html>
